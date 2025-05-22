<div class="stats-box">
    <h3>Temel Statlar</h3>
    <p>Kalan Puan: <span id="remaining-points"><?php echo $user['available_stat_points']; ?></span></p>
    <ul>
        <?php
        $stats = ['strength' => 'Güç', 'defense' => 'Savunma', 'stamina' => 'Dayanıklılık', 'agility' => 'Çeviklik', 'wisdom' => 'Bilgelik', 'luck' => 'Şans'];
        foreach ($stats as $key => $label) {
            echo "<li>$label: <span id='{$key}-val'>{$user[$key]}</span>";
            if ($user['available_stat_points'] > 0) {
                echo " <button class='add-btn' data-stat='{$key}'>+</button>";
            }
            echo "</li>";
        }
        ?>
    </ul>
</div>

<script>
document.querySelectorAll(".add-btn").forEach(btn => {
    btn.addEventListener("click", () => {
        const stat = btn.dataset.stat;
        fetch('increase_stat.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'stat=' + stat
        })
        .then(res => res.text())
        .then(data => {
            if (data === 'OK') {
                const valSpan = document.getElementById(stat + '-val');
                valSpan.textContent = parseInt(valSpan.textContent) + 1;
                const remaining = document.getElementById('remaining-points');
                remaining.textContent = parseInt(remaining.textContent) - 1;
                if (parseInt(remaining.textContent) <= 0) {
                    document.querySelectorAll('.add-btn').forEach(b => b.remove());
                }
            } else {
                alert('İşlem başarısız.');
            }
        });
    });
});
</script>
