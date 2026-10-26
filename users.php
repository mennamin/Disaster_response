<?php

require 'includes/auth.php';
require_role(['admin']);

$me = current_user();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'add') {
        $error = add_user($_POST['name'], $_POST['email'], $_POST['role'], $_POST['started']);
        $done = 'User added.';
    } elseif ($action == 'update') {
        $error = update_user($_POST['id'], $_POST['name'], $_POST['email'], $_POST['role'], $_POST['started'], $me['id']);
        $done = 'Profile updated.';
    } else {
        $error = delete_user($_POST['id'], $me['id']);
        $done = 'User deleted.';
    }

    if ($error == '') {
        set_message($done, 'ok');
    } else {
        set_message($error, 'error');
    }
    header('Location: users.php');
    exit;
}

$message = get_message();


$editing = false;
$form_name = '';
$form_email = '';
$form_role = 'reporter';
$form_started = '';
if (isset($_GET['edit'])) {
    $editing = get_user((int)$_GET['edit']);
    if ($editing) {
        $form_name = $editing['name'];
        $form_email = $editing['email'];
        $form_role = $editing['role'];
        if (isset($editing['started'])) {
            $form_started = $editing['started'];
        }
    }
}

$pageTitle = 'Manage Users & Profiles';
require 'includes/header.php';
?>
<div class="actions">
  <a class="btn secondary" href="admin_dashboard.php">&larr; Back to dashboard</a>
</div>

<?php if ($message): ?>
  <div class="flash <?= $message['type'] ?>"><?= e($message['text']) ?></div>
<?php endif; ?>

<?php if ($editing): ?>
  <h2>Edit profile</h2>
<?php else: ?>
  <h2>Add a user</h2>
<?php endif; ?>

<form method="post">
  <?php if ($editing): ?>
    <input type="hidden" name="action" value="update">
    <input type="hidden" name="id" value="<?= $editing['id'] ?>">
  <?php else: ?>
    <input type="hidden" name="action" value="add">
  <?php endif; ?>

  <div class="form-grid">
    <div><label>Full name</label><input name="name" required value="<?= e($form_name) ?>"></div>
    <div><label>Email</label><input type="email" name="email" required value="<?= e($form_email) ?>"></div>
    <div>
      <label>Role</label>
      <select name="role">
        <?php foreach ($roles as $r): ?>
          <option value="<?= $r ?>" <?php if ($form_role == $r) { echo 'selected'; } ?>><?= role_label($r) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div><label>Started on</label><input type="month" name="started" value="<?= e($form_started) ?>"></div>
  </div>

  <?php if ($editing): ?>
    <button class="btn" type="submit">Save changes</button>
    <a class="btn secondary" href="users.php">Cancel</a>
  <?php else: ?>
    <button class="btn" type="submit">Add user</button>
    <a class="btn secondary" href="#">Add profile</a>
  <?php endif; ?>
</form>

<h2 style="margin-top:36px">All users</h2>
<table>
  <tr><th>Name</th><th>Email</th><th>Role</th><th>Started</th><th></th></tr>
  <?php foreach ($_SESSION['users'] as $u): ?>
    <?php if ($u['role'] == 'admin') { continue; } ?>
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
        <a class="btn small secondary" href="users.php?edit=<?= $u['id'] ?>">Edit</a>
        <form class="inline" method="post" onsubmit="return confirm('Delete this user?');">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?= $u['id'] ?>">
          <button class="btn small danger" type="submit">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
<?php require 'includes/footer.php'; ?>