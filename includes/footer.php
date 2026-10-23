</main>

<footer class="site-footer">
    <div class="container footer-inner">
        <div class="footer-col">
            <h4>Салон красоты Элегант</h4>
            <p>Профессиональные услуги эстетической косметологии, парикмахерского искусства и ногтевого сервиса с 2018 года.</p>
            <p>Лицензия на медицинскую косметологию № ЛО-77-01-019824</p>
        </div>
        <div class="footer-col">
            <h4>Контакты</h4>
            <p>г. Москва, Красная площадь, д. 3 (ГУМ)</p>
            <p>Телефон: +7 (495) 123-45-67</p>
            <p>Электронная почта: info@salon-elegant.ru</p>
            <p>График: ежедневно с 09:00 до 21:00</p>
        </div>
        <div class="footer-col">
            <h4>Разделы сайта</h4>
            <p><a href="<?= e(siteUrl('services.php')) ?>">Каталог услуг</a></p>
            <p><a href="<?= e(siteUrl('prices.php')) ?>">Прайс-лист</a></p>
            <p><a href="<?= e(siteUrl('booking.php')) ?>">Онлайн-запись</a></p>
            <p><a href="<?= e(siteUrl('sitemap.php')) ?>">Карта сайта</a></p>
            <p><a href="<?= e($toggleUrl) ?>"><?= e($ACCESSIBLE ? 'Обычная версия' : 'Версия для слабовидящих') ?></a></p>
        </div>
    </div>
    <div class="copyright">
        &copy; <?= date('Y') ?> Салон красоты Элегант. Все права защищены. Разработано в соответствии с ГОСТ Р 52872-2012 и ГОСТ 34.602-2020.
    </div>
</footer>

<script src="<?= e(siteUrl('assets/js/main.js')) ?>"></script>
</body>
</html>
