<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COACHTECHフリマ - マイページ</title>
  <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
</head>

<body>
  <header>
    <div class="logo">COACHTECH</div>
    <input type="text" placeholder="なにをお探しですか？" class="search-bar">
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
        <img src="{{ asset('storage/' . Auth::user()->profile_pic) }}" alt="Profile Picture">
        @else
        <img src="{{ asset('images/default-profile.png') }}" alt="">
        @endif
      </div>
      <div class="profile-info">
        <h2>{{ Auth::user()->name }}</h2>
        <a href="{{ route('profile.edit') }}" class="edit-profile">プロフィールを編集</a>
      </div>
    </div>
    <div class="tabs">
      <button class="tab active">出品した商品</button>
      <button class="tab">購入した商品</button>
    </div>
    <div class="items">
      <div class="item"></div>
      <div class="item"></div>
      <div class="item"></div>
      <div class="item"></div>
      <div class="item"></div>
      <div class="item"></div>
      <div class="item"></div>
      <div class="item"></div>
    </div>
  </main>
</body>

</html>