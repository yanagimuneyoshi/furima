<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COACHTECHフリマ</title>
  <link rel="stylesheet" href="{{ asset('css/item.css') }}">
  <script>
    // ページ読み込み時にタブを設定する関数
    function initializeTabs() {
      var activeTab = '{{ request("tab", "recommendations") }}';
      switchTab(activeTab, false);
    }

    // タブの切り替えを処理する関数
    function switchTab(tabName, resetSearch = false) {
      var tabs = document.querySelectorAll('.tab-content');
      tabs.forEach(function(tab) {
        tab.style.display = 'none';
      });

      var activeContent = document.getElementById(tabName + '-content');
      activeContent.style.display = 'block';

      // タブ情報を検索フォームに設定
      document.getElementById('tab').value = tabName;

      // タブの色を切り替え
      var buttons = document.querySelectorAll('.tab');
      buttons.forEach(function(button) {
        button.classList.remove('active');
      });
      document.getElementById(tabName + '-tab').classList.add('active');

      // 検索をリセットする場合
      if (resetSearch) {
        document.querySelector('.search-bar').value = '';
        document.querySelector('.search-form').submit();
      }

      // タブ切り替え時はエラーメッセージを非表示にする
      document.querySelectorAll('.no-results').forEach(function(message) {
        message.style.display = 'none';
      });
    }

    // マイリストタブをクリックしたときの処理
    function handleMyListClick() {
      @if(Auth::check())
      // ログインしている場合、マイリストを表示
      switchTab('mylist', true); // trueを渡して検索をリセット
      @else
      // ログインしていない場合、ログイン画面にリダイレクト
      window.location.href = "{{ route('login') }}";
      @endif
    }

    window.onload = function() {
      initializeTabs();
      controlNoResultsMessages(); // ページロード時にメッセージの表示を制御
    };

    // 検索結果に応じてメッセージの表示を制御する関数
    function controlNoResultsMessages() {
      var recommendationsTab = document.getElementById('recommendations-content');
      var myListTab = document.getElementById('mylist-content');

      if (recommendationsTab) {
        var recommendationsMessage = recommendationsTab.querySelector('.no-results');
        if (recommendationsMessage) {
          var hasItems = recommendationsTab.querySelectorAll('.item').length > 0;
          recommendationsMessage.style.display = hasItems ? 'none' : 'block';
        }
      }

      if (myListTab) {
        var myListMessage = myListTab.querySelector('.no-results');
        if (myListMessage) {
          var hasItems = myListTab.querySelectorAll('.item').length > 0;
          myListMessage.style.display = hasItems ? 'none' : 'block';
        }
      }
    }
  </script>
  <style>
    .tab.active {
      background-color: #ccc;
    }

    .tab-content {
      display: none;
      /* 初期状態は非表示 */
    }

    .no-results {
      display: none;
      /* 初期状態は非表示 */
    }
  </style>
</head>

<body>
  <header>
    <div class="logo">COACHTECH</div>
    <form action="{{ route('item.search') }}" method="GET" class="search-form">
      <input type="text" name="query" placeholder="なにをお探しですか？" class="search-bar">
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
      <button class="tab {{ request('tab', 'recommendations') === 'recommendations' ? 'active' : '' }}" id="recommendations-tab" onclick="switchTab('recommendations', true)">おすすめ</button>
      <button class="tab {{ request('tab') === 'mylist' ? 'active' : '' }}" id="mylist-tab" onclick="handleMyListClick()">マイリスト</button>
    </div>

    <div id="recommendations-content" class="tab-content">
      <div class="items">
        @if($items->isEmpty() && request()->has('query'))
        <p class="no-results" style="display: block;">一致する商品が見つかりませんでした。</p>
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
    <div id="mylist-content" class="tab-content">
      <div class="items">
        @if(isset($favorites) && $favorites->isEmpty() && request()->has('query'))
        <p class="no-results" style="display: block;">一致するお気に入り商品が見つかりませんでした。</p>
        @else
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
        @endif
      </div>
    </div>
    @endif
  </main>
</body>

</html>