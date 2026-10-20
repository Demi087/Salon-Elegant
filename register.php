<?php
$pageTitle = 'Регистрация пользователя - Салон красоты Элегант';
require_once __DIR__ . '/includes/header.php';

if (isLoggedIn()) {
    redirect('/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (mb_strlen($username) < 3) {
        $error = 'Имя пользователя должно быть не короче 3 символов.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Указан некорректный адрес электронной почты.';
    } elseif (mb_strlen($password) < 6) {
        $error = 'Пароль должен содержать минимум 6 символов.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $error = 'Пользователь с таким именем или email уже зарегистрирован.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'user')");
            $insert->execute([$username, $hash, $email]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'user';
            redirect('/index.php');
        }
    }
}
?>

<div class="page-heading">
    <h1>Регистрация нового пользователя</h1>
    <p>Создайте учетную запись для удобного отслеживания визитов и персональных скидок.</p>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>

<div class="card auth-card">
    <form method="post" class="form-standard">
        <label>Имя пользователя (логин)
            <input type="text" name="username" required value="<?= e($_POST['username'] ?? '') ?>" placeholder="Придумайте логин">
        </label>
        <label>Адрес электронной почты
            <input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>" placeholder="user@example.ru">
        </label>
        <label>Пароль
            <input type="password" name="password" required minlength="6" placeholder="Минимум 6 символов">
        </label>
        <button type="submit" class="btn btn-large btn-block">Зарегистрироваться</button>
    </form>

    <div class="auth-helper">
        <p>Уже зарегистрированы? <a href="/login.php">Войти в учетную запись</a></p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
