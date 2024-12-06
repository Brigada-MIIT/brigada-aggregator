<?php
require '../core/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function main() {
    global $system, $system_user_id, $_user;
    if($system->auth() && $_user['ban'] != 0)
        $system->printError(100);
    if(!empty($_COOKIE['last'])) {
        $location = trim($_COOKIE['last']);
        setcookie("last", $location, time()-1, "/");
    }
    $title = "Бригада | Главная";
    $settings = $system->db()->query("SELECT * FROM `settings` LIMIT 1")->fetch_assoc();
    $content = '../core/template/main.php';
    include '../core/template/default.php';
}

function category($args) {
    global $system, $system_user_id, $_user;
    $id = $args['id'];
    if(!$id)
        Location("/");
    if(!$system->auth())
        Location("/app/auth", "/category/".$id);
    if($_user['ban'] != 0)
        $system->printError(100);
    $db = $system->db();
    $query = $db->query("SELECT * FROM `subcategories` WHERE `category_id` = ${id}");
    if(!$query->num_rows)
        $system->printError(404);
    $name_category = $db->query("SELECT * FROM `categories` WHERE `id` = ${id}")->fetch_assoc()["name"];
    $title = "Бригада | Категория «${name_category}»";
    $content = '../core/template/categories/category.php';
    include '../core/template/default.php';
}

function subcategory($args) {
    global $system, $system_user_id, $_user;
    $id = $args['id'];
    if(!$id)
        Location("/");
    if(!$system->auth())
        Location("/app/auth", "/subcategory/".$id);
    if($_user['ban'] != 0)
        $system->printError(100);
    $db = $system->db();
    $query = $db->query("SELECT * FROM `products` WHERE `subcategory_id` = ${id}");
    if(!$query->num_rows)
        $system->printError(404);
    $name_category = $db->query("SELECT * FROM `subcategories` WHERE `id` = ${id}")->fetch_assoc()["name"];
    $title = "Бригада | Товары подкатегории «${name_category}»";
    $mode = "subcategory";
    $content = '../core/template/categories/subcategory+search.php';
    include '../core/template/default.php';
}

function search($args) {
    global $system, $system_user_id, $_user;
    $search = $args['search'];
    if(!$search)
        Location("/");
    if(!$system->auth())
        Location("/app/auth", "/search/".$search);
    if($_user['ban'] != 0)
        $system->printError(100);
    $db = $system->db();
    $query = $db->query("SELECT * FROM `products` WHERE `name` LIKE '%${search}%'");
    if(!$query->num_rows)
        $system->printError(404);
    $title = "Бригада | Поиск товара «${search}»";
    $mode = "search";
    $content = '../core/template/categories/subcategory+search.php';
    include '../core/template/default.php';
}

function instruction() {
    global $system, $system_user_id, $_user;
    if(!$system->auth())
        Location("/app/auth", "/instruction");
    if($_user['ban'] != 0)
        $system->printError(100);
    $title = "Бригада | Инструкция по использованию";
    $page = "instruction";
    $content = '../core/template/instruction.php';
    include '../core/template/default.php';
}

function auth() {
    global $system, $system_user_id, $_user;
    if ($system->auth() && $system->haveUserPermission($system_user_id, "ACCESS"))
        Location("/");
    $title = "Бригада | Авторизация";
    $settings = $system->db()->query("SELECT * FROM `settings` LIMIT 1")->fetch_assoc();
    include '../core/template/auth/login.php';
}

function register() {
    global $system, $system_user_id, $_user;
    if ($system->auth() && $system->haveUserPermission($system_user_id, "ACCESS"))
        Location("/");
    $title = "Бригада | Регистрация";
    $settings = $system->db()->query("SELECT * FROM `settings` LIMIT 1")->fetch_assoc();
    include '../core/template/auth/register.php';
}

function password_recovery() {
    global $system, $system_user_id, $_user;
    if ($system->auth())
        Location("/");
    $title = "Бригада | Восстановление пароя";
    $settings = $system->db()->query("SELECT * FROM `settings` LIMIT 1")->fetch_assoc();
    $content = '../core/template/password/recovery.php';
    include '../core/template/default.php';
}

function password_change($args) {
    global $system, $system_user_id, $_user;
    $token = $args['token'];
    if(empty($token))
        Location("/");
    $db = $system->db();
    $query = $db->query("SELECT * FROM `users` WHERE `password_token` = '$token'");
    if(!$query)
        die("mysql error");
    if($query->num_rows == 0)
        Location("/");
    $result = $query->fetch_assoc();
    if(time() - intval($result['password_send_timestamp']) > 86400)
        Location("/");
    $title = "Бригада | Смена пароля";
    $settings = $system->db()->query("SELECT * FROM `settings` LIMIT 1")->fetch_assoc();
    $content = '../core/template/password/change.php';
    include '../core/template/default.php';
}

function users() {
    global $system, $system_user_id, $_user;
    if(!$system->auth())
        Location("/app/auth", "/app/users");
    if(!$system->haveUserPermission($system_user_id, "MANAGE_USERS"))
        $system->printError(403);
    $title = "Бригада | Пользователи";
    $content = '../core/template/admin/users/main.php';
    include '../core/template/default.php';
}

function users_edit($args) {
    global $system, $system_user_id, $_user;
    if(!$system->auth())
        Location("/app/auth", "/app/users/".$args['id']."/edit");
    if(!$system->haveUserPermission($system_user_id, "MANAGE_USERS"))
        $system->printError(403);
    $user_id = !empty(intval($args['id'])) ? intval($args['id']) : Location("/users");
    if (!$user = $system->userinfo($user_id))
        Location("/app/users");
    if ($user['user_type'] >= $system->userinfo()['user_type'])
        Location("/app/users");
    $title = "Бригада | Управление пользователем (". $user_id .")";
    $settings = $system->db()->query("SELECT * FROM `settings` LIMIT 1")->fetch_assoc();
    $content = '../core/template/admin/users/edit.php';
    include '../core/template/default.php';
}

function settings() {
    global $system, $system_user_id, $_user;
    if(!$system->auth())
        Location("/app/auth", "/app/settings");
    if(!$system->haveUserPermission($system_user_id, "MANAGE_SETTINGS"))
        $system->printError(403);
    $title = "Бригада | Настройки сайта";
    $settings = $system->db()->query("SELECT * FROM `settings` LIMIT 1")->fetch_assoc();
    $content = '../core/template/admin/settings/settings.php';
    include '../core/template/default.php';
}

