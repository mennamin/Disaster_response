<?php
// Part 4: Display of Reports and Their Status
// Simple prototype with sample data only.
$reports = [
    ['id' => 1, 'type' => 'Road Accident', 'location' => 'Tahrir Square', 'severity' => 'Critical', 'status' => 'Assigned'],
    ['id' => 2, 'type' => 'Fire', 'location' => 'Nasr City', 'severity' => 'High', 'status' => 'Under Review'],
    ['id' => 3, 'type' => 'Medical Emergency', 'location' => 'Dokki', 'severity' => 'Medium', 'status' => 'Resolved'],
];
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Display Reports</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Incident Reports</h1>
    <p>This page displays incident reports and their current status.</p>

    <table>
        <tr>
            <th>Report ID</th><th>Incident Type</th><th>Location</th>
            <th>Severity</th><th>Status</th><th>Action</th>
        </tr>
        <?php foreach ($reports as $report): ?>
        <tr>
            <td><?= e($report['id']) ?></td>
            <td><?= e($report['type']) ?></td>
            <td><?= e($report['location']) ?></td>
            <td><?= e($report['severity']) ?></td>
            <td><?= e($report['status']) ?></td>
            <td><a href="request_details.php?id=<?= e($report['id']) ?>">View details</a></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Edit Status Description (Prototype)</h2>
    <form method="post">
        <label for="report_id">Report ID:</label>
        <input type="number" id="report_id" name="report_id" min="1" required>
        <label for="status_description">New status description:</label>
        <input type="text" id="status_description" name="status_description" required>
        <button type="submit">Submit</button>
    </form>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p class="message">Prototype only: your status description was submitted but is not saved.</p>
    <?php endif; ?>
</body>
</html>
