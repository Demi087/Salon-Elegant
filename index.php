<?php
$pageTitle = 'Салон красоты Элегант - Главная';
require_once __DIR__ . '/includes/header.php';

$stmtServices = $pdo->query("SELECT * FROM services ORDER BY id ASC LIMIT 6");
$services = $stmtServices->fetchAll();

$stmtNews = $pdo->query("SELECT * FROM news ORDER BY published_at DESC LIMIT 3");
$news = $stmtNews->fetchAll();

$stmtMasters = $pdo->query("SELECT * FROM masters ORDER BY experience DESC LIMIT 4");
$masters = $stmtMasters->fetchAll();
?>

<section class="hero">
    <div class="hero-content">
        <span class="hero-badge">Салон красоты Элегант</span>
        <h1>Искусство гармонии и безупречного стиля</h1>
        <p>Индивидуальные программы ухода, премиальные косметические бренды и команда сертифицированных специалистов.</p>
        <div class="hero-actions">
            <a href="<?= e(siteUrl('booking.php')) ?>" class="btn btn-large">Записаться на прием</a>
            <a href="<?= e(siteUrl('services.php')) ?>" class="btn btn-large btn-outline">Каталог услуг</a>
        </div>
    </div>
</section>

<section class="section client-privileges-banner">
    <div class="card client-privileges-card">
        <h2>Клубные привилегии салона: Гость vs Авторизованный клиент</h2>
        <p>Для зарегистрированных клиентов действует постоянная скидка 10% на все услуги, 5% кэшбэк бонусами на счет и доступ к Личному кабинету с возможностью отмены записи онлайн в 1 клик.</p>
        <div class="privileges-actions">
            <a href="<?= e(siteUrl('register.php')) ?>" class="btn btn-small">Зарегистрироваться (-10% скидка)</a>
            <a href="<?= e(siteUrl('login.php')) ?>" class="btn btn-outline btn-small">Войти в кабинет</a>
        </div>
    </div>
</section>

<section class="banner-section">
    <div class="banner-card">
        <div class="banner-title">Специальное предложение месяца</div>
        <div class="banner-desc">Скидка 20% на первое посещение любого специалиста при онлайн-записи через сайт.</div>
        <a href="<?= e(siteUrl('booking.php')) ?>" class="btn btn-small">Получить скидку</a>
    </div>
</section>

<section class="section">
    <div class="section-header">
        <h2>Популярные услуги</h2>
        <a href="<?= e(siteUrl('services.php')) ?>" class="link-more">Весь каталог услуг &rarr;</a>
    </div>
    <div class="grid grid-3">
        <?php foreach ($services as $s): ?>
            <div class="card service-card">
                <span class="category-tag"><?= e($s['category']) ?></span>
                <h3><?= e($s['title']) ?></h3>
                <p><?= e($s['description']) ?></p>
                <div class="card-footer">
                    <span class="price"><?= formatPrice($s['price']) ?></span>
                    <a href="<?= e(siteUrl('booking.php')) ?>?service_id=<?= (int)$s['id'] ?>" class="btn btn-small">Записаться</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="section-header">
        <h2>Ведущие специалисты</h2>
        <a href="<?= e(siteUrl('masters.php')) ?>" class="link-more">Все мастера &rarr;</a>
    </div>
    <div class="grid grid-4">
        <?php foreach ($masters as $m): ?>
            <?php
                $nameParts = preg_split('/\s+/', trim((string)$m['name']));
                $initials = '';
                foreach (array_slice($nameParts, 0, 2) as $part) {
                    $initials .= mb_substr($part, 0, 1, 'UTF-8');
                }
            ?>
            <div class="card master-card">
                <div class="master-avatar" aria-label="Мастер <?= e($m['name']) ?>"><span><?= e($initials) ?></span></div>
                <h3><?= e($m['name']) ?></h3>
                <p class="specialization"><?= e($m['specialization']) ?></p>
                <p class="experience">Стаж работы: <?= (int)$m['experience'] ?> лет</p>
                <p><?= e($m['description']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="section-header">
        <h2>Новости и события</h2>
        <a href="<?= e(siteUrl('news.php')) ?>" class="link-more">Все публикации &rarr;</a>
    </div>
    <div class="grid grid-3">
        <?php foreach ($news as $n): ?>
            <div class="card news-card">
                <span class="date-badge"><?= formatDate($n['published_at']) ?></span>
                <h3><?= e($n['title']) ?></h3>
                <p><?= e($n['summary']) ?></p>
                <a href="<?= e(siteUrl('news.php')) ?>?id=<?= (int)$n['id'] ?>" class="btn-text">Подробнее &rarr;</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="cta-banner">
    <h2>Запишитесь на удобное время онлайн</h2>
    <p>Администратор свяжется с вами в течение 10 минут для согласования деталей визита.</p>
    <a href="<?= e(siteUrl('booking.php')) ?>" class="btn btn-large">Перейти к записи</a>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
