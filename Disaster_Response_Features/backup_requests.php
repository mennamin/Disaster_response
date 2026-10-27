<?php
require __DIR__ . '/config.php';
$error = '';
$success = '';
$canManage = canManageRequests();
$resources = ['Ambulance','Fire engine','Rescue team','Medical supplies','Food and water','Other'];
$priorities = ['Low','Medium','High','Critical'];
$departments = ['Police Department','Hospital Department','Fire Department','Rescue Department','Logistics Department','Civil Defense Department'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $incidentId = filter_input(INPUT_POST, 'incident_id', FILTER_VALIDATE_INT);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
        $resource = $_POST['resource_type'] ?? '';
        $priority = $_POST['priority'] ?? 'Medium';
        $department = trim($_POST['department'] ?? '');
        $reason = trim($_POST['reason'] ?? '');
        if (!$incidentId || !$quantity || $quantity < 1 || $quantity > 1000 ||
            !in_array($resource, $resources, true) || !in_array($priority, $priorities, true) ||
            !in_array($department, $departments, true) || $reason === '') {
            $error = 'Please complete all fields with valid values.';
        } else {
            $check = $pdo->prepare('SELECT id FROM incidents WHERE id = ?');
            $check->execute([$incidentId]);
            $userCheck = $pdo->prepare('SELECT id FROM users WHERE id = ?');
            $userCheck->execute([currentUserId()]);
            if (!$check->fetch()) {
                $error = 'Please choose a valid incident.';
            } elseif (!$userCheck->fetch()) {
                $error = 'The current demo user does not exist. Import sql/schema.sql and try again.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO backup_requests (incident_id, requested_by, department, resource_type, quantity, priority, reason) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([$incidentId, currentUserId(), $department, $resource, $quantity, $priority, $reason]);
                header('Location: backup_requests.php?created=1'); exit;
            }
        }
    } elseif ($action === 'update' && $canManage) {
        $requestId = filter_input(INPUT_POST, 'request_id', FILTER_VALIDATE_INT);
        $status = $_POST['status'] ?? '';
        if ($requestId && in_array($status, ['Waiting','Approved','Solved'], true)) {
            $stmt = $pdo->prepare('UPDATE backup_requests SET status = ?, reviewed_by = ? WHERE id = ?');
            $stmt->execute([$status, currentUserId(), $requestId]);
            header('Location: backup_requests.php?updated=1'); exit;
        } else { $error = 'Invalid request or status.'; }
    } else { $error = 'You do not have permission to change request statuses.'; }
}
if (isset($_GET['created'])) $success = 'Backup request submitted and saved in MySQL.';
if (isset($_GET['updated'])) $success = 'Request status updated.';
$incidents = $pdo->query('SELECT id, title, description, department, priority, status, location_name FROM incidents ORDER BY created_at DESC')->fetchAll();
$requests = $pdo->query('SELECT br.*, i.title AS incident_title, i.location_name, u.full_name AS requester FROM backup_requests br JOIN incidents i ON i.id=br.incident_id JOIN users u ON u.id=br.requested_by ORDER BY FIELD(br.status,"Waiting","Approved","Solved"), br.created_at DESC')->fetchAll();
$pendingCount = count(array_filter($requests, fn($r) => $r['status'] === 'Waiting'));
$criticalCount = count(array_filter($requests, fn($r) => $r['priority'] === 'Critical'));
$solvedCount = count(array_filter($requests, fn($r) => $r['status'] === 'Solved'));
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Backup Requests | Disaster Response</title><link rel="stylesheet" href="css/style.css"></head><body>
<header class="topbar"><a class="brand" href="index.php">Disaster<span>Response</span></a><nav><a href="index.php">Dashboard</a><a class="active" href="backup_requests.php">Backup Request</a><a href="best_route.php">Best Route</a></nav><span class="role-pill"><?= h(currentRole()) ?> · demo</span></header>
<main class="container"><section class="page-heading"><div><p class="eyebrow">RESOURCE COORDINATION</p><h1>Backup requests</h1><p class="muted">Request additional resources for an existing emergency incident.</p></div><div class="heading-stats"><div class="stat-card"><span>Total requests</span><strong><?= count($requests) ?></strong><small>All resource requests</small></div><div class="stat-card"><span>Awaiting review</span><strong><?= $pendingCount ?></strong><small>Pending decisions</small></div></div></section>
<?php if ($error): ?><div class="notice error"><?= h($error) ?></div><?php endif; ?><?php if ($success): ?><div class="notice success"><?= h($success) ?></div><?php endif; ?>
<section class="panel"><div class="section-title"><div><h2>Request backup from your department</h2><p>Select an incident and enter the resources needed by your team.</p></div></div><div class="request-layout"><form method="post" class="request-form"><input type="hidden" name="action" value="create">
<label>Incident<select name="incident_id" id="incidentSelect" required><option value="">Select an incident</option><?php foreach ($incidents as $i): ?><option value="<?= (int)$i['id'] ?>"><?= h('#'.$i['id'].' — '.$i['title'].' ('.$i['location_name'].')') ?></option><?php endforeach; ?></select></label>
<label>Department<select name="department" required><option value="">Select your department</option><?php foreach ($departments as $d): ?><option value="<?= h($d) ?>"><?= h($d) ?></option><?php endforeach; ?></select></label>
<label>Resource type<select name="resource_type" required><?php foreach ($resources as $r): ?><option value="<?= h($r) ?>"><?= h($r) ?></option><?php endforeach; ?></select></label>
<label>Quantity<input type="number" name="quantity" min="1" max="1000" value="1" required></label>
<label>Priority<select name="priority" required><?php foreach ($priorities as $p): ?><option value="<?= h($p) ?>" <?= $p === 'High' ? 'selected' : '' ?>><?= h($p) ?></option><?php endforeach; ?></select></label>
<label class="full">Reason / details<textarea name="reason" rows="3" placeholder="Explain what is needed and why..." required></textarea></label><div class="full form-actions"><button class="btn primary" type="submit">Submit backup request</button></div></form>
<aside class="incident-report-card"><p class="eyebrow">INCIDENT REPORT</p><h3 id="reportTitle">Select an incident</h3><p class="report-hint">Choose an incident to view its details before requesting backup.</p><div class="report-detail"><span>Location</span><strong id="reportLocation">—</strong></div><div class="report-detail"><span>Lead department</span><strong id="reportDepartment">—</strong></div><div class="report-detail"><span>Priority</span><strong id="reportPriority">—</strong></div><div class="report-detail"><span>Status</span><strong id="reportStatus">—</strong></div><div class="report-description"><span>Report details</span><p id="reportDescription">Choose an incident from the form.</p></div></aside></div></section>
<section class="queue-overview"><div class="mini-stat"><span class="mini-icon amber">◷</span><div><strong><?= $pendingCount ?></strong><span>Waiting</span></div></div><div class="mini-stat"><span class="mini-icon red">!</span><div><strong><?= $criticalCount ?></strong><span>Critical priority</span></div></div><div class="mini-stat"><span class="mini-icon green">✓</span><div><strong><?= $solvedCount ?></strong><span>Solved</span></div></div></section>
<section class="panel queue-panel"><div class="section-title"><div><p class="eyebrow">LIVE OVERVIEW</p><h2>Request queue</h2><p>Requests are stored in MySQL and shared by anyone using this database.</p></div><span class="queue-count"><?= count($requests) ?> requests</span></div><div class="table-wrap"><table><thead><tr><th>Request</th><th>Incident / location</th><th>Resource</th><th>Priority</th><th>Details</th><th>Department</th><th>Status</th><?php if ($canManage): ?><th>Update</th><?php endif; ?></tr></thead><tbody>
<?php if (!$requests): ?><tr><td colspan="<?= $canManage ? 8 : 7 ?>" class="empty">No backup requests yet.</td></tr><?php endif; ?>
<?php foreach ($requests as $r): ?><tr><td><strong>#<?= (int)$r['id'] ?></strong><small><?= h(date('M j, H:i', strtotime($r['created_at']))) ?></small></td><td><strong><?= h($r['incident_title']) ?></strong><small><?= h($r['location_name']) ?></small></td><td><?= h($r['resource_type']) ?><small>Quantity: <?= (int)$r['quantity'] ?></small></td><td><span class="priority <?= strtolower(h($r['priority'])) ?>"><?= h($r['priority']) ?></span></td><td class="request-details-cell"><?= h($r['reason']) ?><small>Requested by: <?= h($r['requester']) ?></small></td><td><?= h($r['department']) ?></td><td><span class="status <?= strtolower(str_replace(' ','-',h($r['status']))) ?>"><?= h($r['status']) ?></span></td><?php if ($canManage): ?><td><form method="post" class="inline-form"><input type="hidden" name="action" value="update"><input type="hidden" name="request_id" value="<?= (int)$r['id'] ?>"><select name="status" aria-label="Request status"><?php foreach (['Waiting','Approved','Solved'] as $s): ?><option value="<?= h($s) ?>" <?= $s === $r['status'] ? 'selected' : '' ?>><?= h($s) ?></option><?php endforeach; ?></select><button class="btn small" type="submit">Save</button></form></td><?php endif; ?></tr><?php endforeach; ?>
</tbody></table></div></section><p class="footnote">Demo authentication only. Replace the fallback user/session values with your teammates' login system when merging.</p></main>
<script>
const incidentData = <?= json_encode(array_map(static fn($i) => ['id'=>(int)$i['id'],'title'=>$i['title'],'description'=>$i['description'] ?? '', 'department'=>$i['department'],'priority'=>$i['priority'],'status'=>$i['status'],'location'=>$i['location_name']], $incidents), JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>;
const sel=document.getElementById('incidentSelect');
function showIncident(){const x=incidentData.find(i=>String(i.id)===sel.value);const fields={reportTitle:x?.title||'Select an incident',reportLocation:x?.location||'—',reportDepartment:x?.department||'—',reportPriority:x?.priority||'—',reportStatus:x?.status||'—',reportDescription:x?.description||'Choose an incident from the form.'};for(const [id,value] of Object.entries(fields))document.getElementById(id).textContent=value;}
sel.addEventListener('change',showIncident);
</script></body></html>
