<?php
$pageTitle = 'Поиск по сайту - Салон красоты Элегант';
require_once __DIR__ . '/includes/header.php';

$query = trim($_GET['q'] ?? '');
$results = [];

if (!empty($query)) {
    $param = '%' . $query . '%';

    $stmt1 = $pdo->prepare("SELECT 'Услуга' AS entity_type, id, title AS title, description AS text_content, '/booking.php?service_id=' AS link_prefix FROM services WHERE title LIKE ? OR description LIKE ?");
    $stmt1->execute([$param, $param]);
    $res1 = $stmt1->fetchAll();

    $stmt2 = $pdo->prepare("SELECT 'Мастер' AS entity_type, id, name AS title, specialization AS text_content, '/booking.php?master_id=' AS link_prefix FROM masters WHERE name LIKE ? OR specialization LIKE ? OR description LIKE ?");
    $stmt2->execute([$param, $param, $param]);
    $res2 = $stmt2->fetchAll();

    $stmt3 = $pdo->prepare("SELECT 'Новость' AS entity_type, id, title AS title, summary AS text_content, '/news.php?id=' AS link_prefix FROM news WHERE title LIKE ? OR content LIKE ?");
    $stmt3->execute([$param, $param]);
    $res3 = $stmt3->fetchAll();

    $results = array_merge($res1, $res2, $res3);
}
?>

<div class="page-heading">
    <h1>Поиск по сайту</h1>
    <p>Поиск услуг, специалистов и актуальных публикаций салона по ключевым словам.</p>
</div>

<form method="get" class="search-page-box card">
    <input type="text" name="q" value="<?= e($query) ?>" placeholder="Введите название процедуры, мастера или ключевое слово..." required>
    <button type="submit" class="btn">Найти</button>
</form>

<?php if (!empty($query)): ?>
    <div class="search-results-info">
        Результаты по запросу "<strong><?= e($query) ?></strong>": найдено <?= count($results) ?> совпадений.
    </div>

    <?php if (empty($results)): ?>
        <div class="alert alert-error">К сожалению, по данному запросу ничего не найдено. Попробуйте изменить формулировку.</div>
    <?php else: ?>
        <div class="grid grid-2">
            <?php foreach ($results as $item): ?>
                <div class="card result-card">
                    <span class="category-tag"><?= e($item['entity_type']) ?></span>
                    <h3><?= e($item['title']) ?></h3>
                    <p><?= e($item['text_content']) ?></p>
                    <a href="<?= e($item['link_prefix'] . $item['id']) ?>" class="btn btn-small">Перейти</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
