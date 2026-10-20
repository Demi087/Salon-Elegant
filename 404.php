<?php
http_response_code(404);
$pageTitle = '404 - Страница не найдена';
require_once __DIR__ . '/includes/header.php';
?>

<div class="card error-page-card">
    <div class="error-code">404</div>
    <h1>Страница не найдена</h1>
    <p>Запрашиваемый адрес веб-страницы не существует либо был перемещен в другой раздел.</p>
    <div class="error-actions">
        <a href="/index.php" class="btn btn-large">На главную страницу</a>
        <a href="/sitemap.php" class="btn btn-large btn-outline">Перейти к карте сайта</a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
