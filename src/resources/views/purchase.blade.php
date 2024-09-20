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
            <button type="button" onclick="togglePaymentOptions()">変更する</button>
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
        <div id="card-element-container" style="display: none;">
          <div id="card-number-element"></div>
          <div id="card-expiry-element"></div>
          <div id="card-cvc-element"></div>
          <input type="text" id="postal-code" maxlength="8">
        </div>
        <button class="buy-button" id="submit-button">購入する</button>
      </div>
    </div>
  </main>

  <script>
    function togglePaymentOptions() {
      var paymentMethodSelect = document.getElementById('payment-method');
      if (paymentMethodSelect.style.display === 'none' || paymentMethodSelect.style.display === '') {
        paymentMethodSelect.style.display = 'inline-block';
      } else {
        paymentMethodSelect.style.display = 'none';
      }
    }

    function updatePaymentMethod() {
      var paymentMethod = document.getElementById('payment-method').value;
      document.getElementById('selected-payment-method').innerText = paymentMethod;

      if (paymentMethod === 'クレジットカード') {
        document.getElementById('card-element-container').style.display = 'block';
        cardNumberElement.mount('#card-number-element');
        cardExpiryElement.mount('#card-expiry-element');
        cardCvcElement.mount('#card-cvc-element');
      } else {
        document.getElementById('card-element-container').style.display = 'none';
      }
    }

    var stripe = Stripe('pk_test_51PnN81KUcLKzkipSHyequBLJXlYm7A3z0RHhe0Ck76SEO6is0Bp9m2eqxJH8izrLNI3vqeYxFgnpu4c2AHoam92200FXru96oa');
    var elements = stripe.elements();
    var cardNumberElement = elements.create('cardNumber');
    var cardExpiryElement = elements.create('cardExpiry');
    var cardCvcElement = elements.create('cardCvc');
    var itemId = '{{ $item->id }}';

    document.getElementById('submit-button').addEventListener('click', function() {
      var paymentMethod = document.getElementById('selected-payment-method').innerText;
      var itemPrice = parseInt('{{ $item->price }}', 10);
      var amount = itemPrice * 100;

      var postalCode = document.getElementById('postal-code').value;

      if (paymentMethod === 'クレジットカード') {
        stripe.createToken(cardNumberElement).then(function(result) {
          if (result.error) {
            alert(result.error.message);
          } else {
            processPayment(result.token.id, amount, paymentMethod, postalCode);
          }
        });
      } else if (paymentMethod === 'コンビニ払い' || paymentMethod === '銀行振込') {
        savePurchaseData(paymentMethod, amount, postalCode);
      }
    });

    function processPayment(token, amount, paymentMethod, postalCode) {
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
            payment_method: paymentMethod,
            postal_code: postalCode
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
            alert('決済に失敗しました。');
          }
        })
        .catch(function(error) {
          console.error('決済処理でエラーが発生しました:', error);
          alert('決済に失敗しました。再度お試しください。');
        });
    }

    function savePurchaseData(paymentMethod, amount, postalCode) {
      fetch('/purchase/save', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            item_id: itemId,
            amount: amount,
            payment_method: paymentMethod,
            postal_code: postalCode
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