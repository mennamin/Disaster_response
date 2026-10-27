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
  <style>
.dash-bar {
  display: flex;
  flex-wrap: wrap;
  align-items: stretch;
  gap: 10px;
  margin-bottom: 18px;
  padding: 8px;
  background: var(--inner-bg);
  border-radius: 999px;
}
.dash-link {
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 8px 22px;
  border-radius: 999px;
  color: var(--text);
  text-decoration: none;
  line-height: 1.15;
}
.dash-link small { color: var(--muted); font-style: italic; }
.dash-link:hover { background: #d3e0fa; }
.dash-link.current { background: var(--accent); color: #fff; }
.dash-link.current small { color: #dfe6ff; }
.dash-link.reset { margin-left: auto; color: var(--muted); font-style: italic; }
  </style>
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
        </div>
      </div>
    </div>
  </div>

  <?php if (DEMO_MODE): ?>
    <div class="dash-bar">
      <?php foreach ($_SESSION['users'] as $u): ?>
        <a class="dash-link<?php if ($u['id'] == $account['id']) { echo ' current'; } ?>" href="<?= dashboard_for($u['role']) ?>?as=<?= $u['id'] ?>">
          <?= e(role_label($u['role'])) ?> dashboard
          <small><?= e($u['name']) ?></small>
        </a>
      <?php endforeach; ?>
      <a class="dash-link reset" href="?reset=1">Reset data</a>
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