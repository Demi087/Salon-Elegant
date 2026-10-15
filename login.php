<?php
$pageTitle = 'Вход в личный кабинет - Салон красоты Элегант';
require_once __DIR__ . '/includes/header.php';

if (isLoggedIn()) {
    redirect('/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Пожалуйста, введите имя пользователя и пароль.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            redirect($user['role'] === 'admin' ? '/admin/index.php' : '/index.php');
        } else {
            $error = 'Неверное имя пользователя или пароль.';
        }
    }
}
?>

<div class="page-heading">
    <h1>Авторизация пользователя</h1>
    <p>Войдите в учетную запись для управления записями и оставления отзывов.</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>

<div class="card auth-card">
    <form method="post" class="form-standard">
        <label>Имя пользователя (логин)
            <input type="text" name="username" required placeholder="Введите логин">
        </label>
        <label>Пароль
            <input type="password" name="password" required placeholder="Введите пароль">
        </label>
        <button type="submit" class="btn btn-large btn-block">Войти в систему</button>
    </form>

    <div class="auth-helper">
        <p>Тестовые данные для входа:</p>
        <p>Администратор: <strong>admin</strong> / пароль: <strong>admin123</strong></p>
        <p>Пользователь: <strong>user</strong> / пароль: <strong>user123</strong></p>
        <p>Еще нет аккаунта? <a href="/register.php">Зарегистрироваться</a></p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
