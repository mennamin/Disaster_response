<?php
require 'includes/auth.php';
require_role(['reporter']);


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
  <a class="btn" href="report_incident.php">+ Report new incident</a>
</div>
<?php require 'includes/footer.php'; ?>