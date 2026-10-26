<?php

require 'includes/auth.php';
require_role(['reporter', 'admin']);

$me = current_user();
$back = dashboard_for($me['role']);


$form_type = '';
$form_location = '';
$form_severity = 'medium';
$form_injured = 0;
$form_dead = 0;
$form_description = '';
$message = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_type = $_POST['type'];
    $form_location = $_POST['location'];
    $form_severity = $_POST['severity'];
    $form_injured = $_POST['injured'];
    $form_dead = $_POST['dead'];
    
    if (isset($_POST['injured_unknown'])) { $form_injured = 'unknown'; }
    if (isset($_POST['dead_unknown'])) { $form_dead = 'unknown'; }
    $form_description = $_POST['description'];

    $error = add_incident($form_type, $form_location, $form_severity, $form_injured, $form_dead, $form_description, $me['id']);

    if ($error == '') {
        set_message('Report sent.', 'ok');
        header('Location: report_incident.php');
        exit;
    }
    
    $message = ['text' => $error, 'type' => 'error'];
} else {
    $message = get_message();
}

$pageTitle = 'Report a New Incident';
require 'includes/header.php';
?>
<div class="actions">
  <a class="btn secondary" href="<?= $back ?>">&larr; Back to dashboard</a>
</div>

<?php if ($message): ?>
  <div class="flash <?= $message['type'] ?>"><?= e($message['text']) ?></div>
<?php endif; ?>

<form method="post">
  <div class="form-grid">
    <div>
      <label>Type of disaster</label>
      <select name="type" required>
        <option value="" disabled <?php if ($form_type == '') { echo 'selected'; } ?>>Choose the type</option>
        <?php foreach ($incident_types as $t): ?>
          <option value="<?= $t ?>" <?php if ($form_type == $t) { echo 'selected'; } ?>><?= $t ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label>Location</label>
      <input name="location" required placeholder="Street and area" value="<?= e($form_location) ?>">
    </div>
    <div>
      <label>How critical is the situation?</label>
      <select name="severity">
        <?php foreach ($severities as $s): ?>
          <option value="<?= $s ?>" <?php if ($form_severity == $s) { echo 'selected'; } ?>><?= ucfirst($s) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label>People injured</label>
      <input type="number" name="injured" min="0" value="<?php if ($form_injured != 'unknown') { echo e($form_injured); } ?>">
      <label style="margin-top:6px"><input type="checkbox" name="injured_unknown" <?php if ($form_injured == 'unknown') { echo 'checked'; } ?>> Unknown</label>
    </div>
    <div>
      <label>People dead</label>
      <input type="number" name="dead" min="0" value="<?php if ($form_dead != 'unknown') { echo e($form_dead); } ?>">
      <label style="margin-top:6px"><input type="checkbox" name="dead_unknown" <?php if ($form_dead == 'unknown') { echo 'checked'; } ?>> Unknown</label>
    </div>
  </div>

  <div style="margin-bottom:18px">
    <label>Description of the disaster</label>
    <textarea name="description" rows="5" required placeholder="Write what the caller told you, for example: two people were injured when two cars collided."><?= e($form_description) ?></textarea>
  </div>

  <button class="btn" type="submit">Send report</button>
</form>
<?php require 'includes/footer.php'; ?>