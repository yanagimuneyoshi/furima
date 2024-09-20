<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ asset('css/admin.register.css') }}">
  <title>管理者登録</title>
</head>

<body>
  <h1>管理者登録</h1>

  <form method="POST" action="{{ route('admin.register') }}">
    @csrf
    <div>
      <label for="name">名前</label>
      <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
      @if($errors->has('name'))
      <div class="alert alert-danger">{{ $errors->first('name') }}</div>
      @endif
    </div>

    <div>
      <label for="email">メールアドレス</label>
      <input id="email" type="email" name="email" value="{{ old('email') }}" required>
      @if($errors->has('email'))
      <div class="alert alert-danger">{{ $errors->first('email') }}</div>
      @endif
    </div>

    <div>
      <label for="password">パスワード</label>
      <input id="password" type="password" name="password" required>
      @if($errors->has('password'))
      <div class="alert alert-danger">{{ $errors->first('password') }}</div>
      @endif
    </div>

    <div>
      <label for="password-confirm">パスワード確認</label>
      <input id="password-confirm" type="password" name="password_confirmation" required>
      @if($errors->has('password_confirmation'))
      <div class="alert alert-danger">{{ $errors->first('password_confirmation') }}</div>
      @endif
    </div>

    <div>
      <button type="submit">登録</button>
    </div>
  </form>
</body>

</html>