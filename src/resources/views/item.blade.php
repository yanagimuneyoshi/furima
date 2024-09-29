<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COACHTECHフリマ</title>
  <link rel="stylesheet" href="{{ asset('css/item.css') }}">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    function switchTab(tabName) {
      var newUrl = window.location.origin + '/?tab=' + tabName;
      window.location.href = newUrl;
    }

    function handleMyListClick() {
  $.ajax({
    url: "{{ route('mylist.check') }}",
    type: 'GET',
    success: function(response) {
      if (response.authenticated) {
        switchTab('mylist');
      } else {
        window.location.href = "{{ route('login') }}";
      }
    },
    error: function(xhr) {
      if (xhr.status === 401) {
        window.location.href = "{{ route('login') }}";
      }
    }
  });
}

  </script>
</head>

<body>
  <header>
    <a href="/" class="logo">
      <img src="{{ asset('images/logo.svg') }}" alt="COACHTECHロゴ" />
    </a>
    <form action="{{ route('item.search') }}" method="GET" class="search-form" id="search-form">
      <input type="text" name="query" placeholder="なにをお探しですか？" class="search-bar" id="search-bar">
      <input type="hidden" name="tab" id="tab" value="{{ request('tab', 'recommendations') }}">
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
    <div class="tabs">
      <button class="tab {{ request('tab', 'recommendations') === 'recommendations' ? 'active' : '' }}" id="recommendations-tab" onclick="switchTab('recommendations')">おすすめ</button>
      <button class="tab {{ request('tab') === 'mylist' ? 'active' : '' }}" id="mylist-tab" onclick="handleMyListClick()">マイリスト</button>
    </div>
    <div id="recommendations-content" class="tab-content {{ request('tab', 'recommendations') === 'recommendations' ? 'active' : '' }}">
      <div class="items">
        @if($items->isEmpty() && request()->has('query'))
        <p class="no-results">一致する商品が見つかりませんでした。</p>
        @else
        @foreach($items as $item)
        <div class="item">
          <a href="{{ route('item.show', $item->id) }}">
            <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->title }}">
            <div class="item-details">
              <p>カテゴリー:
                @foreach($item->categories as $category)
                {{ $category->name }}
                @if(!$loop->last),@endif
                @endforeach
              </p>
              <p>状態: {{ $item->condition }}</p>
              <p>商品名: {{ $item->title }}</p>
              <p>価格: ¥{{ number_format($item->price) }}</p>
            </div>
          </a>
        </div>
        @endforeach
        @endif
      </div>
    </div>
    @if (Auth::check())
    <div id="mylist-content" class="tab-content {{ request('tab') === 'mylist' ? 'active' : '' }}">
      <div class="items">
        @if($favorites->isNotEmpty())
        @foreach($favorites as $item)
        <div class="item">
          <a href="{{ route('item.show', $item->id) }}">
            <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->title }}">
            <div class="item-details">
              <p>カテゴリー:
                @foreach($item->categories as $category)
                {{ $category->name }}
                @if(!$loop->last),@endif
                @endforeach
              </p>
              <p>状態: {{ $item->condition }}</p>
              <p>商品名: {{ $item->title }}</p>
              <p>価格: ¥{{ number_format($item->price) }}</p>
            </div>
          </a>
        </div>
        @endforeach
        @else
        <p class="no-results">一致するお気に入り商品が見つかりませんでした。</p>
        @endif
      </div>
    </div>
    @endif
  </main>
</body>

</html>