<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

if (!isLoggedIn()) {
    redirect('/login.php');
}

$pageTitle = 'Личный кабинет клиента - Салон красоты Элегант';
$uid = currentUserId();
$uname = currentUsername();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_my_booking'])) {
    $bId = (int)$_POST['booking_id'];
    $check = $pdo->prepare("SELECT id FROM bookings WHERE id = ? AND (user_id = ? OR client_name = ?)");
    $check->execute([$bId, $uid, $uname]);
    if ($check->fetch()) {
        $stmtCancel = $pdo->prepare("UPDATE bookings SET status = 'canceled' WHERE id = ?");
        $stmtCancel->execute([$bId]);
        redirect('/profile.php');
    }
}

$stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmtUser->execute([$uid]);
$userData = $stmtUser->fetch();

$stmtBookings = $pdo->prepare("
    SELECT b.*, s.title AS service_title, s.price AS service_price, m.name AS master_name
    FROM bookings b
    LEFT JOIN services s ON b.service_id = s.id
    LEFT JOIN masters m ON b.master_id = m.id
    WHERE b.user_id = ? OR b.client_name = ?
    ORDER BY b.booking_date DESC
");
$stmtBookings->execute([$uid, $uname]);
$myBookings = $stmtBookings->fetchAll();

$stmtReviews = $pdo->prepare("SELECT * FROM reviews WHERE user_id = ? OR author_name = ? ORDER BY created_at DESC");
$stmtReviews->execute([$uid, $uname]);
$myReviews = $stmtReviews->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-heading">
    <h1>Личный кабинет постоянного клиента</h1>
    <p>Управление вашими записями, персональная накопительная скидка и история визитов.</p>
</div>

<div class="contacts-grid" style="margin-bottom: 30px;">
    <div class="card">
        <h3>Профиль клиента</h3>
        <p><strong>Имя пользователя:</strong> <?= e($userData['username'] ?? $uname) ?></p>
        <p><strong>Электронная почта:</strong> <?= e($userData['email'] ?? 'client@example.ru') ?></p>
        <p><strong>Дата регистрации:</strong> <?= formatDate($userData['created_at'] ?? date('Y-m-d')) ?></p>
        <p><strong>Статус:</strong> Постоянный клиент салона</p>
    </div>

    <div class="card" style="background: #fdfaf6; border-color: #ebd9c5;">
        <h3 style="color: var(--accent);">Программа лояльности</h3>
        <p style="font-size: 22px; font-weight: bold; color: var(--primary); margin: 10px 0;">Персональная скидка: 10%</p>
        <p>Скидка автоматически применяется при бронировании любых процедур через сайт.</p>
        <p><strong>Бонусный баланс:</strong> 1 250 бонусов (1 бонус = 1 рубль)</p>
        <a href="/booking.php" class="btn btn-small" style="margin-top: 10px;">Записаться со скидкой</a>
    </div>
</div>

<section class="section">
    <h2>Мои записи на процедуры</h2>
    <?php if (empty($myBookings)): ?>
        <p>У вас пока нет активных записей. <a href="/booking.php">Записаться на процедуру</a>.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Услуга</th>
                        <th>Мастер</th>
                        <th>Дата и время</th>
                        <th>Стоимость</th>
                        <th>Статус</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($myBookings as $mb): ?>
                        <?php
                            $origPrice = (float)($mb['service_price'] ?? 0);
                            $discPrice = round($origPrice * 0.9);
                        ?>
                        <tr>
                            <td><?= (int)$mb['id'] ?></td>
                            <td><strong><?= e($mb['service_title']) ?></strong></td>
                            <td><?= e($mb['master_name']) ?></td>
                            <td><?= formatDateTime($mb['booking_date']) ?></td>
                            <td>
                                <span style="text-decoration: line-through; color: #888; font-size: 12px;"><?= formatPrice($origPrice) ?></span><br>
                                <strong style="color: var(--primary);"><?= formatPrice($discPrice) ?> (-10%)</strong>
                            </td>
                            <td><span class="status-badge status-<?= e($mb['status']) ?>"><?= e($mb['status']) ?></span></td>
                            <td>
                                <?php if ($mb['status'] === 'new'): ?>
                                    <form method="post" style="display:inline">
                                        <input type="hidden" name="booking_id" value="<?= (int)$mb['id'] ?>">
                                        <button type="submit" name="cancel_my_booking" class="btn btn-small btn-danger">Отменить</button>
                                    </form>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<section class="section">
    <h2>Мои отзывы</h2>
    <?php if (empty($myReviews)): ?>
        <p>Вы пока не оставили отзывов. <a href="/reviews.php">Оставить отзыв</a>.</p>
    <?php else: ?>
        <div class="grid grid-2">
            <?php foreach ($myReviews as $mr): ?>
                <div class="card">
                    <div class="review-header">
                        <strong>Подтвержденный отзыв клиента</strong>
                        <span>Оценка: <?= (int)$mr['rating'] ?> из 5</span>
                    </div>
                    <p><?= nl2br(e($mr['text'])) ?></p>
                    <div class="review-date"><?= formatDate($mr['created_at']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
