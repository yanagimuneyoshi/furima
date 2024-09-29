<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COACHTECHフリマ - 商品の出品</title>
  <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
</head>

<body>
  <header>
    <a href="/" class="logo">
      <img src="{{ asset('images/logo.svg') }}" alt="COACHTECHロゴ" />
    </a>
    <div class="auth-buttons">
      <form method="POST" action="{{ route('logout') }}" class="page">
        @csrf
        <button type="submit" class="logout">ログアウト</button>
      </form>
      <a href="{{ route('mypage') }}" class="mypage">マイページ</a>
      <a href="{{ route('sell') }}" class="sell">出品</a>
    </div>
  </header>
  <main>
    <h1>商品の出品</h1>
    <form action="{{ route('items.store') }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label for="item_image">商品画像</label>
        <input type="file" id="item_image" name="item_image" required>
        <img id="preview" src="#" alt="プレビュー画像" style="display:none; width: 150px; height: 150px;" />
      </div>
      <div class="form-group">
        <label for="category">カテゴリー</label>
        <input type="text" id="category" name="category" placeholder="カテゴリーを入力してEnterを押してください">
        <div id="category-container" class="categories"></div>
        <input type="hidden" name="categories" id="categories">
      </div>
      <div class="form-group">
        <label for="condition">商品の状態</label>
        <select id="condition" name="condition" required>
          <option value="良好">良好</option>
          <option value="不良">不良</option>
        </select>
      </div>
      <div class="form-group">
        <label for="title">商品名</label>
        <input type="text" id="title" name="title" required>
      </div>
      <div class="form-group">
        <label for="description">商品の説明</label>
        <textarea id="description" name="description" required></textarea>
      </div>
      <div class="form-group">
        <label for="price">販売価格</label>
        <input type="number" id="price" name="price" min="0" required>
      </div>
      <button type="submit" class="submit-button">出品する</button>
    </form>
  </main>
  <script>
    document.getElementById('item_image').addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('preview').setAttribute('src', e.target.result);
          document.getElementById('preview').style.display = 'block';
        }
        reader.readAsDataURL(file);
      }
    });

    document.getElementById('price').addEventListener('input', function(e) {
      if (e.target.value < 0) {
        e.target.value = 0;
      }
    });

    document.getElementById('category').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        const category = e.target.value.trim();
        if (category) {
          addCategoryTag(category);
          e.target.value = '';
        }
      }
    });

    function addCategoryTag(category) {
      const categoryContainer = document.getElementById('category-container');
      const categoryTag = document.createElement('div');
      categoryTag.className = 'category-tag';
      categoryTag.innerText = category;
      const removeBtn = document.createElement('span');
      removeBtn.innerHTML = '&times;';
      removeBtn.onclick = function() {
        categoryContainer.removeChild(categoryTag);
        updateCategoriesInput();
      };
      categoryTag.appendChild(removeBtn);
      categoryContainer.appendChild(categoryTag);
      updateCategoriesInput();
    }

    function updateCategoriesInput() {
      const categories = [];
      document.querySelectorAll('.category-tag').forEach(tag => {
        categories.push(tag.innerText.replace('×', '').trim());
      });
      document.getElementById('categories').value = categories.join(',');
    }
  </script>
</body>

</html>