<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COACHTECHフリマ - ログイン</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
  <header>
    <a href="/" class="logo">
      <img src="{{ asset('images/logo.svg') }}" />
    </a>
  </header>
  <main>
    <h1>ログイン</h1>
    @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    <form action="{{ route('login') }}" method="post">
      @csrf
      <div class="form-group">
        <label for="email">メールアドレス</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
      </div>
      <div class="form-group">
        <label for="password">パスワード</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="login-button">ログインする</button>
    </form>
    <p class="register-link"><a href="{{ route('register') }}">会員登録はこちら</a></p>
  </main>
</body>

</html>