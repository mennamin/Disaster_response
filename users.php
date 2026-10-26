<?php

require 'includes/auth.php';
require_role(['admin']);

$me = current_user();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = delete_user($_POST['id'], $me['id']);

    if ($error == '') {
        set_message('User deleted.', 'ok');
    } else {
        set_message($error, 'error');
    }
    header('Location: users.php');
    exit;
}

$message = get_message();

$pageTitle = 'Manage Users & Profiles';
require 'includes/header.php';
?>
<div class="actions">
  <a class="btn secondary" href="admin_dashboard.php">&larr; Back to dashboard</a>
</div>

<?php if ($message): ?>
  <div class="flash <?= $message['type'] ?>"><?= e($message['text']) ?></div>
<?php endif; ?>

<div class="actions">
  <a class="btn" href="#">Add user</a>
</div>

<h2 style="margin-top:36px">All users</h2>
<table>
  <tr><th>Name</th><th>Email</th><th>Role</th><th>Started</th><th></th></tr>
  <?php foreach ($_SESSION['users'] as $u): ?>
    <?php if ($u['role'] == 'admin') { continue; }  ?>
    <?php
      $started = '';
      if (isset($u['started'])) {
          $started = $u['started'];
      }
    ?>
    <tr>
      <td><?= e($u['name']) ?></td>
      <td><?= e($u['email']) ?></td>
      <td><span class="badge"><?= role_label($u['role']) ?></span></td>
      <td><?= started_text($started) ?></td>
      <td>
        <a class="btn small secondary" href="#">Open profile</a>
        <a class="btn small secondary" href="#">Edit</a>
        <form class="inline" method="post" onsubmit="return confirm('Delete this user?');">
          <input type="hidden" name="id" value="<?= $u['id'] ?>">
          <button class="btn small danger" type="submit">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
<?php require 'includes/footer.php'; ?>