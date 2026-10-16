<?php
$pageTitle = 'Мастера - Салон красоты Элегант';
require_once __DIR__ . '/includes/header.php';

$stmt = $pdo->query("SELECT * FROM masters ORDER BY experience DESC");
$masters = $stmt->fetchAll();
?>

<div class="page-heading">
    <h1>Команда наших специалистов</h1>
    <p>Каждый мастер салона имеет профильное образование, медицинские и профессиональные сертификаты и регулярно повышает квалификацию.</p>
</div>

<div class="grid grid-3">
    <?php foreach ($masters as $m): ?>
        <?php
            $nameParts = preg_split('/\s+/', trim((string)$m['name']));
            $initials = '';
            foreach (array_slice($nameParts, 0, 2) as $part) {
                $initials .= mb_substr($part, 0, 1, 'UTF-8');
            }
        ?>
        <div class="card master-profile-card">
            <div class="master-avatar large" aria-label="Мастер <?= e($m['name']) ?>"><span><?= e($initials) ?></span></div>
            <h3><?= e($m['name']) ?></h3>
            <p class="specialization"><?= e($m['specialization']) ?></p>
            <p class="experience-badge">Практический опыт: <?= (int)$m['experience'] ?> лет</p>
            <p class="description"><?= e($m['description']) ?></p>
            <a href="/booking.php?master_id=<?= (int)$m['id'] ?>" class="btn btn-small btn-block">Записаться к мастеру</a>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>