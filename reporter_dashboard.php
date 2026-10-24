<?php
require 'includes/auth.php';
require_role(['reporter']);

// only this reporter's own incidents
$me = current_user();
$mine = incidents_of($me['id']);

$pageTitle = 'Reporter Dashboard';
require 'includes/header.php';
?>
<div class="stats">
  <div class="stat"><div class="num"><?= count($mine) ?></div><div class="label">Reports submitted</div></div>
  <div class="stat"><div class="num"><?= count_status($mine, ['open']) ?></div><div class="label">Waiting for assignment</div></div>
  <div class="stat"><div class="num"><?= count_status($mine, ['assigned']) ?></div><div class="label">Being handled</div></div>
  <div class="stat"><div class="num"><?= count_status($mine, ['resolved', 'closed']) ?></div><div class="label">Resolved / closed</div></div>
</div>

<div class="actions">
  <a class="btn" href="#">+ Report new incident</a>
</div>

<h2>My recent reports</h2>
<table>
  <tr><th>#</th><th>Type</th><th>Location</th><th>Severity</th><th>Status</th><th>Update</th><th>Time</th></tr>
  <?php foreach ($mine as $r): ?>
    <tr>
      <td><?= $r['id'] ?></td>
      <td><?= e($r['type']) ?></td>
      <td><?= e($r['location']) ?></td>
      <td><span class="badge <?= $r['severity'] ?>"><?= $r['severity'] ?></span></td>
      <td><?= ucfirst($r['status']) ?></td>
      <td><?= e($r['note']) ?></td>
      <td><?= $r['time'] ?></td>
    </tr>
  <?php endforeach; ?>
  <?php if (count($mine) == 0): ?>
    <tr><td colspan="7">You have not reported any incidents yet.</td></tr>
  <?php endif; ?>
</table>
<?php require 'includes/footer.php'; ?>