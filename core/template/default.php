<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo (!empty($title)) ? $title : "Бригада"; ?></title>
    <link rel="icon" href="/assets/img/front/mini_logo.png" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/front/main.css">
</head>
<body>
<header class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <div class="navbar-brand-container">
            <a class="navbar-brand" href="#">
                <img src="/assets/img/front/logo_brigada.png" alt="Бригада" width="30" height="30" class="d-inline-block align-top">
                Brigada
            </a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav shifted-left"> <!-- Добавляем класс для сдвига -->
                <li class="nav-item">
                    <a class="nav-link" href="#">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="News.html">Новости</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="help.html">Помощь</a>
                </li>
            </ul>
        </div>
        <div class="navbar-text-container">
            <div class="navbar-text d-flex align-items-center">
                <span class="mr-2">Время по МСК: <span id="moscow-time"></span></span>
                <i class="fas fa-globe mr-3"></i>
            </div>
        </div>
    </div>
</header>

<?php include $content; ?>

<footer class="bg-light text-center text-lg-start mt-4">
    <div class="container p-4">
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase">Ссылки</h5>
                <ul class="list-unstyled">
                    <li><a href="Team.html" class="text-primary">Наша команда</a></li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase">Социальные сети</h5>
                <ul class="list-unstyled">
                    <li><a href="https://web.telegram.org/k/#-4047480590" class="text-primary"><i class="fab fa-telegram"></i> Telegram</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        © 2024 Copyright:
        <a class="text-dark" href="#">Бригада</a>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function updateTime() {
        const moscowTime = new Date().toLocaleString("ru-RU", { timeZone: "Europe/Moscow", hour: '2-digit', minute: '2-digit' });
        document.getElementById("moscow-time").textContent = moscowTime;
    }
    setInterval(updateTime, 1000);
    updateTime();

    $(function () {
        $('#productCategory').change(function() {
            const selectedCategory = $(this).val();
            if (selectedCategory === 'kitchen') {
                window.location.href = 'kitchen_appliances.html';
            }
            if (selectedCategory === 'electronics') {
                window.location.href = 'electronics.html';
            }
        });
    });
</script>
</body>
</html>