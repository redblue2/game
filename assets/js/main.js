document.addEventListener('DOMContentLoaded', function() {
    const mainContent = document.getElementById('main-content');
    const menuLinks = document.querySelectorAll('.menu ul li a');

    // İlk açılışta karakter ekranını yükle
    loadPage('character');

    menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = this.getAttribute('data-page');
            loadPage(page);
        });
    });

    function loadPage(page) {
        mainContent.innerHTML = '<p>Yükleniyor...</p>';

        fetch(`modules/ajax_content_loader.php?page=${page}`)
            .then(response => response.text())
            .then(html => {
                mainContent.innerHTML = html;
            })
            .catch(() => {
                mainContent.innerHTML = '<p>Sayfa yüklenirken hata oluştu.</p>';
            });
    }
});
