<nav class="my-navbar">
  <a href="/" class="my-logo">QUIZHERO</a>
  <div class="my-nav-items">
    <?php if (isset($_SESSION['user'])): ?>
      <?php if ($_SESSION['user']['is_admin'] === 1): ?>
        <a href="/manage-questions" class="my-nav-link">Manage Questions</a>
      <?php endif; ?>
      <a href="/logout" class="my-nav-link logout">Logout</a>
    <?php endif; ?>
  </div>
</nav>
