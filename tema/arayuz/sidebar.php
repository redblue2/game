<!-- Menü -->
<div class="menu">
    <h2><i class="fas fa-bars"></i> Menü</h2>
    <ul>
        <li><a href="#" class="menu-btn" data-sayfa="modules/dashboard/main.php"><i class="fas fa-home"></i> Ana Sayfa</a></li>
        <li><a href="#" class="menu-btn" data-sayfa="modules/character/character.php"><i class="fas fa-user"></i> Karakter</a></li>
        <li><a href="#" class="menu-btn" data-sayfa="modules/quests/quests.php"><i class="fas fa-scroll"></i> Görevler</a></li>
        <li><a href="#" class="menu-btn" data-sayfa="modules/shop/shop.php"><i class="fas fa-store"></i> Market</a></li>
        <li><a href="#" class="menu-btn" data-sayfa="modules/maps/map.php"><i class="fas fa-map"></i> Harita</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Çıkış Yap</a></li>
    </ul>
</div>

<!-- Font Awesome (ikonlar için) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.menu {
    background: linear-gradient(to bottom, #111, #1c1c1c);
    padding: 20px;
    width: 240px;
    min-height: 100vh;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.6);
    color: #f0f0f0;
    font-family: 'Segoe UI', sans-serif;
}

.menu h2 {
    margin-bottom: 20px;
    font-size: 22px;
    text-align: center;
    border-bottom: 1px solid #444;
    padding-bottom: 10px;
    color: #5cc6ff;
}

.menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.menu ul li {
    margin-bottom: 12px;
}

.menu a {
    color: #ccc;
    text-decoration: none;
    background-color: #2a2a2a;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    border-radius: 8px;
    transition: background 0.3s, color 0.3s;
    gap: 10px;
    font-size: 15px;
}

.menu a:hover {
    background-color: #5cc6ff;
    color: #000;
    font-weight: bold;
}

.menu a i {
    width: 20px;
    text-align: center;
}

/* Aktif link */
.menu a.active {
    background-color: #5cc6ff;
    color: #000;
    font-weight: bold;
}
</style>
