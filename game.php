<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>MK Oyunu - Oyun Alanı</title>
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>

<?php include('modules/sidebar.php'); ?>

<div class="main-content" id="main-content">
    <!-- Dinamik içerik buraya yüklenecek -->
</div>

<script src="assets/js/main.js"></script>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #222;
    color: #eee;
}

.main-content {
    margin-left: 220px; /* Sidebar genişliği kadar boşluk */
    padding: 20px;
    min-height: 100vh;
    background-color: #2c2c2c;
}
</style>

</body>
</html>
