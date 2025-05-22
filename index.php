<!-- game/index.php -->

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MK - Ütopyanın Karanlığında</title>
    <link rel="stylesheet" href="tema/anasayfa/style.css">
</head>
<body>

    <!-- Üst Başlık: Oyun İsmi ve Slogan -->
    <header>
        <h1>MK</h1>
        <p>Ütopyanın Karanlığında Hayatta Kal!</p>
    </header>

    <!-- Menü Barı -->
    <nav class="menu-bar">
        <a href="#">Hikaye</a>
        <a href="#">Rehber</a>
        <a href="#">Yardım</a>
    </nav>

    <!-- Ana Video Alanı -->
    <main>
        <video autoplay muted loop>
            <source src="tema/anasayfa/banner.mp4" type="video/mp4">
            Tarayıcınız video etiketini desteklemiyor.
        </video>

        <!-- Giriş / Kayıt Ol Butonları -->
        <div class="auth-buttons">
            <a href="modules/login.php" class="btn">Giriş Yap</a>
            <a href="modules/register.php" class="btn">Kayıt Ol</a>
        </div>
    </main>

    <!-- Sayfa Altı / İmza Barı -->
    <footer>
        <p>&copy; 2025 MK Oyun Stüdyosu | Kodlama: Sen ve GPT</p>
    </footer>

</body>
</html>
