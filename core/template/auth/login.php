<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бригада | Авторизация</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/front/auth.css" rel="stylesheet">
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
    <div class="flex-grow-1">
        <h1 class="h3 mb-3 fw-normal text-center">Авторизация</h1>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" placeholder="Введите Email...">
        </div>
        <div class="form-group">
            <label for="password">Пароль:</label>
            <input type="password" class="form-control" id="password" placeholder="Введите пароль...">
        </div>

        <div class="form-check mb-3 d-flex align-items-center">
            <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
            <label class="form-check-label" for="flexCheckDefault">Запомнить меня</label>
        </div>

        <button class="btn btn-primary w-100 py-2" type="submit" onclick="login()">Войти</button>

        <div class="text-center mt-3">
            <span>У Вас нет учетной записи? <a href="/app/register">Зарегистрироваться</a></span>
        </div>

        <div class="text-center mt-2">
            <a href="/password/recovery">Забыли пароль?</a>
        </div>
    </div>
</main>
</body>
</html>
<script>
    function login(code) {
        let a_code = ""
        if(code) {
            a_code = '&auth_code='+code
        }
        Swal.close();
        $.ajax({
            type: 'POST',
            url: '/api/login',
            data: 'email='+$("#email").val().trim()+'&password='+$("#password").val()+a_code,
            success: async function(data) {
            var res = $.parseJSON(data);
            if (res.result == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Неверный пароль!',
                    text: 'Проверьте правильность введённых данных.',
                    footer: '<a href="/password/recovery">Забыли пароль?</a>'
                });
            } else if (res.result == 1) {
                if(!res.text)
                    location.replace("/");
                else
                    location.replace(res.text);
            } else if (res.result == 4) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ваш аккаунт заблокирован!',
                    text: 'Обратитесь к администратору.',
                    footer: '<a href="<?php echo $settings['link_to_admin']; ?>">Возникли вопросы?</a>'
                });
            } else if (res.result == 100) {
                const { value: user_code } = await Swal.fire({
                    icon: 'warning',
                    input: 'text',
                    title: 'Двухфакторная аутентификация',
                    inputPlaceholder: 'Укажите 2FA код для авторизации...',
                    inputAttributes: {
                        'aria-label': '2FA'
                    },
                    showCancelButton: true,
                    confirmButtonColor: "#3fae3a",
                    confirmButtonText: "Принять",
                    cancelButtonColor: "#da2121",
                    cancelButtonText: "Отмена"
                })
                if(isNaN(user_code)) return;
                await login(user_code);
            } else if (res.result == 101) {
                Swal.fire({
                    icon: 'error',
                    title: 'Неверный код 2FA!',
                    text: 'Попробуйте снова или обратитесь к администратору.',
                    footer: '<a href="<?php echo $settings['link_to_admin']; ?>">Возникли вопросы?</a>'
                });
            } else if (res.result == 102) {
                Swal.fire({
                    icon: 'error',
                    title: 'Не подтверждён Email-адрес',
                    text: 'Если вы не получили письмо, нажмите снизу "Переотправить письмо"',
                    footer: '<a onclick="resendEmail(\'' + res.text + '\');" href="#">Переотправить письмо</a>&nbsp;|&nbsp;<a href="<?php echo $settings['link_to_admin']; ?>">Возникли вопросы?</a>'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Произошла неизвестная ошибка!',
                    text: 'Обратитесь к администратору.',
                    footer: '<a href="<?php echo $settings['link_to_admin']; ?>">Возникли вопросы?</a>'
                });
            }
        }
        });
    }

    function resendEmail(token) {
        Swal.close();
        $.ajax({
            type: 'POST',
            url: '/email/resend/'+token,
            success: async function(data) {
            console.log('/email/resend/'+token);
            var res = $.parseJSON(data);
            if (res.result == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ошибка',
                    text: 'При отправке письма произошла неизвестная ошибка. Обратитесь к администратору.',
                    footer: '<a href="<?php echo $settings['link_to_admin']; ?>">Возникли вопросы?</a>'
                });
            } else if (res.result == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Письмо успешно оптравлено',
                    text: 'На указанный Email-адрес было отправлено письмо с ссылкой для подтверждения вашего аккаунта',
                    footer: '<a href="<?php echo $settings['link_to_admin']; ?>">Возникли вопросы?</a>'
                });
            } else if (res.result == 2) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Письмо не было отправлено',
                    text: 'Переотправка письма возможна 1 раз в 5 минут. Попробуйте переотправить письмо чуть позже снова.',
                    footer: '<a href="<?php echo $settings['link_to_admin']; ?>">Возникли вопросы?</a>'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Произошла неизвестная ошибка!',
                    text: 'Обратитесь к администратору.',
                    footer: '<a href="<?php echo $settings['link_to_admin']; ?>">Возникли вопросы?</a>'
                });
            }
        }
        });
    }
    
    document.addEventListener('keypress', function(event) {
        if(arguments[0].code == "Enter" || arguments[0].code == "NumpadEnter") {
            if(!Swal.isVisible()) login();
        }
    });

    let show_pswd = false;

    $('span#btn-pswd').click(function() {
        if(!show_pswd) {
            $('#password-field').attr('type', 'text');
        }
        else {
            $('#password-field').attr('type', 'password');
        }
        $('span#btn-pswd').toggleClass("fa-eye");
        $('span#btn-pswd').toggleClass("fa-eye-slash");
        show_pswd = !show_pswd
    });
</script>