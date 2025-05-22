<?php
// functions.php

/**
 * Veritabanı bağlantısı (PDO, Singleton)
 * @return PDO
 */
function getPDOConnection() {
    static $pdo = null;

    if ($pdo === null) {
        try {
            $host = 'localhost';
            $dbname = 'mk_oyun';
            $username = 'root';
            $password = '';
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            // Loglama yapılabilir burada
            die("Veritabanı bağlantı hatası. Lütfen daha sonra tekrar deneyiniz.");
        }
    }

    return $pdo;
}

/**
 * Kullanıcı verilerini alır.
 * @param int $user_id
 * @return array|false
 */
function getUserData(int $user_id) {
    $pdo = getPDOConnection();
    $sql = "SELECT id, username, level, group_name, strength, defense, stamina, agility, wisdom, luck, available_stat_points 
            FROM users 
            WHERE id = :user_id 
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetch();
}

/**
 * Kullanıcının kuşandığı ekipmanları getirir.
 * @param int $user_id
 * @return array
 */
function getUserEquipment(int $user_id): array {
    $pdo = getPDOConnection();
    $sql = "SELECT slot, item_name, image FROM user_equipment WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);

    $equipment = [];
    while ($row = $stmt->fetch()) {
        $equipment[$row['slot']] = $row;
    }

    return $equipment;
}

/**
 * Stat artırma işlemi
 * @param int $user_id
 * @param string $stat
 * @return bool
 */
function increaseStat(int $user_id, string $stat): bool {
    $pdo = getPDOConnection();
    $valid_stats = ['strength', 'defense', 'stamina', 'agility', 'wisdom', 'luck'];

    if (!in_array($stat, $valid_stats, true)) {
        return false;
    }

    try {
        $pdo->beginTransaction();

        // Kilitle ve kontrol et
        $stmt = $pdo->prepare("SELECT available_stat_points FROM users WHERE id = :user_id FOR UPDATE");
        $stmt->execute(['user_id' => $user_id]);
        $user = $stmt->fetch();

        if (!$user || $user['available_stat_points'] <= 0) {
            $pdo->rollBack();
            return false;
        }

        // Güncelle
        $sql = "UPDATE users SET {$stat} = {$stat} + 1, available_stat_points = available_stat_points - 1 WHERE id = :user_id";
        $updateStmt = $pdo->prepare($sql);
        $updateStmt->execute(['user_id' => $user_id]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

/**
 * Kullanıcının ekipman kuşanma işlemini yapar.
 * @param int $user_id
 * @param int $slot
 * @param string $item_name
 * @param string $image
 * @return bool
 */
function equipItem(int $user_id, int $slot, string $item_name, string $image): bool {
    $pdo = getPDOConnection();

    try {
        $pdo->beginTransaction();

        // Slot doluysa güncelle yoksa ekle
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_equipment WHERE user_id = :user_id AND slot = :slot");
        $stmt->execute(['user_id' => $user_id, 'slot' => $slot]);
        $exists = $stmt->fetchColumn() > 0;

        if ($exists) {
            $update = $pdo->prepare("UPDATE user_equipment SET item_name = :item_name, image = :image WHERE user_id = :user_id AND slot = :slot");
            $update->execute([
                'item_name' => $item_name,
                'image' => $image,
                'user_id' => $user_id,
                'slot' => $slot
            ]);
        } else {
            $insert = $pdo->prepare("INSERT INTO user_equipment (user_id, slot, item_name, image) VALUES (:user_id, :slot, :item_name, :image)");
            $insert->execute([
                'user_id' => $user_id,
                'slot' => $slot,
                'item_name' => $item_name,
                'image' => $image
            ]);
        }

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

/**
 * Kullanıcının kuşandığı ekipmandan birini çıkarır.
 * @param int $user_id
 * @param int $slot
 * @return bool
 */
function unequipItem(int $user_id, int $slot): bool {
    $pdo = getPDOConnection();

    try {
        $stmt = $pdo->prepare("DELETE FROM user_equipment WHERE user_id = :user_id AND slot = :slot");
        return $stmt->execute(['user_id' => $user_id, 'slot' => $slot]);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Oturumdaki kullanıcı id'sini döner veya false
 * @return int|false
 */
function getSessionUserId() {
    session_start();
    return $_SESSION['user_id'] ?? false;
}
function getUserInventory($userId, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM inventory WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
