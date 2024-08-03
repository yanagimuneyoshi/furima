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
    <h1>商品の出品</h1>
    <form id="item-form" action="{{ route('items.store') }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label for="item_image">商品画像</label>
        <input type="file" id="item_image" name="item_image" accept="image/*" required>
        <div id="image-preview" class="image-preview"></div>
      </div>
      <div class="form-group">
        <label for="category">カテゴリー</label>
        <input type="text" id="category" name="category_input" placeholder="カテゴリーを入力してEnterを押してください">
        <div id="category-container" class="categories"></div>
        <input type="hidden" name="categories" id="categories">
      </div>
      <div class="form-group">
        <label for="condition">商品の状態</label>
        <select id="condition" name="condition" required>
          <option value="新品">新品</option>
          <option value="中古">中古</option>
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

    document.getElementById('item-form').addEventListener('submit', function(e) {
      updateCategoriesInput();
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

    document.getElementById('item_image').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
          const img = document.createElement('img');
          img.src = event.target.result;
          img.className = 'image-preview';
          const previewContainer = document.getElementById('image-preview');
          previewContainer.innerHTML = '';
          previewContainer.appendChild(img);
        }
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>

</html>