<?php
$pageTitle = 'Новости - Салон красоты Элегант';
require_once __DIR__ . '/includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if (!$item) {
        redirect('/404.php');
    }
    ?>
    <article class="single-article">
        <a href="/news.php" class="link-back">&larr; Вернуться к списку новостей</a>
        <div class="article-meta">
            <span class="category-tag"><?= e($item['category']) ?></span>
            <span class="date"><?= formatDate($item['published_at']) ?></span>
        </div>
        <h1><?= e($item['title']) ?></h1>
        <div class="article-content">
            <p><?= nl2br(e($item['content'])) ?></p>
        </div>
        <div class="article-actions">
            <a href="/booking.php" class="btn">Онлайн-запись</a>
        </div>
    </article>
    <?php
} else {
    $stmt = $pdo->query("SELECT * FROM news ORDER BY published_at DESC");
    $items = $stmt->fetchAll();
    ?>
    <div class="page-heading">
        <h1>Новости и специальные предложения</h1>
        <p>Актуальные события салона, скидки, анонсы мастер-классов и публикации о бьюти-новинках.</p>
    </div>

    <div class="grid grid-2">
        <?php foreach ($items as $n): ?>
            <div class="card news-card-full">
                <div class="article-meta">
                    <span class="category-tag"><?= e($n['category']) ?></span>
                    <span class="date"><?= formatDate($n['published_at']) ?></span>
                </div>
                <h3><?= e($n['title']) ?></h3>
                <p><?= e($n['summary']) ?></p>
                <a href="/news.php?id=<?= (int)$n['id'] ?>" class="btn btn-small">Читать далее</a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}

require_once __DIR__ . '/includes/footer.php';
?>
