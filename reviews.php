<?php
$pageTitle = 'Отзывы клиентов - Салон красоты Элегант';
require_once __DIR__ . '/includes/header.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['author_name'] ?? '');
    $text = trim($_POST['text'] ?? '');
    $rating = (int)($_POST['rating'] ?? 5);

    if (empty($name) && isLoggedIn()) {
        $name = currentUsername();
    }

    if (empty($name) || empty($text) || $rating < 1 || $rating > 5) {
        $error = 'Пожалуйста, заполните имя и текст отзыва.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, author_name, text, rating) VALUES (?, ?, ?, ?)");
        $stmt->execute([currentUserId(), $name, $text, $rating]);
        $success = true;
    }
}

$stmt = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC");
$reviews = $stmt->fetchAll();
?>

<div class="page-heading">
    <h1>Отзывы клиентов</h1>
    <p>Нам важно мнение каждого посетителя. Оставьте свой комментарий о качестве обслуживания.</p>
</div>

<?php if ($success): ?>
    <div class="alert alert-success">Благодарим за ваш отзыв! Он успешно сохранен и опубликован.</div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>

<div class="reviews-layout">
    <div class="reviews-list">
        <?php foreach ($reviews as $r): ?>
            <div class="card review-card">
                <div class="review-header">
                    <strong><?= e($r['author_name']) ?></strong>
                    <span class="rating-display">Оценка: <?= (int)$r['rating'] ?> из 5</span>
                </div>
                <p><?= nl2br(e($r['text'])) ?></p>
                <div class="review-date"><?= formatDate($r['created_at']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="review-form-container">
        <form method="post" class="card form-card">
            <h3>Оставить отзыв</h3>
            <label>Ваше имя
                <input type="text" name="author_name" required value="<?= e(currentUsername()) ?>">
            </label>
            <label>Оценка качества
                <select name="rating">
                    <option value="5">5 - Отлично</option>
                    <option value="4">4 - Хорошо</option>
                    <option value="3">3 - Удовлетворительно</option>
                    <option value="2">2 - Плохо</option>
                    <option value="1">1 - Очень плохо</option>
                </select>
            </label>
            <label>Текст отзыва
                <textarea name="text" rows="4" required placeholder="Поделитесь вашими впечатлениями о визите..."></textarea>
            </label>
            <button type="submit" class="btn btn-block">Опубликовать отзыв</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
