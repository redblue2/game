<?php
session_start();
include_once('functions.php');

if (!isset($_SESSION['user_id'])) {
    echo "Oturum yok, giriş yapmalısınız.";
    exit;
}

$page = $_GET['page'] ?? '';

switch ($page) {
    case 'character':
        include('character/character.php');
        break;
    case 'inventory':
        echo "<h2>Envanter sayfası yapılıyor...</h2>";
        break;
    case 'map':
        echo "<h2>Harita sayfası yapılıyor...</h2>";
        break;
    case 'quests':
        echo "<h2>Görevler sayfası yapılıyor...</h2>";
        break;
    case 'market':
        echo "<h2>Market sayfası yapılıyor...</h2>";
        break;
    default:
        echo "<h2>Hoşgeldiniz! Soldaki menüden bir sayfa seçiniz.</h2>";
        break;
}
