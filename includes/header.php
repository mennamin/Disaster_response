<?php

$account = current_user();
$role_name = role_label($account['role']);


$words = explode(' ', $account['name']);
$initials = strtoupper(substr($words[0], 0, 1));
if (count($words) > 1) {
    $initials = $initials . strtoupper(substr($words[count($words) - 1], 0, 1));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?></title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
  <div class="topbar">
    <h1><?= e($pageTitle) ?></h1>

    <div class="topbar-right">
      <div class="account" id="account">
        <button type="button" class="account-btn" onclick="toggleMenu(event)">
          <span class="avatar"><?= e($initials) ?></span>
          <span class="account-text">
            <strong><?= e($account['name']) ?></strong>
            <small><?= e($role_name) ?></small>
          </span>
        </button>
        <div class="account-menu">
          <div class="avatar big"><?= e($initials) ?></div>
          <div class="menu-name"><?= e($account['name']) ?></div>
          <div class="menu-email"><?= e($account['email']) ?></div>
          <div class="menu-role"><?= e($role_name) ?></div>
          <a class="btn small secondary" href="#">View profile</a>
          <?php if (!DEMO_MODE): ?>
            <a class="btn small" href="logout.php">Log out</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <?php if (DEMO_MODE): ?>
    <div class="flash ok demo-bar">
      Demo mode (no login yet). Pretend to be:
      <?php foreach ($_SESSION['users'] as $u): ?>
        <a href="?as=<?= $u['id'] ?>" <?php if ($u['id'] == $account['id']) { echo 'class="current"'; } ?>><?= e($u['name']) ?> (<?= e(role_label($u['role'])) ?>)</a> |
      <?php endforeach; ?>
      <a href="?reset=1">reset demo data</a>
    </div>
  <?php endif; ?>

  <script>
    function toggleMenu(event) {
      event.stopPropagation();
      document.getElementById('account').classList.toggle('open');
    }
    function closeMenu() {
      document.getElementById('account').classList.remove('open');
    }
    document.onclick = closeMenu;
  </script>
  <div class="panel">