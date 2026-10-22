<?php
$pageTitle = 'Карта сайта - Салон красоты Элегант';
require_once __DIR__ . '/includes/header.php';

$stmtCats = $pdo->query("SELECT DISTINCT category FROM services ORDER BY category");
$categories = $stmtCats->fetchAll();
?>

<div class="page-heading">
    <h1>Карта сайта</h1>
    <p>Полная иерархическая структура разделов и страниц ресурса.</p>
</div>

<div class="card sitemap-card">
    <ul class="sitemap-tree">
        <li><a href="/index.php">Главная страница</a></li>
        <li>
            <a href="/services.php">Каталог услуг</a>
            <ul>
                <?php foreach ($categories as $cat): ?>
                    <li><?= e($cat['category']) ?></li>
                <?php endforeach; ?>
            </ul>
        </li>
        <li><a href="/masters.php">Наши мастера</a></li>
        <li><a href="/prices.php">Прайс-лист</a></li>
        <li><a href="/news.php">Новости и акции</a></li>
        <li><a href="/reviews.php">Отзывы посетителей</a></li>
        <li><a href="/booking.php">Онлайн-запись</a></li>
        <li><a href="/contacts.php">Контакты и адрес</a></li>
        <li><a href="/search.php">Поиск по сайту</a></li>
        <li><a href="/login.php">Авторизация пользователя</a></li>
        <li><a href="/register.php">Регистрация нового пользователя</a></li>
        <li><a href="/admin/index.php">Панель управления (для администратора)</a></li>
    </ul>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
