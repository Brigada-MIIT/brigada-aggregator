<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo (!empty($title)) ? $title : "Бригада"; ?></title>
    <link rel="SHORTCUT ICON" href="/assets/img/logo_brigada.ico" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/front/main.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-md navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="/assets/img/logo_brigada_min.png" width="32" height="32" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item<?php echo (empty($page)) ? " active" : "" ?>">
                        <a class="nav-link" href="/">Главная</a>
                    </li>
                    <li class="nav-item<?php echo (!empty($page)) ? ($page == "instruction" ? " active" : "") : "" ?>">
                        <a class="nav-link" href="/instruction">Инструкция</a>
                    </li>
                </ul>
                <ul class="navbar-nav justify-content-end">
                    <?php if(!$system->auth()) echo '
                            <hr class="d-sm-none">
                            <li class="nav-item">
                                <a class="nav-link" href="/app/auth">Войти</a>
                            </li>
                            <li class="d-none d-md-block" style="color: rgba(0, 0, 0, .5);">|</li>
                            <li class="nav-item">
                                <a class="nav-link" href="/app/register">Регистрация</a>
                            </li>';
                    if($system->auth()) echo '
                            <hr class="d-sm-none">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16" style="top: 0.13em;position: relative"><path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/><path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/></svg>
                                    '. $_user['surname'] .'
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="left: auto; right: 0">
                                    <a class="dropdown-item" href="/profile/'.$system_user_id.'">Профиль</a>
                                    <a class="dropdown-item" href="/profile/uploads">Мои загрузки</a>';
                    if($system->haveUserPermission($system_user_id, "CREATE_UPLOADS") && $system->userinfo($system_user_id)['ban_upload'] == 0) echo '
                                    <a class="dropdown-item" href="/uploads/create">Создать загрузку</a>';
                    if($system->haveUserPermission($system_user_id, "MANAGE_USERS") || $system->haveUserPermission($system_user_id, "MANAGE_SETTINGS")) echo '
                                    <hr style="margin-top: 5px;margin-bottom: 4px;">';
                    if($system->haveUserPermission($system_user_id, "MANAGE_USERS")) echo '
                                    <a class="dropdown-item" href="/app/users">Пользователи</a>';
                    if($system->haveUserPermission($system_user_id, "MANAGE_SETTINGS")) echo '
                                    <a class="dropdown-item" href="/app/settings">Настройки</a>';
                    if($system->auth()) echo '
                                    <hr style="margin-top: 5px;margin-bottom: 4px;">
                                    <a class="dropdown-item" href="/logout">Выйти</a>
                                </div>
                            </li>'; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<?php include $content; ?>

</body>
</html>