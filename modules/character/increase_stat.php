<?php
session_start();
if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Lütfen giriş yapınız.']);
    exit;
}

include("../../../config.php");
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['stat'])) {
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek.']);
    exit;
}

$valid_stats = ['strength', 'defense', 'stamina', 'agility', 'wisdom', 'luck'];
$stat = $_POST['stat'];

if (!in_array($stat, $valid_stats)) {
    echo json_encode(['success' => false, 'message' => 'Geçersiz stat.']);
    exit;
}

// Öncelikle mevcut kullanılabilir stat puanını alalım
$stmt = $conn->prepare("SELECT available_stat_points, $stat FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Kullanıcı bulunamadı.']);
    exit;
}

$user = $result->fetch_assoc();

if ((int)$user['available_stat_points'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Yeterli kullanılabilir puan yok.']);
    exit;
}

// Stat artır ve kullanılabilir puanı azalt
$stmtUpdate = $conn->prepare("UPDATE users SET $stat = $stat + 1, available_stat_points = available_stat_points - 1 WHERE id = ?");
if (!$stmtUpdate->bind_param("i", $user_id)) {
    echo json_encode(['success' => false, 'message' => 'Parametre bağlama hatası.']);
    exit;
}

if ($stmtUpdate->execute()) {
    // Güncellenmiş değerleri tekrar çek
    $stmtNew = $conn->prepare("SELECT $stat, available_stat_points FROM users WHERE id = ?");
    $stmtNew->bind_param("i", $user_id);
    $stmtNew->execute();
    $resNew = $stmtNew->get_result();
    $newUser = $resNew->fetch_assoc();

    echo json_encode([
        'success' => true,
        'new_stat_value' => (int)$newUser[$stat],
        'available_stat_points' => (int)$newUser['available_stat_points']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Güncelleme başarısız.']);
}
