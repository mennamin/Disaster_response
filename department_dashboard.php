<?php
require 'includes/auth.php';
require_role(['department']);


$mine = sort_incidents(incidents_with_status('assigned'));

$pageTitle = 'Department Dashboard';
require 'includes/header.php';
?>
<div class="stats">
  <div class="stat"><div class="num"><?= count($mine) ?></div><div class="label">Assigned to us</div></div>
  <div class="stat"><div class="num"><?= count_status($_SESSION['incidents'], ['resolved', 'closed']) ?></div><div class="label">Resolved</div></div>
  <div class="stat"><div class="num"><?= $_SESSION['backup_pending'] ?></div><div class="label">Backup requests pending</div></div>
</div>

<div class="actions">
  <a class="btn" href="backup-requests.php">Request backup</a>
  <a class="btn secondary" href="reports.html">Report details</a>
</div>

<h2>Our active incidents</h2>
<table>
  <tr><th>#</th><th>Type</th><th>Location</th><th>Severity</th><th>Update</th><th>Time</th><th></th></tr>
  <?php foreach ($mine as $r): ?>
    <tr>
      <td><?= $r['id'] ?></td>
      <td><?= e($r['type']) ?></td>
      <td><?= e($r['location']) ?></td>
      <td><span class="badge <?= $r['severity'] ?>"><?= $r['severity'] ?></span></td>
      <td><?= e($r['note']) ?></td>
      <td><?= $r['time'] ?></td>
      <td><a class="btn small" href="best-route.php">Best route</a></td>
    </tr>
  <?php endforeach; ?>
  <?php if (count($mine) == 0): ?>
    <tr><td colspan="7">Nothing assigned right now.</td></tr>
  <?php endif; ?>
</table>
<?php require 'includes/footer.php'; ?>