<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>管理画面</title>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>
  <h1>管理画面</h1>

  <h2>ユーザー一覧</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>名前</th>
        <th>メールアドレス</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $user)
      <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
          <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">削除</button>
          </form>
          <form action="{{ route('admin.mailForm') }}" method="GET" style="display:inline;">
            @csrf
            <input type="hidden" name="email" value="{{ $user->email }}">
            <button type="submit">お客様にメール</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <h2>コメント一覧</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>内容</th>
        <th>投稿者</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      @foreach($comments as $comment)
      <tr>
        <td>{{ $comment->id }}</td>
        <td>{{ $comment->content }}</td>
        <td>{{ $comment->user->name ?? '名前なし'  }}</td>
        <td>
          <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">削除</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>