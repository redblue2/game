<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}
$username = htmlspecialchars($_SESSION["username"]);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>MK Oyuncu Paneli</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #1b1b1b;
            color: #f0f0f0;
            margin: 0;
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 220px;
            background-color: #2c2c2c;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.5);
        }

        .sidebar h2 {
            font-size: 20px;
            color: #6ec6ff;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: #ccc;
            text-decoration: none;
            margin: 10px 0;
            transition: color 0.2s;
        }

        .sidebar a:hover {
            color: #fff;
        }

        .main {
            flex-grow: 1;
            padding: 40px;
        }

        .main h1 {
            margin-top: 0;
            font-size: 28px;
        }

        .logout {
            margin-top: 30px;
            display: inline-block;
            padding: 8px 16px;
            background-color: #ff6e6e;
            color: #000;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .logout:hover {
            background-color: #ff4c4c;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>MK Oyunu</h2>
        <a href="character.php">👤 Karakterim</a>
        <a href="map.php">🗺️ Harita</a>
        <a href="inventory.php">🎒 Envanter</a>
        <a href="missions.php">📜 Görevler</a>
        <a href="market.php">🏪 Market</a>
        <a href="auction.php">💰 Müzayede</a>
        <a href="logout.php" class="logout">Çıkış Yap</a>
    </div>

    <div class="main">
        <h1>Hoş geldin, <?php echo $username; ?>!</h1>
        <p>Buradan oyun ekranlarına geçebilirsin. Sol menüyü kullanarak karakterini geliştir, görevleri yap veya haritayı keşfet.</p>
    </div>
</body>
</html>
