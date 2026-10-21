<?php
$pageTitle = 'Услуги - Салон красоты Элегант';
require_once __DIR__ . '/includes/header.php';

$stmt = $pdo->query("SELECT * FROM services ORDER BY category, title");
$allServices = $stmt->fetchAll();

$grouped = [];
foreach ($allServices as $s) {
    $grouped[$s['category']][] = $s;
}
?>

<div class="page-heading">
    <h1>Услуги салона красоты</h1>
    <p>Широкий спектр процедур эстетической медицины, парикмахерского зала, ногтевого сервиса и массажа.</p>
</div>

<?php foreach ($grouped as $category => $items): ?>
    <section class="section category-block">
        <h2 class="category-title"><?= e($category) ?></h2>
        <div class="grid grid-2">
            <?php foreach ($items as $s): ?>
                <div class="card service-card-detailed">
                    <div class="service-info">
                        <h3><?= e($s['title']) ?></h3>
                        <p><?= e($s['description']) ?></p>
                        <span class="duration">Длительность: <?= (int)$s['duration_min'] ?> мин.</span>
                    </div>
                    <div class="service-meta">
                        <span class="price-value"><?= formatPrice($s['price']) ?></span>
                        <a href="/booking.php?service_id=<?= (int)$s['id'] ?>" class="btn btn-small">Записаться</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endforeach; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
