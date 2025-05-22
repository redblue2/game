<?php
session_start();
include("../../../config.php"); // Veritabanı bağlantısı (yol gerektiğinde uyarlanabilir)

$user_id = $_SESSION['user_id'] ?? 0;

$sql = $conn->prepare("SELECT username, level, group_name, strength, defense, endurance, agility, wisdom, luck FROM users WHERE id = ?");
$sql->bind_param("i", $user_id);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<p>Kullanıcı bilgileri alınamadı.</p>";
    exit;
}
?>

<div style="padding: 20px;">
    <h2>Karakter Bilgileri</h2>
    <table style="width: 100%; max-width: 600px; border-collapse: collapse; background-color: #222; color: #fff;">
        <tr>
            <td><strong>Kullanıcı Adı:</strong></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
        </tr>
        <tr>
            <td><strong>Grup:</strong></td>
            <td><?php echo htmlspecialchars($user['group_name']); ?></td>
        </tr>
        <tr>
            <td><strong>Seviye:</strong></td>
            <td><?php echo (int)$user['level']; ?></td>
        </tr>
        <tr>
            <td><strong>Güç:</strong></td>
            <td><?php echo (int)$user['strength']; ?></td>
        </tr>
        <tr>
            <td><strong>Savunma:</strong></td>
            <td><?php echo (int)$user['defense']; ?></td>
        </tr>
        <tr>
            <td><strong>Dayanıklılık:</strong></td>
            <td><?php echo (int)$user['endurance']; ?></td>
        </tr>
        <tr>
            <td><strong>Çeviklik:</strong></td>
            <td><?php echo (int)$user['agility']; ?></td>
        </tr>
        <tr>
            <td><strong>Bilgelik:</strong></td>
            <td><?php echo (int)$user['wisdom']; ?></td>
        </tr>
        <tr>
            <td><strong>Şans:</strong></td>
            <td><?php echo (int)$user['luck']; ?></td>
        </tr>
    </table>
</div>
