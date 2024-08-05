<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>商品詳細</title>
  <link rel="stylesheet" href="{{ asset('css/item_show.css') }}">
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
    <div class="item-container">
      <div class="item-image">
        <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->title }}">
      </div>
      <div class="item-details">
        <h1>{{ $item->title }}</h1>
        <p class="brand">ブランド名</p>
        <p class="price">¥{{ number_format($item->price) }}(値段)</p>
        <div class="actions">
          <span class="favorite-count">★ 3</span>
          <span class="comment-count">💬 14</span>
          <form action="{{ route('buy', $item->id) }}" method="POST">
            @csrf
            <button type="submit" class="buy-button">購入する</button>
          </form>
        </div>
        <div class="description">
          <h2>商品説明</h2>
          <p>カラー：グレー</p>
          <p>{{ $item->condition }}</p>
          <p>{{ $item->description }}</p>
        </div>
        <div class="additional-info">
          <h2>商品の情報</h2>
          <p>カテゴリー：
            @foreach($item->categories as $category)
            {{ $category->name }}
            @if(!$loop->last)
            ,
            @endif
            @endforeach
          </p>
          <p>商品の状態：{{ $item->condition }}</p>
        </div>
      </div>
    </div>
  </main>
</body>

</html>