<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo (!empty($title)) ? $title : "Бригада | Регистрация"; ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/front/register.css" rel="stylesheet">
    <link rel="SHORTCUT ICON" href="/assets/img/logo_brigada.ico" type="image/x-icon">
    <script src="/assets/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="/assets/js/sweetalert2.js"></script>
</head>
<body class="d-flex align-items-center justify-content-center" style="background-color: rgb(237, 243, 249); height: 100vh;">
<main class="form-signin d-flex align-items-center">
    <div class="color-strip"></div>
    <div class="logo-container">
        <span class="logo">
             <img src="/assets/img/front/logo_brigada.png" alt="Brigada">
        </span>
    </div>
    <div class="vertical-line"></div>
    <div class="flex-grow-1" id="registrationForm">
        <h1 class="h3 mb-3 fw-normal text-center">Регистрация</h1>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" placeholder="Введите Email..." required>
        </div>
        <div class="form-group">
            <label for="password-field">Пароль:</label>
            <input type="password" class="form-control" id="password-field" placeholder="Веедите пароль..." required>
        </div>
        <div class="form-group">
            <label for="password-repeat-field">Подтвердите пароль</label>
            <input type="password" class="form-control" id="password-repeat-field" placeholder="Подтвердите пароль..." required>
        </div>
        <div class="form-group">
            <label for="surname">Фамилия:</label>
            <input type="text" class="form-control" id="surname" placeholder="Введите фамилию..." required>
        </div>
        <div class="form-group">
            <label for="lastname">Имя:</label>
            <input type="text" class="form-control" id="lastname" placeholder="Введите имя..." required>
        </div>
        <!--<div class="form-group">
            <label for="patronymic">Отчество (необязательно):</label>
            <input type="text" class="form-control" id="patronymic" placeholder="Введите отчество...">
        </div>-->

        <button class="btn btn-primary w-100 py-2" type="submit" onclick="register()">Зарегистрироваться</button>

        <div class="text-center mt-3">
            <span>Уже есть учётная запись? <a href="/app/auth">Войти</a></span>
        </div>
    </div>
</main>
</body>
</html>
<script>
    function register() {
        let patronymic = ""
        if ($("#patronymic").val())
            patronymic = $("#patronymic").val().trim()
        $.ajax({
            type: 'POST',
            url: '/api/register',
            data: 'email='+$("#email").val().trim()+'&password='+$("#password-field").val()+'&password_repeat='+$("#password-repeat-field").val()+'&lastname='+$("#lastname").val().trim()+'&surname='+$("#surname").val().trim()+'&patronymic='+patronymic,
            success: async function(data) {
                var res = $.parseJSON(data);
                if (res.result == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Успешная регистрация',
                        text: 'На указанный Email-адрес было отправлено письмо с ссылкой для подтверждения вашего аккаунта. Если письмо не пришло, пожалуйста, проверьте папку «Спам»',
                        footer: '<a href="<?php echo $settings['link_to_admin']; ?>">Возникли вопросы?</a>'
                    }).then((result) => {
                        location.replace("/app/auth");
                    });
                } else if (res.result == 2) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Указан неверный Email-адрес',
                        text: 'Укажите, пожалуйста, правильный Email-адрес',
                        footer: '<a href="<?php echo $settings['link_to_admin']; ?>">Возникли вопросы?</a>'
                    });
                } else if (res.result == 3) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Пароли не совпадают',
                        text: 'Перепроверьте, пожалуйста, совпадение паролей в полях',
                        footer: '<a href="<?php echo $settings['link_to_admin']; ?>">Возникли вопросы?</a>'
                    });
                } else if (res.result == 4) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Пароль меньше 6-ти символов',
                        text: 'Укажите, пожалуйста, пароль не менее 6-ти символов',
                        footer: '<a href="<?php echo $settings['link_to_admin'];n ?>">Возникли вопросы?</a>'
                    });
                } else if (res.result == 5) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Не заполнены поля фамилия/имя',
                        text: 'Пожалуйста, заполните следующие поля: фамилия, имя, отчество',
                        footer: '<a href="<?php echo $settings['link_to_admin']; ?>">Возникли вопросы?</a>'
                    });
                } else if (res.result == 6) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Пользователь уже существует',
                        text: 'Пользователь с указанным Email-адресом уже существует',
                        footer: '<a href="/password/recovery">Забыли пароль?</a>'
                    });
                } else if (res.result == 7) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ошибка в заполнении полей',
                        text: 'В полях ФИО слишком много символов',
                        footer: '<a href="<?php echo $settings['link_to_admin']; ?>">Возникли вопросы?</a>'
                    });
                } else if (res.result == 8) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ошибка в заполнении полей',
                        text: 'В полях ФИО слишком много пробелов',
                        footer: '<a href="<?php echo $settings['link_to_admin']; ?>">Возникли вопросы?</a>'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Произошла неизвестная ошибка!',
                        footer: '<a href="<?php echo $settings['link_to_admin']; ?>">Возникли вопросы?</a>'
                    });
                }
            }
        });
    }

    document.addEventListener('keypress', function(event) {
        if(arguments[0].code == "Enter" || arguments[0].code == "NumpadEnter") {
            if(!Swal.isVisible()) register();
        }
    });

    let show_pswd = false;

    $('span#btn-pswd').click(function() {
        if(!show_pswd) {
            $('#password-field').attr('type', 'text');
            $('#password-repeat-field').attr('type', 'text');
        }
        else {
            $('#password-field').attr('type', 'password');
            $('#password-repeat-field').attr('type', 'password');
        }
        $('span#btn-pswd').toggleClass("fa-eye");
        $('span#btn-pswd').toggleClass("fa-eye-slash");
        show_pswd = !show_pswd
    });
</script>
