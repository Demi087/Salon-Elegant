<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isAdmin()) {
    redirect('/login.php');
}

$pageTitle = 'Панель управления администратора';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_confirm'])) {
        $id = (int)$_POST['booking_id'];
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ?");
        $stmt->execute([$id]);
        redirect('/admin/index.php');
    } elseif (isset($_POST['action_cancel'])) {
        $id = (int)$_POST['booking_id'];
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'canceled' WHERE id = ?");
        $stmt->execute([$id]);
        redirect('/admin/index.php');
    } elseif (isset($_POST['add_news'])) {
        $category = trim($_POST['category'] ?? 'Новости салона');
        $title = trim($_POST['title'] ?? '');
        $summary = trim($_POST['summary'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if (!empty($title) && !empty($content)) {
            $stmt = $pdo->prepare("INSERT INTO news (category, title, summary, content) VALUES (?, ?, ?, ?)");
            $stmt->execute([$category, $title, $summary, $content]);
            redirect('/admin/index.php');
        }
    }
}

$stmtBookings = $pdo->query("
    SELECT b.*, s.title AS service_title, m.name AS master_name
    FROM bookings b
    LEFT JOIN services s ON b.service_id = s.id
    LEFT JOIN masters m ON b.master_id = m.id
    ORDER BY b.booking_date DESC
");
$bookings = $stmtBookings->fetchAll();

$stmtUsers = $pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY id ASC");
$users = $stmtUsers->fetchAll();

$stmtMessages = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 20");
$messages = $stmtMessages->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="container header-inner">
        <a href="/index.php" class="logo">Салон Элегант - Панель управления</a>
        <div class="header-actions">
            <a href="/index.php" class="btn btn-outline">Перейти на сайт</a>
            <a href="/logout.php" class="btn">Выход</a>
        </div>
    </div>
</header>

<main class="container">
    <div class="page-heading">
        <h1>Панель администратора</h1>
        <p>Управление заявками на онлайн-запись, входящими сообщениями, публикациями и пользователями.</p>
    </div>

    <section class="section">
        <h2>Заявки клиентов на онлайн-запись</h2>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>ФИО Клиента</th>
                        <th>Телефон</th>
                        <th>Услуга</th>
                        <th>Мастер</th>
                        <th>Дата и время</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                        <tr>
                            <td><?= (int)$b['id'] ?></td>
                            <td><?= e($b['client_name']) ?></td>
                            <td><?= e($b['phone']) ?></td>
                            <td><?= e($b['service_title']) ?></td>
                            <td><?= e($b['master_name']) ?></td>
                            <td><?= formatDateTime($b['booking_date']) ?></td>
                            <td><span class="status-badge status-<?= e($b['status']) ?>"><?= e($b['status']) ?></span></td>
                            <td>
                                <?php if ($b['status'] === 'new'): ?>
                                    <form method="post" style="display:inline-block">
                                        <input type="hidden" name="booking_id" value="<?= (int)$b['id'] ?>">
                                        <button type="submit" name="action_confirm" class="btn btn-small">Подтвердить</button>
                                        <button type="submit" name="action_cancel" class="btn btn-small btn-danger">Отменить</button>
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
    </section>

    <section class="section">
        <h2>Опубликовать новость или акцию</h2>
        <div class="card">
            <form method="post" class="form-standard">
                <label>Категория публикации
                    <select name="category">
                        <option value="Новости салона">Новости салона</option>
                        <option value="Акции и скидки">Акции и скидки</option>
                        <option value="Мероприятия">Мероприятия</option>
                        <option value="Продукция">Продукция</option>
                    </select>
                </label>
                <label>Заголовок новости
                    <input type="text" name="title" required placeholder="Введите заголовок">
                </label>
                <label>Краткий анонс
                    <input type="text" name="summary" placeholder="Краткое описание для списка">
                </label>
                <label>Полный текст
                    <textarea name="content" rows="4" required placeholder="Текст новости..."></textarea>
                </label>
                <button type="submit" name="add_news" class="btn">Опубликовать</button>
            </form>
        </div>
    </section>

    <section class="section">
        <h2>Зарегистрированные пользователи</h2>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Логин</th>
                        <th>Email</th>
                        <th>Роль в системе</th>
                        <th>Дата создания</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= (int)$u['id'] ?></td>
                            <td><?= e($u['username']) ?></td>
                            <td><?= e($u['email']) ?></td>
                            <td><?= e($u['role']) ?></td>
                            <td><?= formatDate($u['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="container copyright">
        &copy; <?= date('Y') ?> Панель администратора салона Элегант.
    </div>
</footer>

</body>
</html>
