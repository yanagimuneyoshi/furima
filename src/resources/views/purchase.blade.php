<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>購入ページ</title>
  <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
</head>

<body>
  <header>
    <div class="logo">COACHTECH</div>
    <input type="text" placeholder="なにをお探しですか？" class="search-bar">
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
        <div class="item-info">
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
        <button class="buy-button">購入する</button>
      </div>
    </div>
  </main>
  <script>
    function showPaymentOptions() {
      document.getElementById('payment-method').style.display = 'inline-block';
    }

    function updatePaymentMethod() {
      var paymentMethod = document.getElementById('payment-method').value;
      document.getElementById('selected-payment-method').innerText = paymentMethod;
    }
  </script>
</body>

</html>