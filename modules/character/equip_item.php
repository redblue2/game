<?php
session_start();
require_once '../modules/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$item_id = (int) $data['item_id'];
$slot = $data['slot'];
$user_id = $_SESSION['user_id'];

// Geçerli slot mu kontrol et
$valid_slots = ['helmet', 'armor', 'weapon', 'boots', 'gloves', 'ring'];
if (!in_array($slot, $valid_slots)) {
    echo json_encode(['error' => 'Geçersiz slot']);
    exit;
}

// Envanterden eşyayı çek
$stmt = $pdo->prepare("SELECT * FROM user_inventory WHERE id = ? AND user_id = ?");
$stmt->execute([$item_id, $user_id]);
$item = $stmt->fetch();

if (!$item) {
    echo json_encode(['error' => 'Eşya bulunamadı']);
    exit;
}

// Daha önce o slota kuşanılmış bir eşya varsa, onu envantere geri at
$stmt = $pdo->prepare("SELECT * FROM user_equipment WHERE user_id = ? AND slot = ?");
$stmt->execute([$user_id, $slot]);
$equipped = $stmt->fetch();

if ($equipped) {
    $stmt = $pdo->prepare("INSERT INTO user_inventory (user_id, name, image, bonus) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $equipped['name'], $equipped['image'], $equipped['bonus']]);
}

// Envanterden sil
$stmt = $pdo->prepare("DELETE FROM user_inventory WHERE id = ? AND user_id = ?");
$stmt->execute([$item_id, $user_id]);

// Ekipmana ekle (update varsa güncelle, yoksa insert)
$stmt = $pdo->prepare("INSERT INTO user_equipment (user_id, slot, name, image, bonus)
                       VALUES (?, ?, ?, ?, ?)
                       ON DUPLICATE KEY UPDATE name = VALUES(name), image = VALUES(image), bonus = VALUES(bonus)");
$stmt->execute([$user_id, $slot, $item['name'], $item['image'], $item['bonus']]);

echo json_encode(['success' => true]);