function profile($args) {
    global $system, $system_user_id, $_user;
    if(!$system->auth())
        Location("/app/auth", "/profile/".$args['id']);
    if($_user['ban'] != 0)
        $system->printError(100);
    $db = $system->db();
    $user = $system->userinfo($args['id']);
    if(empty($user))
        $system->printError(404);
    $title = "Бригада | Профиль";
    $content = '../core/template/profile/main.php';
    include '../core/template/default.php';
}

function profile_edit() {
    global $system, $system_user_id, $_user;
    if(!$system->auth())
        Location("/app/auth", "/profile/edit");
    if($_user['ban'] != 0)
        $system->printError(100);
    $title = "Бригада | Редактирование профиля";
    $settings = $system->db()->query("SELECT * FROM `settings` LIMIT 1")->fetch_assoc();
    $content = '../core/template/profile/edit.php';
    include '../core/template/default.php';
}

function profile_avatar() {
    global $system, $system_user_id, $_user;
    if (!$system->auth())
        Location("/app/auth", "/profile/avatar");
    if ($_user['ban'] != 0)
        $system->printError(100);
    $title = "Бригада | Редактирование профиля";
    $content = '../core/template/profile/avatar.php';
    include '../core/template/default.php';
}

function admin_categories() {
    global $system, $system_user_id, $_user;
    if(!$system->auth())
        Location("/app/auth", "/app/categories");
    if(!$system->haveUserPermission($system_user_id, "MANAGE_CATEGORIES"))
        $system->printError(403);
    $title = "Бригада | Управление категориями";
    $content = '../core/template/admin/categories/main.php';
    include '../core/template/default.php';
}

function admin_categories_create() {
    global $system, $system_user_id, $_user;
    if(!$system->auth())
        Location("/app/auth", "/app/categories");
    if(!$system->haveUserPermission($system_user_id, "MANAGE_CATEGORIES"))
        $system->printError(403);
    $title = "Бригада | Создание категории";
    $content = '../core/template/admin/categories/create.php';
    include '../core/template/default.php';
}

function admin_categories_edit($args) {
    global $system, $system_user_id, $_user;
    if(!$system->auth())
        Location("/app/auth", "/app/categories");
    if(!$system->haveUserPermission($system_user_id, "MANAGE_CATEGORIES"))
        $system->printError(403);
    $id = $args['id'];
    $db = $system->db();
    $query = $db->query("SELECT * FROM `categories` WHERE id = '$id' LIMIT 1");
    if(!$query->num_rows)
        $system->printError(404);
    $result = $query->fetch_assoc();
    $name_category = $result['name'];
    $settings = $system->db()->query("SELECT * FROM `settings` LIMIT 1")->fetch_assoc();
    $title = "Бригада | Управление категорией «${name_category}»";
    $content = '../core/template/admin/categories/category.php';
    include '../core/template/default.php';
}

function admin_subcategories_edit($args) {
    global $system, $system_user_id, $_user;
    if(!$system->auth())
        Location("/app/auth", "/app/categories");
    if(!$system->haveUserPermission($system_user_id, "MANAGE_CATEGORIES"))
        $system->printError(403);
    $id = $args['id'];
    $db = $system->db();
    $query = $db->query("SELECT * FROM `subcategories` WHERE id = '$id' LIMIT 1");
    if(!$query->num_rows)
        $system->printError(404);
    $result = $query->fetch_assoc();
    $name_category = $result['name'];
    $settings = $system->db()->query("SELECT * FROM `settings` LIMIT 1")->fetch_assoc();
    $title = "Бригада | Управление категорией «${name_category}»";
    $content = '../core/template/admin/categories/subcategory.php';
    include '../core/template/default.php';
}

function admin_products() {
    global $system, $system_user_id, $_user;
    if(!$system->auth())
        Location("/app/auth", "/app/products");
    if(!$system->haveUserPermission($system_user_id, "MANAGE_PRODUCTS"))
        $system->printError(403);
    $title = "Бригада | Управление товарами";
    $content = '../core/template/admin/products/main.php';
    include '../core/template/default.php';
}

function admin_products_create() {
    global $system, $system_user_id, $_user;
    if(!$system->auth())
        Location("/app/auth", "/app/products");
    if(!$system->haveUserPermission($system_user_id, "MANAGE_PRODUCTS"))
        $system->printError(403);
    $title = "Бригада | Создание товара";
    $content = '../core/template/admin/products/create.php';
    include '../core/template/default.php';
}

function admin_products_edit($args) {
    global $system, $system_user_id, $_user;
    if(!$system->auth())
        Location("/app/auth", "/app/products");
    if(!$system->haveUserPermission($system_user_id, "MANAGE_PRODUCTS"))
        $system->printError(403);
}

// ================ API ================ \\

function api_search() {
    global $system, $system_user_id, $_user;
    $s = $_REQUEST['s'];
    if(!$s)
        res(0);
    $db = $system->db();
    $num_rows = $db->query("SELECT * FROM `products` WHERE `name` LIKE '%${s}%'")->num_rows;
    res($num_rows);
}
function api_login() {
    global $system, $system_user_id, $_user;
    if ($system->auth())
        res(3);

    $db = $system->db();
    $db->set_charset("utf8");
    $email = $db->real_escape_string($_REQUEST['email']);
    $password = $db->real_escape_string($_REQUEST['password']);
    $query = $db->query("SELECT * FROM `users` WHERE `email` = '$email'");
    if($query->num_rows == 0)
        res(0);
    $result = $query->fetch_assoc();
    if(!password_verify($password, $result['password']))
        res(0);
    if($result['email_verifed'] == 0 || $result['email_verifed'] == '0')
        res(102, $result['email_send_token']);

    $id = $result['id'];
    $solt = bin2hex(openssl_random_pseudo_bytes(20, $cstrong));
    if($id != 0 && !is_null($id)) {
        if($result['ban'] != 0)
            res(4);
        if($system->enabled_2fa($id)) {
            $user_code = $_REQUEST['auth_code'];
            if (is_null($user_code))
                res(100);
            if (!$system->auth_2fa($id, $user_code))
                res(101);
        }
        $query = $db->query("DELETE FROM `users_session` WHERE `id` = '$id' AND `usid` = '$solt'");
        $query = $db->query("INSERT INTO `users_session` (`id`, `usid`) VALUES ('$id', '$solt')");
        setcookie("id", $id, time()+(60*60*24*7), "/");
        setcookie("usid", $solt, time()+(60*60*24*7), "/");
    }

    if(empty($_COOKIE['last']))
        res(1);
    else {
        $location = trim($_COOKIE['last']);
        setcookie("last", $location, time()-1, "/");
        res(1, $location);
    }
}

