<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>MK Oyunu - Ana Ekran</title>
    <link rel="stylesheet" href="tema/arayuz/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container">
    <div class="sidebar">
        <?php include("tema/arayuz/sidebar.php"); ?>
    </div>
    <div class="main-content" id="icerik">
        <?php include("modules/dashboard/main.php"); ?>
    </div>
</div>

<script>
// Menü butonlarına tıklanınca içerik yüklenir
$(document).on("click", ".menu-btn", function(e) {
    e.preventDefault();
    const sayfa = $(this).data("sayfa");
    $("#icerik").load(sayfa);
});
</script>

</body>
</html>
