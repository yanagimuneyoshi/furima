<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COACHTECHフリマ - 会員登録</title>
  <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>
  <header>
    <div class="logo">COACHTECH</div>
  </header>
  <main>
    <h1>会員登録</h1>
    @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    <form action="/register" method="post">
      @csrf
      <div class="form-group">
        <label for="email">メールアドレス</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
      </div>
      <div class="form-group">
        <label for="password">パスワード</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="register-button">登録する</button>
    </form>
    <p class="login-link"><a href="/login">ログインはこちら</a></p>
  </main>
</body>

</html>