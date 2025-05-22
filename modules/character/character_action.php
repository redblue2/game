<?php
session_start();
header('Content-Type: application/json');
require_once 'modules/functions.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Giriş yapılmamış.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$request = json_decode(file_get_contents('php://input'), true);

if (!$request || !isset($request['action'])) {
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek.']);
    exit;
}

switch ($request['action']) {
    case 'increase_stat':
        $stat = $request['stat'] ?? '';
        $validStats = ['strength', 'defense', 'stamina', 'agility', 'wisdom', 'luck'];
        if (!in_array($stat, $validStats)) {
            echo json_encode(['success' => false, 'message' => 'Geçersiz stat.']);
            exit;
        }
        // Kullanıcının kullanılabilir puanını kontrol et
        $user = getUserData($user_id);
        if ($user['available_stat_points'] < 1) {
            echo json_encode(['success' => false, 'message' => 'Yeterli stat puanı yok.']);
            exit;
        }

        // Stat artır ve kullanılabilir puanı azalt
        $newStatValue = increaseUserStat($user_id, $stat);
        $newAvailablePoints = decreaseAvailableStatPoints($user_id);

        echo json_encode([
            'success' => true,
            'new_stat_value' => $newStatValue,
            'new_available_points' => $newAvailablePoints
        ]);
        break;

    case 'equip_item':
        $slot = $request['slot'] ?? '';
        $item_id = (int)($request['item_id'] ?? 0);

        $validSlots = ['head', 'body', 'legs', 'weapon'];
        if (!in_array($slot, $validSlots)) {
            echo json_encode(['success' => false, 'message' => 'Geçersiz slot.']);
            exit;
        }
        if ($item_id < 1) {
            echo json_encode(['success' => false, 'message' => 'Geçersiz ekipman.']);
            exit;
        }

        // Kullanıcının envanterinde bu item ve slot uyumu kontrolü
        if (!userOwnsItemInSlot($user_id, $item_id, $slot)) {
            echo json_encode(['success' => false, 'message' => 'Ekipman bu slot için uygun değil veya envanterde yok.']);
            exit;
        }

        // Ekipmanı kuşan
        $success = equipUserItem($user_id, $slot, $item_id);

        if ($success) {
            $itemData = getItemData($item_id);
            echo json_encode([
                'success' => true,
                'new_item_image' => $itemData['image'],
                'new_item_name' => $itemData['name']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ekipman kuşanamadı.']);
        }

        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Bilinmeyen işlem.']);
}
