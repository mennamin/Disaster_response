<?php
// Part 7: Requests that include full report details
// Simple prototype with sample data only.
$reports = [
    1 => ['id'=>1, 'type'=>'Road Accident', 'location'=>'Tahrir Square',
          'description'=>'Two cars collided at an intersection.', 'severity'=>'Critical',
          'affected_people'=>4, 'status'=>'Assigned', 'reported_at'=>'16 October 2026'],
    2 => ['id'=>2, 'type'=>'Fire', 'location'=>'Nasr City',
          'description'=>'Smoke was reported in a residential building.', 'severity'=>'High',
          'affected_people'=>12, 'status'=>'Under Review', 'reported_at'=>'16 October 2026'],
    3 => ['id'=>3, 'type'=>'Medical Emergency', 'location'=>'Dokki',
          'description'=>'A person fainted in a public area.', 'severity'=>'Medium',
          'affected_people'=>1, 'status'=>'Resolved', 'reported_at'=>'16 October 2026'],
];
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$report = $reports[$id] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Request Full Details</h1>
    <?php if ($report): ?>
        <p><strong>Report ID:</strong> <?= e($report['id']) ?></p>
        <p><strong>Incident Type:</strong> <?= e($report['type']) ?></p>
        <p><strong>Location:</strong> <?= e($report['location']) ?></p>
        <p><strong>Description:</strong> <?= e($report['description']) ?></p>
        <p><strong>Severity:</strong> <?= e($report['severity']) ?></p>
        <p><strong>Estimated Affected People:</strong> <?= e($report['affected_people']) ?></p>
        <p><strong>Status:</strong> <?= e($report['status']) ?></p>
        <p><strong>Reported At:</strong> <?= e($report['reported_at']) ?></p>
    <?php else: ?>
        <p>Report not found.</p>
    <?php endif; ?>
    <p><a href="reports.php">Back to Reports</a></p>
</body>
</html>
