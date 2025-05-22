<?php
session_start();
if (empty($_SESSION['user_id'])) {
    echo "<p>Lütfen giriş yapınız.</p>";
    exit;
}
include("../../../config.php");

$user_id = $_SESSION['user_id'];

// Stat artırma işlemi için kullanılabilir puan alma
$stmt = $conn->prepare("SELECT available_stat_points FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$available_stat_points = 0;
if ($row = $res->fetch_assoc()) {
    $available_stat_points = (int)$row['available_stat_points'];
}

$sql = $conn->prepare("SELECT username, level, group_name, strength, defense, stamina, agility, wisdom, luck FROM users WHERE id = ?");
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

<div id="message" style="color: #ffcc00; font-weight: bold; margin-bottom: 15px;"></div>

<div style="padding: 20px; color: #fff;">
    <h2>Karakter Bilgileri</h2>
    <p>Kullanılabilir Stat Puanı: <span id="available_stat_points"><?php echo $available_stat_points; ?></span></p>
    <table style="width: 100%; max-width: 600px; border-collapse: collapse; background-color: #222;">
        <?php
        // Statları dizi olarak tanımlayalım (anahtar => gösterim ismi)
        $stats = [
            "strength" => "Güç",
            "defense" => "Savunma",
            "stamina" => "Dayanıklılık",
            "agility" => "Çeviklik",
            "wisdom" => "Bilgelik",
            "luck" => "Şans",
        ];

        foreach ($stats as $key => $label) {
            echo '<tr>';
            echo '<td style="padding:8px; border: 1px solid #444;"><strong>' . $label . ':</strong></td>';
            echo '<td style="padding:8px; border: 1px solid #444;" id="stat_' . $key . '">' . (int)$user[$key] . '</td>';
            echo '<td style="padding:8px; border: 1px solid #444;">';
            // Stat puanı varsa artır butonu göster
            if ($available_stat_points > 0) {
                echo '<button class="increaseStatBtn" data-stat="' . $key . '" style="padding:4px 8px; background:#28a745; color:#fff; border:none; cursor:pointer;">+</button>';
            } else {
                echo '<button disabled style="padding:4px 8px; background:#555; color:#ccc; border:none;">+</button>';
            }
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </table>
</div>

<script>
// AJAX ile stat artırma fonksiyonu
document.querySelectorAll('.increaseStatBtn').forEach(button => {
    button.addEventListener('click', function() {
        const stat = this.getAttribute('data-stat');

        fetch('increase_stat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'stat=' + encodeURIComponent(stat)
        })
        .then(response => response.json())
        .then(data => {
            const messageDiv = document.getElementById('message');

            if (data.success) {
                // Stat değerini güncelle
                document.getElementById('stat_' + stat).textContent = data.new_stat_value;
                // Kullanılabilir puan güncelle
                document.getElementById('available_stat_points').textContent = data.available_stat_points;
                messageDiv.style.color = '#28a745';
                messageDiv.textContent = 'Stat başarıyla artırıldı!';
                
                // Eğer puan kalmadıysa butonları disable et
                if (data.available_stat_points <= 0) {
                    document.querySelectorAll('.increaseStatBtn').forEach(btn => btn.disabled = true);
                }
            } else {
                messageDiv.style.color = '#ff5555';
                messageDiv.textContent = data.message || 'Bir hata oluştu.';
            }
        })
        .catch(err => {
            document.getElementById('message').style.color = '#ff5555';
            document.getElementById('message').textContent = 'İstek gönderilirken hata oluştu.';
        });
    });
});
</script>
