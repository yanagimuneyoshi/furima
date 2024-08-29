<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COACHTECHフリマ - マイページ</title>
  <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
  <script>
    function switchTab(tabName) {
      // タブの表示を切り替える
      var tabs = document.querySelectorAll('.tab-content');
      tabs.forEach(function(tab) {
        tab.style.display = 'none';
      });

      document.getElementById(tabName).style.display = 'block';

      // タブのアクティブ状態を切り替える
      var buttons = document.querySelectorAll('.tab');
      buttons.forEach(function(button) {
        button.classList.remove('active');
      });

      document.getElementById(tabName + '-tab').classList.add('active');
    }

    window.onload = function() {
      // 初期表示: 出品した商品タブ
      switchTab('sold-items');
    };
  </script>
</head>

<body>
  <header>
    <a href="/" class="logo">
      <img src="{{ asset('images/logo.svg') }}" />
    </a>
    <form action="{{ route('mypage') }}" method="GET" class="search-form">
      <input type="text" name="query" placeholder="なにをお探しですか？" class="search-bar" value="{{ request('query') }}">
    </form>

    <div class="auth-buttons">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout">ログアウト</button>
      </form>
      <a href="{{ route('mypage') }}" class="mypage">マイページ</a>
      <a href="{{ route('sell') }}" class="sell">出品</a>
    </div>
  </header>
  <main>
    <div class="profile-section">
      <div class="profile-pic">
        @if (Auth::user()->profile_pic)
        <img src="{{ asset('storage/' . Auth::user()->profile_pic) }}">
        @else
        <img src="{{ asset('images/default-profile.png') }}">
        @endif
      </div>
      <div class="profile-info">
        <h2>{{ Auth::user()->name }}</h2>
        <a href="{{ route('profile.edit') }}" class="edit-profile">プロフィールを編集</a>
      </div>
    </div>

    <div class="tabs">
      <button class="tab active" id="sold-items-tab" onclick="switchTab('sold-items')">出品した商品</button>
      <button class="tab" id="purchased-items-tab" onclick="switchTab('purchased-items')">購入した商品</button>
    </div>

    <!-- 出品した商品 -->
    <div id="sold-items" class="tab-content">
      <div class="items">
        @forelse($soldItems as $item)
        <div class="item">
          <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->title }}">
          <div class="item-details">
            <p>商品名: {{ $item->title }}</p>
            <p>価格: ¥{{ number_format($item->price) }}</p>
            <p>状態: {{ $item->condition }}</p>
          </div>
        </div>
        @empty
        <p>出品した商品はありません。</p>
        @endforelse
      </div>
    </div>

    <!-- 購入した商品 -->
    <div id="purchased-items" class="tab-content" style="display: none;">
      <div class="items">
        @forelse($purchasedItems as $item)
        <div class="item">
          <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->title }}">
          <div class="item-details">
            <p>商品名: {{ $item->title }}</p>
            <p>価格: ¥{{ number_format($item->price) }}</p>
            <p>状態: {{ $item->condition }}</p>
          </div>
        </div>
        @empty
        <p>購入した商品はありません。</p>
        @endforelse
      </div>
    </div>
  </main>
</body>

</html>