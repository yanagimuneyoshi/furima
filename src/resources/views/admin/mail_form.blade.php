<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>メール送信</title>
</head>

<body>
  <h1>メール送信</h1>
  @if(session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
  @endif
  <form action="{{ route('admin.sendMail') }}" method="POST">
    @csrf
    <div>
      <label for="email">送信先メールアドレス</label>
      <input type="email" name="email" value="{{ request('email') }}" required>
    </div>
    <div>
      <label for="title">件名</label>
      <input type="text" name="title" required>
    </div>
    <div>
      <label for="body">メッセージ本文</label>
      <textarea name="body" required></textarea>
    </div>
    <div>
      <button type="submit">送信</button>
    </div>
  </form>
</body>

</html>