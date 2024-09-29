<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>住所の変更</title>
  <link rel="stylesheet" href="{{ asset('css/address_edit.css') }}">
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
    <div class="address-edit-container">
      <h1>住所の変更</h1>
      <form method="POST" action="{{ route('address.update', ['item_id' => $item->id]) }}">
        @csrf
        <div class="form-group">
          <label for="postal_code">郵便番号</label>
          <input type="text" id="postal_code" name="postal_code" value="{{ auth()->user()->postal_code }}" required>
        </div>
        <div class="form-group">
          <label for="address">住所</label>
          <input type="text" id="address" name="address" value="{{ auth()->user()->address }}" required>
        </div>
        <div class="form-group">
          <label for="building">建物名</label>
          <input type="text" id="building" name="building" value="{{ auth()->user()->building }}">
        </div>
        <button type="submit" class="update-button">更新する</button>
      </form>
    </div>
  </main>
</body>

</html>