function api_register() {
    global $system;
    if ($system->auth()) {
        Location("/");
        res(0);
    }

    $db = $system->db();
    $db->set_charset("utf8");
    $email = $db->real_escape_string($_REQUEST['email']);
    $password = $db->real_escape_string($_REQUEST['password']);
    $password_repeat = $db->real_escape_string($_REQUEST['password_repeat']);
    $lastname = $db->real_escape_string($_REQUEST['lastname']);
    $surname = $db->real_escape_string($_REQUEST['surname']);
    $patronymic = $db->real_escape_string($_REQUEST['patronymic']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        res(2);
    if (($password != $password_repeat) || empty($password))
        res(3);
    if (mb_strlen($password) < 6)
        res(4);
    if (empty($lastname) || empty($surname))
        res(5);
    if (strlen($lastname) > 25 || strlen($surname) > 25 || strlen($patronymic) > 30)
        res(7);
    if (countWhiteSpaces($lastname) >= 2 || countWhiteSpaces($surname) >= 2 || countWhiteSpaces($patronymic) >= 1)
        res(8);

    $query = $db->query("SELECT * FROM `users` WHERE `email` = '$email'");
    if ($query->num_rows !== 0)
        res(6);

    $passwordHash = $db->real_escape_string(password_hash($password, PASSWORD_DEFAULT));
    $emailVerifyHash = $db->real_escape_string(RandomString(20));
    $emailSendHash = $db->real_escape_string(RandomString(20));
    $time = $db->real_escape_string(time());
    if(!empty($patronymic))
        $query = $db->query("INSERT INTO `users` (`id`, `email`, `password`, `avatar`, `user_type`, `ban`, `ban_upload`, `email_verifed`, `email_token`, `email_send_token`, `email_send_timestamp`, `password_token`, `password_send_timestamp`, `password_change_timestamp`, `2fa_secret`, `lastname`, `surname`, `patronymic`, `registred`, `biography`) VALUES (NULL, '$email', '$passwordHash', '/assets/img/avatar.jpg', 1, 0, 0, 0, '$emailVerifyHash', '$emailSendHash', NULL, NULL, NULL, NULL, NULL, '$lastname', '$surname', '$patronymic', '$time', NULL)");
    else
        $query = $db->query("INSERT INTO `users` (`id`, `email`, `password`, `avatar`, `user_type`, `ban`, `ban_upload`, `email_verifed`, `email_token`, `email_send_token`, `email_send_timestamp`, `password_token`, `password_send_timestamp`, `password_change_timestamp`, `2fa_secret`, `lastname`, `surname`, `patronymic`, `registred`, `biography`) VALUES (NULL, '$email', '$passwordHash', '/assets/img/avatar.jpg', 1, 0, 0, 0, '$emailVerifyHash', '$emailSendHash', NULL, NULL, NULL, NULL, NULL, '$lastname', '$surname', NULL, '$time', NULL)");
    $query = $db->query("SELECT * FROM `users` WHERE `email` = '$email'");
    if ($query->num_rows !== 1)
        res(7);
    $id = $query->fetch_assoc()['id'];
    $query = $db->query("INSERT INTO `permissions` (`id`, `userid`, `ACCESS`, `MANAGE_USERS`, `MANAGE_SETTINGS`, `MANAGE_CATEGORIES`, `MANAGE_PRODUCTS`) VALUES (NULL, '$id', '0', '0', '0', '0', '0')");
    $system->send_email_verification($emailSendHash);
    res(1);
}

function api_email_resend($args) {
    global $system;
    $token = $args['token'];
    $link = $system->db()->query("SELECT * FROM `settings` LIMIT 1")->fetch_assoc()['link_to_admin'];
    $verification = $system->send_email_verification($token);
    res($verification);
}

function api_email_verify($args) {
    global $system;
    $token = $args['token'];
    $link = $system->db()->query("SELECT * FROM `settings` LIMIT 1")->fetch_assoc()['link_to_admin'];
    $db = $system->db();
    $query = $db->query("SELECT * FROM `users` WHERE `email_token`='$token'");
    if($query->num_rows !== 1)
        exit("Токен не найден. Если считаете, что произошла ошибка, обратитесь к <a href='".$link."'>администратору<a>.");
    $db->query("UPDATE `users` SET `email_verifed` = '1' WHERE `users`.`email_token` = '$token';");
    $db->query("UPDATE `users` SET `email_send_token` = NULL WHERE `users`.`email_token`='$token'");
    $db->query("UPDATE `users` SET `email_token` = NULL WHERE `users`.`email_token`='$token'");
    exit("Ваш аккаунт успешно подтверждён! Теперь вы можете <a href='/app/auth'>авторизироваться</a>.");
}

function logout() {
    global $system, $system_user_id, $_user;
    if (!$system->auth())
        Location("/");
    $db = $system->db();
    $db->set_charset("utf8");
    $id = trim($_COOKIE['id']);
    $usid = trim($_COOKIE['usid']);
    $db->query("DELETE FROM `users_session` WHERE `id` = '$id' AND `usid` = '$usid'");
    setcookie("id", $id, time()-1, "/");
    setcookie("usid", $solt, time()-1, "/");
    if(!empty($_COOKIE['last']))
        setcookie("last", trim($_COOKIE['last']), time()-1, "/");
    Location("/");
}

function api_password_recovery() {
    global $system, $system_user_id, $_user;
    if ($system->auth())
        res(0, "Authorized");
    $email = $_REQUEST['email'];
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
        res(2);
    $db = $system->db();
    $query = $db->query("SELECT * FROM `users` WHERE `email` = '$email'");
    if(!$query)
        res(0, "mysql error");
    if($query->num_rows == 1)
        $system->send_email_change_password($query->fetch_assoc());
    res(1);
}

function api_password_change($args) {
    global $system, $system_user_id, $_user;
    $token = $args['token'];
    $password = $_REQUEST['password'];
    $password_repeat = $_REQUEST['password_repeat'];
    if(empty($token))
        res(0);
    $db = $system->db();
    $query = $db->query("SELECT * FROM `users` WHERE `password_token` = '$token'");
    if(!$query)
        res(0, "mysql error");
    if($query->num_rows == 0)
        res(2);
    if(empty($password) || empty($password_repeat))
        res(3);
    $result = $query->fetch_assoc();
    $user_id = $result['id'];
    if (mb_strlen($password) < 6)
        res(4);
    if($password != $password_repeat)
        res(5);

    $time = time();
    $passwordHash = $db->real_escape_string(password_hash($password, PASSWORD_DEFAULT));;

    $query = $db->query("UPDATE `users` SET `password` = '$passwordHash', `password_token` = NULL, `password_change_timestamp` = '$time' WHERE `users`.`id` = '$user_id'");
    if(!$query)
        res(0, "mysql change error");

    $query = $db->query("DELETE FROM `users_session` WHERE `id` = '$user_id'");
    if(!$query)
        res(0, "mysql clear sessions error");

    /* Генерация письма */
    $mail = new PHPMailer;
    $mail->setFrom('noreply@brigada-miit.ru', 'Агрегатор «Бригада»');
    $mail->addAddress($result['email'], '');
    $mail->CharSet = 'UTF-8';
    $mail->Subject ='Агрегатор «Бригада». Ваш пароль был изменён';
    $mail->IsHTML(true);
    $mail->msgHTML('
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    margin: 0;
                    padding: 0;
                }
        
                .container {
                    max-width: 600px;
                    padding: 20px;
                    background-color: #fff;
                    border-radius: 15px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
        
                .btn {
                    display: inline-block;
                    background-color: #007bff;
                    color: #fff;
                    text-decoration: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                }
        
                .btn:hover {
                    background-color: #0056b3;
                }
        
                .message {
                    margin-bottom: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="message">
                    <p>Здравствуйте, ' . $result["surname"] . '!</p>
                    <p>Вы недавно сменили пароль от вашего аккаунта. Если пароль был изменён не вами, пожалуйста, перейдите по ссылке для смены пароля. Ограничьте доступ к вашей электронной почте посторонним лицам.</p>
                    <p><a class="btn" href="https://brigada-miit.ru/password/recovery?email=' . $result["email"] . '">Сменить пароль</a></p>
                    <p>С уважением, администрация агрегатора «Бригада» <a href="https://brigada-miit.ru">brigada-miit.ru</a></p>
                </div>
            </div>
        </body>
        </html>
    ');

    $mail->DKIM_domain = 'brigada-miit.ru';
    $mail->DKIM_private = '../core/vendor/dkim_private.pem';
    $mail->DKIM_selector = 'mail';
    $mail->DKIM_identity = $mail->From;
    /*******************/
    
    if(!$mail->send())
        res(1, "mail send error");

    res(1);
}

function api_users_get_users() {
    global $system, $system_user_id, $_user;
    if(!$system->haveUserPermission($system_user_id, "MANAGE_USERS"))
        res(0, "Ошибка доступа");

    header('Content-Type: text/html; charset=utf-8');
    setlocale(LC_ALL, "ru_RU");
    
    $db = $system->db();
    $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10; // Количество записей на странице
    if($limit > 100) die("limit should be < 100");
    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; // Номер страницы
    $offset = ($page - 1) * $limit; // Смещение
    $searchTerm = isset($_REQUEST['search']) ? $_REQUEST['search'] : ''; // Термин поиска
    $orderBy = isset($_REQUEST['order']) ? intval($_REQUEST['order']) : 0; // Поле для сортировки
    $orderDir = isset($_REQUEST['dir']) ? $_REQUEST['dir'] : 'DESC'; // Направление сортировки

    switch($orderBy) {
        case 0:
            $order = "id";
            break;
        case 1:
            $order = "email";
            break;
        case 2:
            $order = "registred";
            break;
        case 3:
            $order = "user_type";
            break;
        default:
            $order = "id";
            break;
    }

    $query = $db->query("SELECT COUNT(*) as count FROM `users`");
    if(!$query) die("MySQL error count query");
    $count = $query->fetch_assoc()['count'];

    $query = $db->query("SELECT `id`, `email`, `registred`, `user_type`, `lastname`, `surname`, `patronymic` FROM `users`
    WHERE (`email` LIKE '%$searchTerm%' OR `biography` LIKE '%$searchTerm%' OR `lastname` LIKE '%$searchTerm%' OR `surname` LIKE '%$searchTerm%' OR `patronymic` LIKE '%$searchTerm%')
    ORDER BY `$order` $orderDir 
    LIMIT $limit OFFSET $offset");
    if(!$query) die("MySQL error query");
    $data = array();
    if ($query->num_rows > 0) {
        while($row = $query->fetch_assoc()) {
            $row['email'] = "<a style='color: inherit' href='/app/users/".$row['id']."/edit' title='".$row['lastname']." ".$row['surname']."".((!empty($row['patronymic'])) ? " ".$row['patronymic'] : "")."'>".$row['email']."</a>";
            $row['registred'] = "<a style='color: inherit' href='/app/users/".$row['id']."/edit'>".unixDateToString(intval($row['registred']))."</a>";
            $row['user_type'] = "<a style='color: inherit' href='/app/users/".$row['id']."/edit'>".$system->getNameRole($row['user_type'])."</a>";
            $row['id'] = "<a target='_blank' href='/app/users/".$row['id']."/edit'>".$row['id']."</a>";
            $data[] = $row;
        }
    }

    $response = array(
        "count" => intval($count),
        "filtred_count" => ($searchTerm == '') ? intval($count) : $query->num_rows,
        "data" => $data
    );

    echo json_encode($response);
}

function api_users_2fa_delete($args) {
    global $system, $system_user_id, $_user;
    if(!$system->haveUserPermission($system_user_id, "MANAGE_USERS"))
        res(0, "Ошибка доступа");

    $id = $args['id'];
    $user = $system->userinfo($id);
    if(!$user)
        res(0, "User not found");

    $query = $system->db()->query("UPDATE `users` SET `2fa_secret` = NULL WHERE `users`.`id` = $id");
    if(!$query) 
        res(0, "mysql error");
    res(1);
}

function api_users_edit() {
    global $system, $system_user_id, $_user;
    if(!$system->haveUserPermission($system_user_id, "MANAGE_USERS"))
        res(0, "Ошибка доступа");

    $user_id = !empty(intval($_POST['id'])) ? intval($_POST['id']) : res(0, "user_id error 1");
    if (!$user = $system->userinfo($user_id))
        res(0, "Ошибка");
    if(!is_numeric($_POST['role']))
        res(4, "Выберите роль и попробуйте снова");

    $role = !empty(intval($_POST['role'])) || $_POST['role'] < 1 ? intval($_POST['role']) : 1;
    if($role < 1) res(0, "Неправильно задана роль");
    $user_role = $system->userinfo()['user_type'];
    if ($user_role <= $user['user_type'] || $role >= $user_role)
        res(0, "Ваша роль ниже чем у данного пользователя");

    $db = $system->db();
    $db->set_charset("utf8");

    $ban = (intval($_POST['ban']) == 0 || intval($_POST['ban']) == 1) ? intval($_POST['ban']) : res(0, "ban error 1");
    if($ban == 1) 
        $ban = $system_user_id;
    else if($ban == 0);
    else res(0, "ban error 2");

    $ban_upload = (intval($_POST['ban_upload']) == 0 || intval($_POST['ban_upload']) == 1) ? intval($_POST['ban_upload']) : res(0, "ban_upload error 1");
    if($ban_upload == 1) 
        $ban_upload = $system_user_id;
    else if($ban_upload == 0);
    else res(0, "ban_upload error 2");

    $email_verifed = (intval($_POST['email_verifed']) == 0 || intval($_POST['email_verifed']) == 1) ? intval($_POST['email_verifed']) : res(0, "email_verifed error 1");
    if($email_verifed == 1) {
        if(intval($user['email_verifed']) == 0)
            $email_verifed = $system_user_id;
    }
    else if($email_verifed == 0) {
        if(intval($user['email_verifed']) != 0)
            res(0, "email_verifed error 2");
    }
    
    $patr_check = 0;
    $bio_check = 0;

    $lastname = !empty($_POST['lastname']) ? $_POST['lastname'] : res(0, "Укажите в поле фамилию");
    $surname = !empty($_POST['surname']) ? $_POST['surname'] : res(0, "Укажите в поле имя");
    $patronymic = !empty($_POST['patronymic']) ? $_POST['patronymic'] : ($patr_check = 1);
    $biography = !empty($_POST['biography']) ? $_POST['biography'] : ($bio_check = 1);

    if (strlen($lastname) > 25 || strlen($surname) > 25 || strlen($patronymic) > 30 || strlen($biography) > 500)
        res(0, "В полях ФИО и биографии слишком много символов");
    if (countWhiteSpaces($lastname) >= 2 || countWhiteSpaces($surname) >= 2 || countWhiteSpaces($patronymic) >= 1)
        res(0, "В полях ФИО слишком много пробелов");

    $query = $db->query("UPDATE `users` SET `user_type` = '$role', `ban` = '$ban', `ban_upload` = '$ban_upload', `email_verifed` = '$email_verifed', `lastname` = '$lastname', `surname` = '$surname', `patronymic` = ".(($patr_check) ? "NULL" : "'$patronymic'").", `biography` = ".(($bio_check) ? "NULL" : "'$biography'")." WHERE `id` = '$user_id'");

    if(!$query) res(0, "mysql error");
    res(1, "Данные пользователя успешно обновлены");
}

function api_users_delete() {
    global $system, $system_user_id, $_user;
    if(!$system->haveUserPermission($system_user_id, "MANAGE_USERS"))
        res(0, "Ошибка доступа");
    $id = intval($_POST['id']) > 0 ? intval($_POST['id']) : res(0, 'Выберите пользователя');
    $user_role = $system->userinfo()['user_type'];
    $email = $system->userinfo($id)['email'];
    if ($user_role <= $system->userinfo($id)['user_type'])
        res(0, "Ваша роль меньше или равна удаляемому пользователю");
    $system->db()->query("INSERT INTO `users_deleted` SELECT * FROM `users` WHERE `id` = '$id'");
    $system->db()->query("DELETE FROM `users` WHERE `id` = '$id'");
    $system->db()->query("DELETE FROM `users_session` WHERE `id` = '$id'");
    res(1, "Пользователь ". $email . " успешно удален");
}

function api_users_permissions() {
    global $system, $system_user_id, $_user;
    if(!$system->haveUserPermission($system_user_id, "MANAGE_USERS"))
        Location("/");
    $db = $system->db();
    header("Content-Type: application/json");
    $data = json_decode(file_get_contents("php://input"));
    $user_id = $data[0][1];
    for($i = 0; $i < sizeof($data); $i++) {
        if($data[$i][0] == "id") continue;
        if($data[$i][1] && $system->haveUserPermission($_user['id'], $data[$i][0])) {
            $db->query("UPDATE `permissions` SET ".$data[$i][0]." = 1 WHERE `userid` = $user_id");
        }
        else if(!$data[$i][1] && $system->haveUserPermission($_user['id'], $data[$i][0])) {
            $db->query("UPDATE `permissions` SET ".$data[$i][0]." = 0 WHERE `userid` = $user_id");
        }
    }
    res(1, "Права успешно обновлены!");
}

function api_profile_avatar() {
    global $system, $system_user_id, $_user;
    if (!$system->auth())
        res(0);
    $settings = $system->db()->query("SELECT * FROM `settings` LIMIT 1")->fetch_assoc();
    if($_FILES['avatar']['tmp_name']) {
        if($_FILES['avatar']['type'] != 'image/jpeg') {
            //res(0, "Неверный тип изображения.");
            echo "Неверный тип изображения.<br><a href='/profile/edit/'>ВЕРНУТЬСЯ НАЗАД</a>";
            return;
        }
        if($_FILES['avatar']['size'] >= $settings['max_size_avatar'] * MB) {
            //res(0, "Вес не должен превышать 2 МБ.");
            echo "Вес не должен превышать ". $settings['max_size_avatar'] ." МБ.<br><a href='/profile/edit/'>ВЕРНУТЬСЯ НАЗАД</a>";
            return;
        }
    }
    else Location("/profile/edit/avatar");
    $db = $system->db();
    $db->set_charset("utf8");

    $image = imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
    $permitted_char = '0123456789ABCDEFHKLMNOPRSTUYabcdefhklmnoprstuy-_';
    $filename = substr(str_shuffle($permitted_char), 0, 11);
    $exif = exif_read_data($_FILES['avatar']['tmp_name']);

    if(mkdir('user-avatars/' . $system_user_id . '/', 0777));
    $dir = 'user-avatars/' . $system_user_id . '/' . $filename;

    $files = glob('user-avatars/' . $system_user_id . '/*');
    foreach($files as $file){
        if(is_file($file)) {
            unlink($file);
        }
    }

    if (!empty($exif['Orientation'])) {
        switch ($exif['Orientation']) {
            case 3:
                $image = imagerotate($image, 180, 0);
                break;
            case 6:
                $image = imagerotate($image, -90, 0);
                break;
            case 8:
                $image = imagerotate($image, 90, 0);
                break;
        }
        imagejpeg($image, $dir . '.jpg', 90);
    }
    else imagejpeg($image, $dir . '.jpg');
    $db->query("UPDATE `users` SET `avatar` = '/$dir.jpg' WHERE `id` = '$system_user_id'");
    imagedestroy($tmp);
    //res(1, "Аватарка изменена.");
    echo "Аватарка успешно изменена.<br><a href='/profile/edit/'>ВЕРНУТЬСЯ НАЗАД</a>";
    Location("/profile/".$system_user_id);
}

function api_profile_avatar_delete($args) {
    global $system, $system_user_id, $_user;
    if (!$system->auth())
        res(0);
    $id = 0;
    if(empty($args['id']))
        $id = $system_user_id;
    else {
        if(!$system->haveUserPermission($system_user_id, "MANAGE_USERS"))
            res(0);
        $id = $args['id'];
        if(!$system->userinfo($id))
            res(0, "User not found");
    }
    $files = glob('user-avatars/' . $id . '/*');
    $query = $system->db()->query("UPDATE `users` SET `avatar` = '/assets/img/avatar.jpg' WHERE `users`.`id` = $id");
    if(!$query)
        res(0, "mysql error");
    foreach($files as $file){
        if(is_file($file)) {
            unlink($file);
        }
    }
    res(1);
}

function api_profile_change_password() {
    global $system, $system_user_id, $_user;
    if (!$system->auth())
        res(0);
    res($system->send_email_change_password($_user));
}

function api_profile_edit() {
    global $system, $system_user_id, $_user;
    if(!$system->auth())
        res(0);
    if ($_user['ban'] != 0)
        $system->printError(100);
    $db = $system->db();

    $patr_check = 0;
    $bio_check = 0;

    $lastname = !empty($_POST['lastname']) ? $_POST['lastname'] : res(0, "Укажите в поле вашу фамилию");
    $surname = !empty($_POST['surname']) ? $_POST['surname'] : res(0, "Укажите в поле ваше имя");
    $patronymic = !empty($_POST['patronymic']) ? $_POST['patronymic'] : ($patr_check = 1);
    $biography = !empty($_POST['biography']) ? $_POST['biography'] : ($bio_check = 1);

    if (strlen($lastname) > 25 || strlen($surname) > 25 || strlen($patronymic) > 30 || strlen($biography) > 500)
        res(0, "В полях ФИО и биографии слишком много символов");
    if (countWhiteSpaces($lastname) >= 2 || countWhiteSpaces($surname) >= 2 || countWhiteSpaces($patronymic) >= 1)
        res(0, "В полях ФИО слишком много пробелов");

    $query = $db->query("UPDATE `users` SET `lastname` = '$lastname', `surname` = '$surname', `patronymic` = ".(($patr_check) ? "NULL" : "'$patronymic'").", `biography` = ".(($bio_check) ? "NULL" : "'$biography'")." WHERE `id` = '$system_user_id'");
    if(!$query) res(0, "mysql error");
    res(1);
}

function api_settings_update() {
    global $system, $system_user_id, $_user;
    if (!$system->haveUserPermission($system_user_id, "MANAGE_SETTINGS"))
        res(0, "Ошибка доступа");
    $max_size_avatar = intval($_POST['max_size_avatar']) > 0 ? intval($_POST['max_size_avatar']) : res(0, 'Укажите целое положительное число (max_size_avatar)');
    $link_to_admin = empty($_POST['link_to_admin']) ? res(0, "Укажите ссылку на администратора") : $_POST['link_to_admin'];
    $system->db()->query("UPDATE `settings` SET `max_size_avatar` = '$max_size_avatar', `link_to_admin` = '$link_to_admin' WHERE 1");
    res(1, "Настройки успешно обновлены");
}

function api_user_changepassword() {
    global $system, $system_user_id, $_user;
    if (!$system->auth())
        Location("/");
    if(empty($_REQUEST['password'])) { echo 123; return; };

    $user_id = $_user['id'];
    $db = $system->db();
    $db->set_charset("utf8");
    $password = $db->real_escape_string(trim($_REQUEST['password']));
    $password = password_hash($password, PASSWORD_DEFAULT);
    $db->query("UPDATE `users` SET `password` = '$password' WHERE `id` = '$user_id'");
    $id = trim($_COOKIE['id']);
    $db->query("DELETE FROM `users_session` WHERE `id` = '$id'");
    setcookie("id", $id, time()-1, "/");
    setcookie("usid", $solt, time()-1, "/");
    res(1, "Пароль успешно изменен! Переавторизируйтесь на сайте, текущая сессия закрыта.");
}

function api_categories_get() {
    global $system, $system_user_id, $_user;
    if(!$system->haveUserPermission($system_user_id, "MANAGE_CATEGORIES"))
        res(0);

    $s = $_REQUEST['s'];
    if(is_null($s))
        $s = 0;

    header('Content-Type: text/html; charset=utf-8');
    setlocale(LC_ALL, "ru_RU");

    $db = $system->db();
    $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10; // Количество записей на странице
    if($limit > 100) die("limit should be < 100");
    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; // Номер страницы
    $offset = ($page - 1) * $limit; // Смещение
    $searchTerm = isset($_REQUEST['search']) ? $_REQUEST['search'] : ''; // Термин поиска
    $orderBy = isset($_REQUEST['order']) ? intval($_REQUEST['order']) : 0; // Поле для сортировки
    $orderDir = isset($_REQUEST['dir']) ? $_REQUEST['dir'] : 'DESC'; // Направление сортировки

    switch($orderBy) {
        case 0:
            $order = "id";
            break;
        case 1:
            $order = "name";
            break;
        default:
            $order = "id";
            break;
    }

    $query = 0;

    if($s == 0)
        $query = $db->query("SELECT COUNT(*) as count FROM `categories`");
    else
        $query = $db->query("SELECT COUNT(*) as count FROM `subcategories` WHERE `category_id` = '$s'");
    if(!$query) die("MySQL error count query");
    $count = $query->fetch_assoc()['count'];

    if($s == 0)
        $query = $db->query("SELECT `id`, `name` FROM `categories`
        WHERE (`name` LIKE '%$searchTerm%' OR `id` LIKE '%$searchTerm%')
        ORDER BY `$order` $orderDir 
        LIMIT $limit OFFSET $offset");
    else
        $query = $db->query("SELECT `id`, `name` FROM `subcategories`
        WHERE (`name` LIKE '%$searchTerm%' OR `id` LIKE '%$searchTerm%') AND `category_id` = '$s'
        ORDER BY `$order` $orderDir 
        LIMIT $limit OFFSET $offset");
    if(!$query) die("MySQL error query");
    $data = array();
    if ($query->num_rows > 0) {
        while($row = $query->fetch_assoc()) {
            $r = "categories";
            if($s != 0)
                $r = "subcategories";
            $row['name'] = "<a style='color: inherit' href='/app/".$r."/".$row['id']."/edit'>".$row['name']."</a>";
            $row['id'] = "<a target='_blank' href='/app/".$r."/".$row['id']."/edit'>".$row['id']."</a>";
            $data[] = $row;
        }
    }

    $response = array(
        "count" => intval($count),
        "filtred_count" => ($searchTerm == '') ? intval($count) : $query->num_rows,
        "data" => $data
    );

    echo json_encode($response);
}

function api_categories_create() {
    global $system, $system_user_id, $_user;
    if(!$system->haveUserPermission($system_user_id, "MANAGE_CATEGORIES"))
        res(0);
    $name = !empty($_REQUEST['name']) ? $_REQUEST['name'] : res(0, "Введите название категории");
    $category_id = !empty($_REQUEST['category_id']) ? intval($_REQUEST['category_id']) : 0;
    $description = !empty($_REQUEST['description']) ? "'".$_REQUEST['description']."'" : "NULL";
    $picture_url = !empty($_REQUEST['picture_url']) ? "'".$_REQUEST['picture_url']."'" : "NULL";
    $db = $system->db();
    if($category_id == 0) {
        $query = $db->query("INSERT INTO `categories` (`id`, `name`, `description`, `picture_url`) VALUES (NULL, '$name', $description, $picture_url) ");
        if (!$query)
            res(0, "MySQL Error INSERT");
    }
    else {
        $query = $db->query("SELECT * FROM `categories` WHERE `id` = '$category_id'");
        if(!$query->num_rows)
            res(0, "Category not found");
        $query = $db->query("INSERT INTO `subcategories` (`id`, `category_id`, `name`, `description`, `picture_url`) VALUES (NULL, '$category_id', '$name', $description, $picture_url)");
        if(!$query)
            res(0, "MySQL Error INSERT");
    }
    res(1);
}

function api_categories_edit() {
    global $system, $system_user_id, $_user;
    if(!$system->haveUserPermission($system_user_id, "MANAGE_CATEGORIES"))
        res(0);
    $id = !empty(intval($_REQUEST['id'])) ? intval($_REQUEST['id']) : res(0, "id error");
    $name = !empty($_REQUEST['name']) ? $_REQUEST['name'] : res(0, "Введите название категории");
    $description = !empty($_REQUEST['description']) ? "'".$_REQUEST['description']."'" : "NULL";
    $picture_url = !empty($_REQUEST['picture_url']) ? "'".$_REQUEST['picture_url']."'" : "NULL";
    $db = $system->db();
    $query = $db->query("UPDATE `categories` SET `name` = '$name', `description` = $description, `picture_url` = $picture_url WHERE `id` = '$id'");
    if(!$query)
        res(0, "MySQL Error UPDATE");
    res(1);
}

function api_categories_delete() {
    global $system, $system_user_id, $_user;
    if(!$system->haveUserPermission($system_user_id, "MANAGE_CATEGORIES"))
        res(0);
    $id = $_REQUEST['id'];
    if(is_null($id))
        res(0, "Where is id?");
    $db = $system->db();
    $query = $db->query("SELECT * FROM `subcategories` WHERE `category_id` = '$id'");
    if($query->num_rows > 0)
        res(2);
    $query = $db->query("DELETE FROM `categories` WHERE `id` = '$id'");
    if(!$query)
        res(0, "MySQL Error DELETE");
    res(1);
}

function api_subcategories_edit() {
    global $system, $system_user_id, $_user;
    if(!$system->haveUserPermission($system_user_id, "MANAGE_CATEGORIES"))
        res(0);
    $id = !empty(intval($_REQUEST['id'])) ? intval($_REQUEST['id']) : res(0, "id error");
    $name = !empty($_REQUEST['name']) ? $_REQUEST['name'] : res(0, "Введите название подкатегории");
    $category_id = !empty(intval($_REQUEST['category_id'])) ? intval($_REQUEST['category_id']) : res(0, "Выберите категорию");
    $description = !empty($_REQUEST['description']) ? "'".$_REQUEST['description']."'" : "NULL";
    $picture_url = !empty($_REQUEST['picture_url']) ? "'".$_REQUEST['picture_url']."'" : "NULL";
    $db = $system->db();
    $query = $db->query("SELECT * FROM `categories` WHERE `id` = '$category_id'");
    if(!$query->num_rows)
        res(0, "Указанная категория не найдена");
    $query = $db->query("UPDATE `subcategories` SET `name` = '$name', `category_id` = '$category_id', `description` = $description, `picture_url` = $picture_url WHERE `id` = '$id'");
    if(!$query)
        res(0, "MySQL Error UPDATE");
    res(1);
}

function api_subcategories_delete() {
    global $system, $system_user_id, $_user;
    if(!$system->haveUserPermission($system_user_id, "MANAGE_CATEGORIES"))
        res(0);
    $id = $_REQUEST['id'];
    if(is_null($id))
        res(0, "Where is id?");
    $db = $system->db();
    $query = $db->query("SELECT * FROM `products` WHERE `subcategory_id` = '$id'");
    if($query->num_rows > 0)
        res(2);
    $query = $db->query("DELETE FROM `subcategories` WHERE `id` = '$id'");
    if(!$query)
        res(0, "MySQL Error DELETE");
    res(1);
}

function api_products_get() {
    global $system, $system_user_id, $_user;
    if(!$system->haveUserPermission($system_user_id, "MANAGE_PRODUCTS"))
        res(0);

    $s = $_REQUEST['s'];
    if(is_null($s))
        $s = 0;

    header('Content-Type: text/html; charset=utf-8');
    setlocale(LC_ALL, "ru_RU");

    $db = $system->db();
    $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10; // Количество записей на странице
    if($limit > 100) die("limit should be < 100");
    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; // Номер страницы
    $offset = ($page - 1) * $limit; // Смещение
    $searchTerm = isset($_REQUEST['search']) ? $_REQUEST['search'] : ''; // Термин поиска
    $orderBy = isset($_REQUEST['order']) ? intval($_REQUEST['order']) : 0; // Поле для сортировки
    $orderDir = isset($_REQUEST['dir']) ? $_REQUEST['dir'] : 'DESC'; // Направление сортировки

    switch($orderBy) {
        case 0:
            $order = "id";
            break;
        case 1:
            $order = "name";
            break;
        case 2:
            $order = "subcategory_id";
            break;
        default:
            $order = "id";
            break;
    }

    $query = 0;

    if($s == 0)
        $query = $db->query("SELECT COUNT(*) as count FROM `products`");
    else
        $query = $db->query("SELECT COUNT(*) as count FROM `products` WHERE `subcategory_id` = '$s'");
    if(!$query) die("MySQL error count query");
    $count = $query->fetch_assoc()['count'];

    if($s == 0)
        $query = $db->query("SELECT `id`, `name`, `subcategory_id` FROM `products`
        WHERE (`name` LIKE '%$searchTerm%' OR `id` LIKE '%$searchTerm%')
        ORDER BY `$order` $orderDir 
        LIMIT $limit OFFSET $offset");
    else
        $query = $db->query("SELECT `id`, `name`, `subcategory_id` FROM `products`
        WHERE (`name` LIKE '%$searchTerm%' OR `id` LIKE '%$searchTerm%') AND `subcategory_id` = '$s'
        ORDER BY `$order` $orderDir 
        LIMIT $limit OFFSET $offset");
    if(!$query) die("MySQL error query");
    $data = array();
    if ($query->num_rows > 0) {
        while($row = $query->fetch_assoc()) {
            $row['name'] = "<a style='color: inherit' href='/app/products/".$row['id']."/edit'>".$row['name']."</a>";
            $row['id'] = "<a target='_blank' href='/app/products/".$row['id']."/edit'>".$row['id']."</a>";
            $subcategory_id = $row['subcategory_id'];
            $query1 = $db->query("SELECT `name` FROM `subcategories` WHERE `id` = '$subcategory_id'");
            $subcategory_name = $query1->fetch_assoc()['name'];
            $row['subcategory_id'] = "<a target='_blank' href='/app/subcategories/".$subcategory_id."/edit'>".$subcategory_name."</a>";
            $data[] = $row;
        }
    }

    $response = array(
        "count" => intval($count),
        "filtred_count" => ($searchTerm == '') ? intval($count) : $query->num_rows,
        "data" => $data
    );

    echo json_encode($response);
}

function api_products_create() {
    global $system, $system_user_id, $_user;
    if(!$system->haveUserPermission($system_user_id, "MANAGE_PRODUCTS"))
        res(0);
    $name = !empty($_REQUEST['name']) ? $_REQUEST['name'] : res(0, "Введите название товара");
    $subcategory_id = !empty(intval($_REQUEST['subcategory_id'])) ? intval($_REQUEST['subcategory_id']) :  res(0, "Выберите подкатегорию товара");
    $description = !empty($_REQUEST['description']) ? "'".$_REQUEST['description']."'" : "NULL";
    $picture_url = !empty($_REQUEST['picture_url']) ? "'".$_REQUEST['picture_url']."'" : "NULL";
    $relationships = !empty($_REQUEST['relationships']) ? $_REQUEST['relationships'] : res(0, "Добавьте хотя бы один магазин для товара");
    $relationships = json_decode($relationships);
    if (json_last_error())
        res(0, "JSON ERROR");
    if(!count($relationships))
        res(0, "JSON Format Error 1");
    for($i = 0; $i < count($relationships); $i++) {
        if(count($relationships[$i]) != 4)
            res(0, "JSON Format Error 2");
        if(!is_integer($relationships[$i][1]))
            res(0, "JSON Format Error 3");
    }
    $db = $system->db();
    $query = $db->query("SELECT * FROM `subcategories` WHERE `id` = '$subcategory_id'");
    if(!$query->num_rows)
        res(0, "Subcategory not found");
    $query = $db->query("INSERT INTO `products` (`id`, `subcategory_id`, `name`, `description`, `picture_url`, `relationships`) VALUES (NULL, '$subcategory_id', '$name', $description, $picture_url, '$relationships')");
    if(!$query)
        res(0, "MySQL Error INSERT\n".$db->error);
    res(1);
}

function api_products_edit() {
    global $system, $system_user_id, $_user;
    if(!$system->haveUserPermission($system_user_id, "MANAGE_PRODUCTS"))
        res(0);
    $id = !empty(intval($_REQUEST['id'])) ? intval($_REQUEST['id']) : res(0, "id error");
    $name = !empty($_REQUEST['name']) ? $_REQUEST['name'] : res(0, "Введите название товара");
    $subcategory_id = !empty(intval($_REQUEST['subcategory_id'])) ? intval($_REQUEST['subcategory_id']) :  res(0, "Выберите подкатегорию товара");
    $description = !empty($_REQUEST['description']) ? "'".$_REQUEST['description']."'" : "NULL";
    $picture_url = !empty($_REQUEST['picture_url']) ? "'".$_REQUEST['picture_url']."'" : "NULL";
    $relationships = !empty($_REQUEST['relationships']) ? $_REQUEST['relationships'] : res(0, "Добавьте хотя бы один магазин для товара");
    $relationships = json_decode($relationships);
    if (json_last_error() === JSON_ERROR_NONE)
        res(0, "JSON ERROR");
    if(!count($relationships))
        res(0, "JSON Format Error 1\n".$relationships);
    for($i = 0; $i < count($relationships); $i++) {
        if(count($relationships[$i]) != 4)
            res(0, "JSON Format Error 2\n".$relationships);
        if(!is_integer($relationships[$i][1]))
            res(0, "JSON Format Error 3\n".$relationships);
    }
    $db = $system->db();
    $query = $db->query("SELECT * FROM `subcategories` WHERE `id` = '$subcategory_id'");
    if(!$query->num_rows)
        res(0, "Subcategory not found");
    $query = $db->query("UPDATE `products` SET `name` = '$name', `description` = $description, `picture_url` = $picture_url, `relationships` = '$relationships' WHERE `id` = $id");
    if(!$query)
        res(0, "MySQL Error UPDATE\n".$db->error);
    res(1);
}

function api_products_delete() {
    global $system, $system_user_id, $_user;
    if(!$system->haveUserPermission($system_user_id, "MANAGE_PRODUCTS"))
        res(0);
    $id = $_REQUEST['id'];
    if(is_null($id))
        res(0, "Where is id?");
    $db = $system->db();
    $query = $db->query("DELETE FROM `products` WHERE `id` = '$id'");
    if(!$query)
        res(0, "MySQL Error DELETE");
    res(1);
}

/*function download_moderation_tool() {
    global $system, $system_user_id, $_user;
    if (!$system->haveUserPermission($system_user_id, "DOWNLOAD_MODERTOOL"))
        Location("/");
    $file = "../public_html/moderation-tool/Mc5zsXr/Moderation Tool Installer.exe";
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}*/