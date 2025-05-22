<?php
require_once __DIR__ . '/../../modules/db.php';
require_once __DIR__ . '/../../modules/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Kullanıcı bilgisi
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Ekipman bilgisi
$stmt = $pdo->prepare("SELECT * FROM user_equipment WHERE user_id = ?");
$stmt->execute([$user_id]);
$equipment = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);

// Envanter bilgisi
$stmt = $pdo->prepare("SELECT * FROM user_inventory WHERE user_id = ?");
$stmt->execute([$user_id]);
$inventory = $stmt->fetchAll();

$equipment_slots = [
    'helmet' => 'Kask',
    'armor' => 'Zırh',
    'weapon' => 'Silah',
    'boots' => 'Bot',
    'gloves' => 'Eldiven',
    'ring' => 'Yüzük'
];

$stats = [
    'strength' => 'Güç',
    'defense' => 'Savunma',
    'stamina' => 'Dayanıklılık',
    'agility' => 'Çeviklik',
    'wisdom' => 'Bilgelik',
    'luck' => 'Şans'
];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Karakter Ekranı</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
       .container > div {
           margin-bottom: 2.5rem;
       }

       .bg-gray-800 {
           padding: 2rem;
       }

       /* Ekipman slotlarını tek sütun ve sağa hizala */
       #equipment-slots {
           display: flex;
           flex-direction: column;
           align-items: flex-end;
           gap: 1.5rem;
       }

       #inventory {
           gap: 1.5rem;
       }

       .slot {
           width: 110px;
           height: 110px;
           background-color: #1F2937;
           background-size: cover;
           background-position: center;
           border: 2px dashed #4B5563;
           border-radius: 1rem;
           position: relative;
           transition: border-color 0.2s, box-shadow 0.2s;
           cursor: pointer;
       }

       .slot:hover {
           border-color: #3B82F6;
           box-shadow: 0 0 14px rgba(59, 130, 246, 0.7);
       }

       .tooltip {
           display: none;
           position: absolute;
           top: 50%;
           left: 105%;
           transform: translateY(-50%);
           max-width: 220px;
           background-color: rgba(17, 24, 39, 0.95);
           color: #F9FAFB;
           padding: 10px 14px;
           font-size: 15px;
           border-radius: 10px;
           z-index: 1000;
           box-shadow: 0 6px 14px rgba(0, 0, 0, 0.8);
           white-space: normal;
           line-height: 1.6;
           pointer-events: none;
       }

       .slot:hover .tooltip {
           display: block;
           animation: fadeIn 0.3s ease-in-out;
       }

       @keyframes fadeIn {
           from { opacity: 0; }
           to { opacity: 1; }
       }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen font-sans">
    <div class="container mx-auto p-6 space-y-8 max-w-5xl">
        <!-- ÜST BİLGİ -->
        <div class="bg-gray-800 rounded-xl p-6 flex items-center space-x-6 shadow-lg">
            <img src="images/avatar.png" class="w-24 h-24 rounded-full border-4 border-blue-600 shadow-md" alt="Avatar" />
            <div>
                <h1 class="text-3xl font-bold"><?= htmlspecialchars($user['username']) ?></h1>
                <p class="text-lg text-gray-300 mt-1">⭐ Seviye: <?= $user['level'] ?> | 🔰 Grup: <?= htmlspecialchars($user['group_name']) ?></p>
            </div>
        </div>

        <!-- İSTATİSTİKLER -->
        <section class="bg-gray-800 rounded-xl p-6 shadow">
            <h2 class="text-2xl font-bold mb-6">📊 İstatistikler</h2>
            <?php foreach ($stats as $key => $label): ?>
                <div class="flex justify-between items-center mb-3 bg-gray-700 p-3 rounded hover:bg-gray-600 transition">
                    <span class="text-lg"><?= $label ?>: <?= $user[$key] ?></span>
                    <button onclick="increaseStat('<?= $key ?>')" class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1.5 text-base rounded shadow">+</button>
                </div>
            <?php endforeach; ?>
        </section>

        <!-- EKİPMAN SLOT -->
        <section class="bg-gray-800 rounded-xl p-6 shadow">
            <h2 class="text-2xl font-bold mb-6">🛡️ Kuşanılan Ekipmanlar</h2>
            <div id="equipment-slots">
                <?php foreach ($equipment_slots as $slot => $label): 
                    $item = $equipment[$slot] ?? null;
                    $imagePath = $item ? "images/equipment/{$item['image']}" : "images/placeholders/empty.png";
                    if (!file_exists($imagePath)) $imagePath = "images/placeholders/empty.png";
                ?>
                    <div class="slot" data-slot="<?= $slot ?>" ondrop="drop(event)" ondragover="allowDrop(event)" style="background-image: url('<?= $imagePath ?>')">
                        <div class="tooltip">
                            <?php if ($item): ?>
                                <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                                Tür: <?= ucfirst(htmlspecialchars($item['type'])) ?><br>
                                Bonus: +<?= htmlspecialchars($item['bonus_value']) ?> <?= ucfirst(htmlspecialchars($item['bonus_type'])) ?><br>
                                Seviye: <?= htmlspecialchars($item['required_level']) ?><br>
                                Nadirlik: <?= ucfirst(htmlspecialchars($item['rarity'])) ?>
                            <?php else: ?>
                                <?= htmlspecialchars($label) ?> (boş)
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- ENVANTER -->
        <section class="bg-gray-800 rounded-xl p-6 shadow">
            <h2 class="text-2xl font-bold mb-6">🎒 Envanter</h2>
            <div id="inventory" class="grid grid-cols-8 gap-4">
                <?php foreach ($inventory as $item): 
                    $imagePath = "images/equipment/{$item['image']}";
                    if (!file_exists($imagePath)) $imagePath = "images/placeholders/empty.png";
                ?>
                    <div class="slot item" draggable="true" id="item-<?= $item['id'] ?>" ondragstart="drag(event)" style="background-image: url('<?= $imagePath ?>')">
                        <div class="tooltip">
                            <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                            Tür: <?= ucfirst(htmlspecialchars($item['type'])) ?><br>
                            Bonus: +<?= htmlspecialchars($item['bonus_value']) ?> <?= ucfirst(htmlspecialchars($item['bonus_type'])) ?><br>
                            Seviye: <?= htmlspecialchars($item['required_level']) ?><br>
                            Nadirlik: <?= ucfirst(htmlspecialchars($item['rarity'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <script>
        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev) {
            ev.dataTransfer.setData("item_id", ev.target.id);
        }

        function drop(ev) {
            ev.preventDefault();
            const itemId = ev.dataTransfer.getData("item_id").replace('item-', '');
            const slot = ev.currentTarget.dataset.slot;

            axios.post('equip_item.php', {
                item_id: itemId,
                slot: slot
            }).then(() => location.reload());
        }

        function increaseStat(stat) {
            axios.post('increase_stat.php', {
                stat: stat
            }).then(() => location.reload());
        }
    </script>
</body>
</html>
