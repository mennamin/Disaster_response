<?php
require __DIR__ . '/config.php';
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['ok'=>false,'error'=>'POST required']); exit; }
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Invalid JSON']); exit; }
$fields = ['origin_name','destination_name','origin_lat','origin_lng','destination_lat','destination_lng','distance_km','duration_minutes'];
foreach ($fields as $f) { if (!isset($input[$f]) || !is_numeric($input[$f]) && in_array($f, ['origin_lat','origin_lng','destination_lat','destination_lng','distance_km','duration_minutes'], true) || is_string($input[$f]) && trim($input[$f]) === '') { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Missing or invalid field: '.$f]); exit; } }
try {
  $stmt = $pdo->prepare('INSERT INTO route_searches (requested_by, origin_name, destination_name, origin_lat, origin_lng, destination_lat, destination_lng, distance_km, duration_minutes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
  $stmt->execute([currentUserId(), substr(trim((string)$input['origin_name']),0,255), substr(trim((string)$input['destination_name']),0,255), (float)$input['origin_lat'], (float)$input['origin_lng'], (float)$input['destination_lat'], (float)$input['destination_lng'], max(0,(float)$input['distance_km']), max(0,(float)$input['duration_minutes'])]);
  echo json_encode(['ok'=>true,'id'=>(int)$pdo->lastInsertId()]);
} catch (Throwable $e) { http_response_code(500); echo json_encode(['ok'=>false,'error'=>'Could not save route. Check that the SQL schema was imported.']); }
