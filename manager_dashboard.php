<?php
require 'includes/auth.php';
require_role(['incident_manager']);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = update_incident($_POST['id'], $_POST['severity'], $_POST['status'], $_POST['note']);
    if ($error == '') {
        set_message('Incident #' . (int)$_POST['id'] . ' updated.', 'ok');
    } else {
        set_message($error, 'error');
    }
    header('Location: manager_dashboard.php');
    exit;
}

$message = get_message();
$all = sort_incidents($_SESSION['incidents']);

$pageTitle = 'Incident Management Dashboard';
require 'includes/header.php';
?>
<?php if ($message): ?>
  <div class="flash <?= $message['type'] ?>"><?= e($message['text']) ?></div>
<?php endif; ?>

<div class="stats">
  <div class="stat"><div class="num"><?= count_status($all, ['open', 'assigned']) ?></div><div class="label">Active incidents</div></div>
  <div class="stat"><div class="num"><?= count_critical() ?></div><div class="label">Critical</div></div>
  <div class="stat"><div class="num"><?= count_status($all, ['open']) ?></div><div class="label">Not yet assigned</div></div>
  <div class="stat"><div class="num"><?= $_SESSION['backup_pending'] ?></div><div class="label">Backup requests pending</div></div>
</div>

<div class="actions">
  <a class="btn" href="#">Backup requests</a>
  <a class="btn secondary" href="#">Department resources</a>
</div>

<h2>Reports &amp; priority</h2>

<?php require 'includes/incident_table.php'; ?>
<?php require 'includes/footer.php'; ?>