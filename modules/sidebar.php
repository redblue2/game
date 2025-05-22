<?php
session_start();
include_once('functions.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "Giriş yapınız.";
    exit;
}

$user = getUserData($user_id);
$avatar = $user['avatar'] ?? 'default.png';
?>

<div class="sidebar">
    <div class="profile-info">
        <img src="../../images/avatar/<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" class="avatar-small">
        <h3><?php echo htmlspecialchars($user['username']); ?></h3>
        <p><?php echo htmlspecialchars($user['group_name']); ?></p>
    </div>
    <nav class="menu">
        <ul>
            <li><a href="#" data-page="character">Karakter</a></li>
            <li><a href="#" data-page="inventory">Envanter</a></li>
            <li><a href="#" data-page="map">Harita</a></li>
            <li><a href="#" data-page="quests">Görevler</a></li>
            <li><a href="#" data-page="market">Market</a></li>
        </ul>
    </nav>
</div>

<style>
.sidebar {
    width: 220px;
    height: 100vh;
    background-color: #1f1f1f;
    color: #ccc;
    padding: 15px;
    box-sizing: border-box;
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: auto;
}

.avatar-small {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}

.menu ul {
    list-style: none;
    padding: 0;
}

.menu ul li {
    margin: 15px 0;
}

.menu ul li a {
    color: #ccc;
    text-decoration: none;
    cursor: pointer;
}

.menu ul li a:hover {
    color: #4caf50;
}
</style>
