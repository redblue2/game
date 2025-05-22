<?php
// Bu dosya sadece içerik kısmını döndürür.
// session_start() gerekmiyor, layout.php zaten açıyor.

// Örnek veri - gerçek veriyi DB'den çekebilirsin
$username = htmlspecialchars($_SESSION['username']);
$level = $_SESSION['level'] ?? 1;
$group = $_SESSION['group_name'] ?? 'Bilinmiyor';

// Basit hoşgeldin mesajı ve kısa özet

?>

<h2>Hoşgeldin, <?php echo $username; ?>!</h2>
<p>Şu an <strong><?php echo $group; ?></strong> grubundasın ve <strong>Seviye <?php echo $level; ?></strong> seviyesindesin.</p>

<section style="margin-top:30px;">
    <h3>Panel Özeti</h3>
    <ul>
        <li>Aktif görevlerin: <strong>3</strong></li>
        <li>Son savaşın: <strong>Başarıyla tamamlandı</strong></li>
        <li>Envanterinde <strong>15</strong> eşya bulunuyor</li>
    </ul>
</section>

<section style="margin-top: 40px;">
    <h3>Haberler</h3>
    <p>Yeni harita noktaları ve görevler yakında eklenecek. Hazır ol!</p>
</section>
