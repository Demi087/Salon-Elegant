<?php
$pageTitle = 'Онлайн-запись - Салон красоты Элегант';
require_once __DIR__ . '/includes/header.php';

$stmtServices = $pdo->query("SELECT id, title, price, category FROM services ORDER BY category, title");
$services = $stmtServices->fetchAll();

$stmtMasters = $pdo->query("SELECT id, name, specialization FROM masters ORDER BY name");
$masters = $stmtMasters->fetchAll();

$presetServiceId = isset($_GET['service_id']) ? (int)$_GET['service_id'] : 0;
$presetMasterId = isset($_GET['master_id']) ? (int)$_GET['master_id'] : 0;

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientName = trim($_POST['client_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $serviceId = (int)($_POST['service_id'] ?? 0);
    $masterId = (int)($_POST['master_id'] ?? 0);
    $date = trim($_POST['booking_date'] ?? '');

    if (empty($clientName) || empty($phone) || $serviceId <= 0 || $masterId <= 0 || empty($date)) {
        $error = 'Пожалуйста, заполните все обязательные поля формы онлайн-записи.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, master_id, service_id, client_name, phone, booking_date, status) VALUES (?, ?, ?, ?, ?, ?, 'new')");
        $stmt->execute([currentUserId(), $masterId, $serviceId, $clientName, $phone, $date]);
        $success = true;
    }
}
?>

<div class="page-heading">
    <h1>Онлайн-запись на процедуры</h1>
    <p>Выберите подходящую услугу, мастера и дату визита. Наш специалист свяжется с вами для подтверждения.</p>
</div>

<?php if ($success): ?>
    <div class="alert alert-success">
        Ваша запись успешно зарегистрирована! Администратор свяжется с вами по указанному телефону для подтверждения даты и времени.
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>

<div class="card form-card-booking">
    <form method="post" class="form-standard">
        <label>Ваше имя и фамилия
            <input type="text" name="client_name" required value="<?= e(currentUsername()) ?>" placeholder="Елена Смирнова">
        </label>

        <label>Контактный телефон
            <input type="tel" name="phone" required placeholder="+7 (999) 123-45-67">
        </label>

        <label>Выберите услугу
            <select name="service_id" required>
                <option value="">-- Выберите услугу из перечня --</option>
                <?php foreach ($services as $s): ?>
                    <option value="<?= (int)$s['id'] ?>" <?= $presetServiceId === (int)$s['id'] ? 'selected' : '' ?>>
                        <?= e($s['category']) ?> - <?= e($s['title']) ?> (<?= formatPrice($s['price']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>Выберите мастера
            <select name="master_id" required>
                <option value="">-- Выберите мастера --</option>
                <?php foreach ($masters as $m): ?>
                    <option value="<?= (int)$m['id'] ?>" <?= $presetMasterId === (int)$m['id'] ? 'selected' : '' ?>>
                        <?= e($m['name']) ?> (<?= e($m['specialization']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>Желаемая дата и время посещения
            <input type="datetime-local" name="booking_date" required min="<?= date('Y-m-d\TH:i') ?>">
        </label>

        <button type="submit" class="btn btn-large btn-block">Подтвердить запись</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
