<?php

?>
<table>
  <tr>
    <th>#</th><th>Type</th><th>Location</th><th>Reporter</th>
    <th>Severity</th><th>Status</th><th>Description</th><th></th>
  </tr>
  <?php foreach ($all as $r): ?>
    <?php
      $form_id = 'f' . $r['id'];

      
      $injured = 0;
      $dead = 0;
      $about = '';
      if (isset($r['injured'])) { $injured = $r['injured']; }
      if (isset($r['dead'])) { $dead = $r['dead']; }
      if (isset($r['description'])) { $about = $r['description']; }
      if (strlen($about) > 80) { $about = substr($about, 0, 80) . '...'; }
    ?>
    <tr>
      <td><?= $r['id'] ?></td>
      <td>
        <?= e($r['type']) ?>
        <br><small style="color:#6b7699">Injured: <?= $injured ?>, dead: <?= $dead ?><?php if ($about != '') { echo ' - ' . e($about); } ?></small>
      </td>
      <td><?= e($r['location']) ?></td>
      <td><?= e(user_name($r['reporter_id'])) ?></td>
      <td>
        <select name="severity" form="<?= $form_id ?>">
          <?php foreach ($severities as $s): ?>
            <option value="<?= $s ?>" <?php if ($r['severity'] == $s) { echo 'selected'; } ?>><?= ucfirst($s) ?></option>
          <?php endforeach; ?>
        </select>
      </td>
      <td>
        <select name="status" form="<?= $form_id ?>">
          <?php foreach ($statuses as $s): ?>
            <option value="<?= $s ?>" <?php if ($r['status'] == $s) { echo 'selected'; } ?>><?= ucfirst($s) ?></option>
          <?php endforeach; ?>
        </select>
      </td>
      <td><input class="note" name="note" form="<?= $form_id ?>" maxlength="200" placeholder="Status description" value="<?= e($r['note']) ?>"></td>
      <td>
        <form id="<?= $form_id ?>" method="post">
          <input type="hidden" name="id" value="<?= $r['id'] ?>">
          <button class="btn small" type="submit">Save</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>