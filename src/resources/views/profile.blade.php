<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COACHTECHフリマ - プロフィール設定</title>
  <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
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
    <h1>プロフィール設定</h1>
    <form class=profile action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="form-group profile-pic-section">
        <div class="profile-pic"></div>
        <label for="profile_pic" class="profile-pic-label">画像を選択する</label>
        <input type="file" id="profile_pic" name="profile_pic">
      </div>
      <div class="form-group">
        <label for="name">ユーザー名</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
      </div>
      <div class="form-group">
        <label for="postal_code">郵便番号</label>
        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" required>
      </div>
      <div class="form-group">
        <label for="address">住所</label>
        <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" required>
      </div>
      <div class="form-group">
        <label for="building">建物名</label>
        <input type="text" id="building" name="building" value="{{ old('building', $user->building) }}">
      </div>
      <button type="submit" class="update-button">更新する</button>
    </form>
  </main>
</body>

</html>