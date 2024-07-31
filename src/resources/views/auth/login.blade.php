<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COACHTECHフリマ - ログイン</title>
  <link rel="stylesheet" href="css/login.css">
</head>

<body>
  <header>
    <div class="logo">COACHTECH</div>
  </header>
  <main>
    <h1>ログイン</h1>
    <form action="/login" method="post">
      <div class="form-group">
        <label for="email">メールアドレス</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">パスワード</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="login-button">ログインする</button>
    </form>
    <p class="register-link"><a href="/register">会員登録はこちら</a></p>
  </main>
</body>

</html>