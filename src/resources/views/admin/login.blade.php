<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ asset('css/admin.login.blade.css') }}">

  <title>管理者ログイン</title>
</head>

<body>
  <h1>管理者ログイン</h1>

  @if(session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
  @endif
  

  @if(session('error'))
  <div class="alert alert-danger">
    {{ session('error') }}
  </div>
  @endif

  <form method="POST" action="{{ route('admin.login') }}">
    @csrf
    <div>
      <label for="email">メールアドレス</label>
      <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
    </div>

    <div>
      <label for="password">パスワード</label>
      <input id="password" type="password" name="password" required>
    </div>

    <div>
      <button type="submit">ログイン</button>
    </div>
  </form>
</body>

</html>