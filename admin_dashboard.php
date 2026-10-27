<?php
require 'includes/auth.php';
require_role(['admin']);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = update_incident($_POST['id'], $_POST['severity'], $_POST['status'], $_POST['note']);
    if ($error == '') {
        set_message('Incident #' . (int)$_POST['id'] . ' updated.', 'ok');
    } else {
        set_message($error, 'error');
    }
    header('Location: admin_dashboard.php');
    exit;
}

$message = get_message();
$all = sort_incidents($_SESSION['incidents']);

$pageTitle = 'Admin Dashboard';
require 'includes/header.php';
?>
<div class="stats">
  <div class="stat"><div class="num"><?= count($_SESSION['users']) ?></div><div class="label">Users</div></div>
  <div class="stat"><div class="num"><?= count($all) ?></div><div class="label">Total incidents (history)</div></div>
  <div class="stat"><div class="num"><?= count_status($all, ['open', 'assigned']) ?></div><div class="label">Active incidents</div></div>
  <div class="stat"><div class="num"><?= count_critical() ?></div><div class="label">Critical right now</div></div>
</div>

<div class="actions">
  <a class="btn" href="users.php">Manage users &amp; profiles</a>
  <a class="btn secondary" href="report_incident.php">+ Report new incident</a>
  <a class="btn secondary" href="#">Backup requests</a>
  <a class="btn secondary" href="resources.php">Department resources</a>
</div>

<?php if ($message): ?>
  <div class="flash <?= $message['type'] ?>"><?= e($message['text']) ?></div>
<?php endif; ?>

<h2>Reports &amp; priority</h2>

<?php require 'includes/incident_table.php'; ?>
<?php require 'includes/footer.php'; ?>