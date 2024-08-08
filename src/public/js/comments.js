document.addEventListener('DOMContentLoaded', function() {
  const favoriteButton = document.getElementById('favorite-button');
  const loginButton = document.getElementById('login-button');

  if (favoriteButton) {
    favoriteButton.addEventListener('click', function() {
      const itemId = this.getAttribute('data-item-id');
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      fetch(`/favorites/toggle/${itemId}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        }
      }).then(response => response.json())
        .then(data => {
          if (data.success) {
            this.classList.toggle('favorited');
            this.textContent = data.is_favorited ? '★' : '☆';
            document.getElementById('favorite-count').textContent = data.favorites_count;
          } else {
            window.location.href = loginUrl;
          }
        })
        .catch(error => {
          console.error('Error:', error);
        });
    });
  }

  if (loginButton) {
    loginButton.addEventListener('click', function() {
      window.location.href = loginUrl;
    });
  }
});
