<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>購入ページ</title>
  <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
  <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
  <header>
    <a href="/" class="logo">
      <img src="{{ asset('images/logo.svg') }}" alt="COACHTECHロゴ" />
    </a>


    <div class="auth-buttons">
      @if (Auth::check())
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout">ログアウト</button>
      </form>
      <a href="{{ route('mypage') }}" class="mypage">マイページ</a>
      @else
      <a href="{{ route('login') }}" class="login">ログイン</a>
      <a href="{{ route('register') }}" class="register">会員登録</a>
      @endif
      <a href="{{ route('sell') }}" class="sell">出品</a>
    </div>
  </header>
  <main>
    <div class="purchase-container">
      <div class="purchase-details">
        <!-- <div class="item-info">
          <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->title }}">
          <div>
            <h2>{{ $item->title }}</h2>
            <p>¥{{ number_format($item->price) }}</p>
          </div>
        </div> -->
        <div class="item-info" data-price="{{ $item->price }}">
          <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->title }}">
          <div>
            <h2>{{ $item->title }}</h2>
            <p>¥{{ number_format($item->price) }}</p>
          </div>
        </div>

        <div class="purchase-options">
          <div class="option">
            <span>支払い方法</span>
            <a href="#" onclick="showPaymentOptions()">変更する</a>
            <select id="payment-method" style="display: none;" onchange="updatePaymentMethod()">
              <option value="コンビニ払い" selected>コンビニ払い</option>
              <option value="クレジットカード">クレジットカード</option>
              <option value="銀行振込">銀行振込</option>
            </select>
          </div>
          <div class="option">
            <span>配送先</span>
            <a href="{{ route('purchase.address', ['item_id' => $item->id]) }}">変更する</a>
          </div>
        </div>
      </div>
      <div class="purchase-summary">
        <table>
          <tr>
            <th>商品代金</th>
            <td>¥{{ number_format($item->price) }}</td>
          </tr>
          <tr>
            <th>支払い金額</th>
            <td>¥{{ number_format($item->price) }}</td>
          </tr>
          <tr>
            <th>支払い方法</th>
            <td id="selected-payment-method">コンビニ払い</td>
          </tr>
        </table>
        <div id="card-element" style="display: none;">
          <!-- Stripe Elementsで作成されたカード要素が挿入されます -->
        </div>
        <button class="buy-button" id="submit-button">購入する</button>
      </div>
    </div>
  </main>

  <script>
    // 支払い方法の選択肢を表示/非表示にする関数
    function showPaymentOptions() {
      var paymentMethodSelect = document.getElementById('payment-method');
      if (paymentMethodSelect.style.display === 'none' || paymentMethodSelect.style.display === '') {
        paymentMethodSelect.style.display = 'inline-block';
      } else {
        paymentMethodSelect.style.display = 'none';
      }
    }

    // Stripeのセットアップ
    var stripe = Stripe('pk_test_51PnN81KUcLKzkipSHyequBLJXlYm7A3z0RHhe0Ck76SEO6is0Bp9m2eqxJH8izrLNI3vqeYxFgnpu4c2AHoam92200FXru96oa');
    var elements = stripe.elements();
    var cardElement = elements.create('card');
    var itemId = '{{ $item->id }}'; // BladeからJavaScriptにitem_idを渡す

    // 支払い方法の変更を処理する関数
    function updatePaymentMethod() {
      var paymentMethod = document.getElementById('payment-method').value;
      document.getElementById('selected-payment-method').innerText = paymentMethod;

      if (paymentMethod === 'クレジットカード') {
        document.getElementById('card-element').style.display = 'block';
        cardElement.mount('#card-element');
      } else {
        document.getElementById('card-element').style.display = 'none';
      }
    }

    // 購入ボタンがクリックされた時の処理
    document.getElementById('submit-button').addEventListener('click', function() {
      var paymentMethod = document.getElementById('selected-payment-method').innerText;

      // 商品の価格をデータ属性から取得して、センに変換
      var itemPrice = parseInt('{{ $item->price }}', 10); // 商品価格を整数として取得
      var amount = itemPrice * 100; // センに変換

      if (paymentMethod === 'クレジットカード') {
        stripe.createToken(cardElement).then(function(result) {
          if (result.error) {
            alert(result.error.message);
          } else {
            // クレジットカード決済処理
            processPayment(result.token.id, amount, paymentMethod);
          }
        });
      } else if (paymentMethod === 'コンビニ払い' || paymentMethod === '銀行振込') {
        savePurchaseData(paymentMethod, amount);
      }
    });

    // 決済処理を行う関数
    function processPayment(token, amount, paymentMethod) {
      fetch('/purchase/charge', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            token: token,
            item_id: itemId,
            amount: amount,
            payment_method: paymentMethod
          })
        })
        .then(function(response) {
          return response.json();
        })
        .then(function(data) {
          if (data.success) {
            alert('注文ありがとうございます'); //クレジットカードメッセージ
            window.location.href = '/'; // 注文完了ページにリダイレクト
          } else {
            alert('決済に失敗しました。');
          }
        })
        .catch(function(error) {
          console.error('決済処理でエラーが発生しました:', error);
          alert('決済に失敗しました。再度お試しください。');
        });
    }

    // 購入情報を保存する関数
    function savePurchaseData(paymentMethod, amount) {
      fetch('/purchase/save', { // エンドポイントを正しいものに変更
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            item_id: itemId,
            amount: amount,
            payment_method: paymentMethod
          })
        })
        .then(function(response) {
          return response.json();
        })
        .then(function(data) {
          if (data.success) {
            alert('注文ありがとうございます');
            window.location.href = '/';
          } else {
            alert('保存に失敗しました');
          }
        })
        .catch(function(error) {
          console.error('保存処理でエラーが発生しました:', error);
          alert('購入情報の保存に失敗しました。再度お試しください。');
        });
    }
  </script>

</body>

</html>