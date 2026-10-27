<?php
// Local XAMPP configuration for the two-feature starter project.
// It uses its own database by default to avoid overwriting a teammate's database.
$host = 'localhost';
$dbname = 'disaster_response_features';
$dbuser = 'root';
$dbpass = '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $dbuser,
        $dbpass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed. Start MySQL in XAMPP and import sql/schema.sql.');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// DEMO ONLY: replace these fallback values with the existing project's login/session system when merging.
function currentUserId(): int {
    return (int)($_SESSION['user_id'] ?? 1);
}
function currentRole(): string {
    return (string)($_SESSION['role'] ?? 'admin');
}
function h($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function canManageRequests(): bool {
    return in_array(currentRole(), ['admin', 'department_head', 'dispatcher'], true);
}
