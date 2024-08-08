<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>コメントページ</title>
  <link rel="stylesheet" href="{{ asset('css/comments.css') }}">
</head>

<body>
  <header>
    <div class="logo">COACHTECH</div>
    <input type="text" placeholder="なにをお探しですか？" class="search-bar">
    <div class="auth-buttons">
      @if (Auth::check())
      <!-- ログインしているユーザー向けのコンテンツ -->
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout">ログアウト</button>
      </form>
      <a href="{{ route('mypage') }}" class="mypage">マイページ</a>
      @else
      <!-- ログインしていないユーザー向けのコンテンツ -->
      <a href="{{ route('login') }}" class="login">ログイン</a>
      <a href="{{ route('register') }}" class="register">会員登録</a>
      @endif
      <a href="{{ route('sell') }}" class="sell">出品</a>
    </div>
  </header>
  <main>
    <div class="comment-container">
      <div class="item-image">
        <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->title }}">
      </div>
      <div class="comment-details">
        <h1>{{ $item->title }}</h1>
        <h2>コメント</h2>
        <div class="comments-list">
          @foreach($comments as $comment)
          <div class="comment">
            <p class="comment-user">{{ $comment->user->name }}</p>
            <p class="comment-body">{{ $comment->content }}</p>
          </div>
          @endforeach
        </div>
        <form method="POST" action="{{ route('comments.store', ['item_id' => $item->id]) }}">
          @csrf
          <div class="form-group">
            <label for="content">商品へのコメント</label>
            <textarea id="content" name="content" rows="4" required></textarea>
          </div>
          <button type="submit" class="comment-button">コメントを送信する</button>
        </form>
      </div>
    </div>
  </main>
</body>

</html>