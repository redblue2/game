<?php
session_start();
require_once '../modules/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$stat = $data['stat'];
$user_id = $_SESSION['user_id'];

$valid_stats = ['strength', 'defense', 'stamina', 'agility', 'wisdom', 'luck'];

if (!in_array($stat, $valid_stats)) {
    echo json_encode(['error' => 'Geçersiz stat']);
    exit;
}

// Seviye başına +4 puan veriliyor. Kullanıcının harcanan puanlarını kontrol et
$stmt = $pdo->prepare("SELECT level, spent_points FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$total_points = $user['level'] * 4;
$used_points = $user['spent_points'];

if ($used_points >= $total_points) {
    echo json_encode(['error' => 'Yetersiz stat puanı']);
    exit;
}

// Stat güncelle ve harcanan puanı artır
$stmt = $pdo->prepare("UPDATE users SET $stat = $stat + 1, spent_points = spent_points + 1 WHERE id = ?");
$stmt->execute([$user_id]);

echo json_encode(['success' => true]);
