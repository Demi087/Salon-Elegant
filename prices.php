<?php
$pageTitle = 'Прайс-лист - Салон красоты Элегант';
require_once __DIR__ . '/includes/header.php';

$stmt = $pdo->query("SELECT * FROM services ORDER BY category ASC, price ASC");
$services = $stmt->fetchAll();
?>

<div class="page-heading">
    <h1>Стоимость услуг салона</h1>
    <p>Фиксированные прозрачные цены без скрытых доплат. Все расходные материалы премиум-класса включены в стоимость.</p>
</div>

<div class="table-responsive">
    <table class="data-table">
        <thead>
            <tr>
                <th>Категория направления</th>
                <th>Наименование услуги</th>
                <th>Длительность</th>
                <th>Стоимость</th>
                <th>Действие</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $s): ?>
                <tr>
                    <td><?= e($s['category']) ?></td>
                    <td><strong><?= e($s['title']) ?></strong></td>
                    <td><?= (int)$s['duration_min'] ?> мин.</td>
                    <td class="price-cell"><?= formatPrice($s['price']) ?></td>
                    <td>
                        <a href="/booking.php?service_id=<?= (int)$s['id'] ?>" class="btn btn-small">Запись</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
