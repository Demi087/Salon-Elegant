<?php
$pageTitle = 'Контакты - Салон красоты Элегант';
require_once __DIR__ . '/includes/header.php';
$mapAddress = rawurlencode('ГУМ, Красная площадь, 3, Москва');

$sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Пожалуйста, заполните обязательные поля.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $message]);
        $sent = true;
    }
}
?>

<div class="page-heading">
    <h1>Контакты и схема проезда</h1>
    <p>Мы находимся в самом центре города. Удобная парковка для гостей и шаговая доступность от метро.</p>
</div>

<?php if ($sent): ?>
    <div class="alert alert-success">Ваше сообщение успешно отправлено! Администратор свяжется с вами.</div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>

<div class="contacts-grid">
    <div class="contacts-info card">
        <h3>Контактная информация</h3>
        <p><strong>Адрес:</strong> г. Москва, Красная площадь, д. 3 (ГУМ)</p>
        <p><strong>Ориентир:</strong> рядом станции метро «Охотный Ряд», «Площадь Революции» и «Театральная»</p>
        <p><strong>Телефон:</strong> +7 (495) 123-45-67</p>
        <p><strong>Второй телефон:</strong> +7 (999) 765-43-21</p>
        <p><strong>Электронная почта:</strong> info@salon-elegant.ru</p>
        <p><strong>Режим работы:</strong></p>
        <ul>
            <li>Понедельник - Пятница: с 09:00 до 21:00</li>
            <li>Суббота - Воскресенье: с 10:00 до 20:00</li>
        </ul>
        <div class="public-transport">
            <strong>Как добраться на транспорте:</strong>
            <p>Вход в здание ГУМа со стороны Красной площади.</p>
        </div>
    </div>

    <div class="feedback-container card">
        <h3>Форма обратной связи</h3>
        <form method="post" class="form-standard">
            <label>Ваше имя
                <input type="text" name="name" required placeholder="Иван Иванов">
            </label>
            <label>Электронная почта
                <input type="email" name="email" required placeholder="example@mail.ru">
            </label>
            <label>Телефон для связи
                <input type="tel" name="phone" placeholder="+7 (999) 000-00-00">
            </label>
            <label>Текст сообщения или вопрос
                <textarea name="message" rows="4" required placeholder="Напишите ваш вопрос..."></textarea>
            </label>
            <button type="submit" class="btn btn-block">Отправить сообщение</button>
        </form>
    </div>
</div>

<div class="map-container card">
    <h3>Как нас найти</h3>
    <div class="map-frame-wrap">
        <iframe
            title="Карта проезда до салона красоты Элегант"
            src="https://www.google.com/maps?q=<?= e($mapAddress) ?>&output=embed"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            allowfullscreen>
        </iframe>
    </div>
    <p><a href="https://www.google.com/maps/search/?api=1&query=<?= e($mapAddress) ?>" target="_blank" rel="noopener">Открыть адрес в Google Maps</a></p>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
