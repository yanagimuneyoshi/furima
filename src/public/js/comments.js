document.addEventListener('DOMContentLoaded', function() {
    const loginButton = document.getElementById('login-button');
    if (loginButton) {
        loginButton.addEventListener('click', function() {
            window.location.href = loginUrl;
        });
    }
});
