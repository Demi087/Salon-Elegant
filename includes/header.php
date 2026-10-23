<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? 'Салон красоты Элегант';
$currentUrl = $_SERVER['REQUEST_URI'] ?? '/index.php';
$currentPath = parse_url($currentUrl, PHP_URL_PATH) ?: '/index.php';
$toggleUrl = $currentPath . '?accessibility=' . ($ACCESSIBLE ? 'off' : 'on') . '&return=' . urlencode($currentUrl);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= e(siteUrl('assets/css/style.css')) ?>">
    <?php if ($ACCESSIBLE): ?>
        <link rel="stylesheet" href="<?= e(siteUrl('assets/css/accessible.css')) ?>">
    <?php endif; ?>
</head>
<body class="<?= $ACCESSIBLE ? 'accessible-mode' : '' ?>">

<header class="site-header">
    <div class="container header-inner">
        <a href="<?= e(siteUrl('index.php')) ?>" class="logo">Салон Элегант</a>

        <nav class="main-nav">
            <a href="<?= e(siteUrl('index.php')) ?>">Главная</a>
            <a href="<?= e(siteUrl('services.php')) ?>">Услуги</a>
            <a href="<?= e(siteUrl('masters.php')) ?>">Мастера</a>
            <a href="<?= e(siteUrl('prices.php')) ?>">Цены</a>
            <a href="<?= e(siteUrl('news.php')) ?>">Новости</a>
            <a href="<?= e(siteUrl('reviews.php')) ?>">Отзывы</a>
            <a href="<?= e(siteUrl('contacts.php')) ?>">Контакты</a>
            <a href="<?= e(siteUrl('sitemap.php')) ?>">Карта сайта</a>
        </nav>

        <div class="header-actions">
            <form action="<?= e(siteUrl('search.php')) ?>" method="get" class="search-form">
                <input type="text" name="q" placeholder="Поиск по сайту..." required>
                <button type="submit">Найти</button>
            </form>

            <?php if (isLoggedIn()): ?>
                <span class="user-info">
                    Пользователь: <?= e(currentUsername()) ?>
                    <?php if (isAdmin()): ?>
                        <a href="<?= e(siteUrl('admin/index.php')) ?>" class="admin-link">Панель управления</a>
                    <?php endif; ?>
                </span>
                <a href="<?= e(siteUrl('logout.php')) ?>" class="btn btn-outline">Выход</a>
            <?php else: ?>
                <a href="<?= e(siteUrl('login.php')) ?>" class="btn btn-outline">Вход</a>
                <a href="<?= e(siteUrl('register.php')) ?>" class="btn">Регистрация</a>
            <?php endif; ?>

            <a href="<?= e($toggleUrl) ?>" class="btn-accessibility">
                <?= $ACCESSIBLE ? 'Обычная версия' : 'Версия для слабовидящих' ?>
            </a>
        </div>
    </div>
</header>

<main class="container">