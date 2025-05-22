<?php
session_start();

// Giriş kontrolü
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Güvenlik için sadece belirli sayfalar yüklenebilir
$allowed_pages = [
    'dashboard/home',
    'character/character',
    'missions/missions',
    'map/map',
    'market/market',
    'auction/auction',
];

// Varsayılan sayfa
$default_page = 'dashboard/home';

// GET ile sayfa isteği alınıyor (Ajax için)
$page = isset($_GET['page']) ? $_GET['page'] : $default_page;
if (!in_array($page, $allowed_pages)) {
    $page = $default_page;
}

// Ajax isteği ise sadece içerik dosyasını döndür
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    $content_file = "../" . $page . ".php";
    if (file_exists($content_file)) {
        include $content_file;
    } else {
        echo "<p>İçerik yüklenemedi.</p>";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>MK - Oyuncu Paneli</title>
    <style>
        /* Aynı önceki css yapısı */
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212;
            color: #e0e0e0;
        }
        header, footer {
            background-color: #1e1e2f;
            text-align: center;
            padding: 15px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.7);
        }
        header h1 {
            margin: 0;
            font-size: 1.8rem;
            color: #f39c12;
            letter-spacing: 1.5px;
        }
        header p {
            margin: 5px 0 0;
            font-style: italic;
            color: #aaa;
            font-weight: 600;
        }
        .container {
            display: flex;
            height: calc(100vh - 110px);
            overflow: hidden;
        }
        nav {
            width: 240px;
            background-color: #252538;
            padding: 20px 15px;
            box-sizing: border-box;
            box-shadow: inset -1px 0 5px rgba(0,0,0,0.5);
        }
        nav strong {
            display: block;
            font-size: 1.1rem;
            color: #f39c12;
            margin-bottom: 25px;
            user-select: none;
        }
        nav a {
            display: block;
            color: #ccc;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.25s ease, color 0.25s ease;
            box-shadow: inset 0 0 0 0 transparent;
            cursor: pointer;
        }
        nav a.active, nav a:hover {
            background-color: #f39c12;
            color: #121212;
            box-shadow: inset 0 0 10px #fff3b0;
        }
        main {
            flex: 1;
            padding: 30px 40px;
            background-color: #181822;
            overflow-y: auto;
            box-sizing: border-box;
            position: relative;
        }
        footer small {
            color: #888;
            font-weight: 400;
        }
        /* Loader spinner */
        #loader {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 6px solid #f3f3f3;
            border-top: 6px solid #f39c12;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            display: none;
            z-index: 100;
        }
        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                height: auto;
            }
            nav {
                width: 100%;
                box-shadow: none;
                padding: 15px;
                display: flex;
                overflow-x: auto;
            }
            nav strong {
                display: none;
            }
            nav a {
                flex: 1 0 auto;
                margin: 0 10px 0 0;
                padding: 10px;
                text-align: center;
            }
            main {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>MK: Karanlığın Efsanesi</h1>
        <p>Üç grup. Tek kader. Senin yolun hangisi?</p>
    </header>

    <div class="container" role="main">
        <nav aria-label="Ana menü">
            <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
            <a data-page="dashboard/home" class="nav-link active">Anasayfa</a>
            <a data-page="character/character" class="nav-link">Karakter</a>
            <a data-page="missions/missions" class="nav-link">Görevler</a>
            <a data-page="map/map" class="nav-link">Harita</a>
            <a data-page="market/market" class="nav-link">Market</a>
            <a data-page="auction/auction" class="nav-link">Müzayede</a>
            <a href="../logout.php" style="margin-top: 20px; color: #e74c3c;">Çıkış Yap</a>
        </nav>

        <main tabindex="0" id="content-area">
            <div id="loader"></div>
            <?php
            // Sayfa ilk yüklendiğinde içerik ekle
            $content_file = "../" . $default_page . ".php";
            if (file_exists($content_file)) {
                include $content_file;
            } else {
                echo "<h2>Sayfa bulunamadı.</h2>";
            }
            ?>
        </main>
    </div>

    <footer>
        <small>© 2025 MK Oyunu - Tüm hakları saklıdır.</small>
    </footer>

    <script>
        const navLinks = document.querySelectorAll('nav a.nav-link');
        const contentArea = document.getElementById('content-area');
        const loader = document.getElementById('loader');

        function setActiveLink(page) {
            navLinks.forEach(link => {
                if (link.dataset.page === page) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }

        function loadPage(page) {
            loader.style.display = 'block';
            fetch('?page=' + page, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('İçerik yüklenirken hata oluştu.');
                return response.text();
            })
            .then(html => {
                contentArea.innerHTML = html;
                loader.style.display = 'none';
                setActiveLink(page);
                // URL'yi güncelle (sayfa yenilemeden)
                history.pushState({page: page}, '', '?page=' + page);
                // İçerik odaklama (accessibility için)
                contentArea.focus();
            })
            .catch(error => {
                contentArea.innerHTML = '<p style="color: #e74c3c;">' + error.message + '</p>';
                loader.style.display = 'none';
            });
        }

        navLinks.forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const page = link.dataset.page;
                if (!link.classList.contains('active')) {
                    loadPage(page);
                }
            });
        });

        // Tarayıcı geri/ileri düğmeleri için sayfa durumunu yönet
        window.addEventListener('popstate', event => {
            const page = (event.state && event.state.page) ? event.state.page : '<?php echo $default_page; ?>';
            loadPage(page);
        });
    </script>
</body>
</html>
