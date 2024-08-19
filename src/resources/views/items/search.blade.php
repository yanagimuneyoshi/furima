<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>検索結果</title>
  <link rel="stylesheet" href="{{ asset('css/item.css') }}">
</head>

<body>
  <header>
    <div class="logo">COACHTECH</div>
    <form action="{{ route('item.search') }}" method="GET" class="search-form">
      <input type="text" name="query" placeholder="なにをお探しですか？" class="search-bar">
    </form>
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
    <div class="container">
      <h1>検索結果</h1>
      @if($items->isEmpty())
      <p>該当する商品が見つかりませんでした。</p>
      @else
      <div class="items">
        @foreach($items as $item)
        <div class="item">
          <a href="{{ route('item.show', $item->id) }}">
            <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->title }}">
            <div class="item-details">
              <p>カテゴリー:
                @foreach($item->categories as $category)
                {{ $category->name }}
                @if(!$loop->last)
                ,
                @endif
                @endforeach
              </p>
              <p>状態: {{ $item->condition }}</p>
              <p>商品名: {{ $item->title }}</p>
              <p>価格: ¥{{ number_format($item->price) }}</p>
            </div>
          </a>
        </div>
        @endforeach
      </div>
      @endif
    </div>
  </main>
</body>

</html>