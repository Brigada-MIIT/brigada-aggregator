<?php
header('Access-Control-Allow-Origin: *');
$system = new System();

require __DIR__ . '/vendor/autoload.php';

$_user = $system->userinfo();
$system_user_id = $system->userinfo()['id'];

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'main');
    $r->addRoute('GET', '/instruction', 'instruction');
    $r->addRoute('GET', '/app/auth', 'auth');
    $r->addRoute('GET', '/app/register', 'register');
    $r->addRoute('GET', '/password/recovery', 'password_recovery');
    $r->addRoute('GET', '/password/change/{token}', 'password_change');
    $r->addRoute('GET', '/app/users', 'users');
    $r->addRoute('GET', '/app/users/{id:\d+}/edit', 'users_edit');
    $r->addRoute('GET', '/app/settings', 'settings');
    $r->addRoute('GET', '/profile/{id:\d+}', 'profile');
    $r->addRoute('GET', '/profile/edit', 'profile_edit');
    $r->addRoute('GET', '/profile/uploads', 'profile_uploads');
    $r->addRoute('GET', '/profile/edit/avatar', 'profile_avatar');
    $r->addRoute(['GET', 'POST'], '/uploads/create', 'uploads_create');
    $r->addRoute('GET', '/uploads/files/{id:\d+}', 'uploads_files');
    $r->addRoute('GET', '/uploads/files/download/{id:\d+}', 'uploads_files_download');
    $r->addRoute('GET', '/uploads/view/{id:\d+}', 'uploads_view');
    $r->addRoute('GET', '/uploads/edit/{id:\d+}', 'uploads_edit');
    //*** API ***\\
    $r->addRoute('POST', '/api/main/get_uploads', 'api_main_get_uploads');
    $r->addRoute('POST', '/api/login', 'api_login');
    $r->addRoute('POST', '/api/register', 'api_register');
    $r->addRoute('POST', '/email/resend/{token}', 'api_email_resend');
    $r->addRoute(['GET', 'POST'], '/email/verify/{token}', 'api_email_verify');
    $r->addRoute(['GET', 'POST'], '/logout', 'logout');
    $r->addRoute('POST', '/api/password/recovery', 'api_password_recovery');
    $r->addRoute('POST', '/api/password/change/{token}', 'api_password_change');
    $r->addRoute('POST', '/api/users/get_users', 'api_users_get_users');
    $r->addRoute('POST', '/api/users/2fa_delete/{id:\d+}', 'api_users_2fa_delete');
    $r->addRoute('POST', '/api/users/edit', 'api_users_edit');
    $r->addRoute('POST', '/api/users/delete', 'api_users_delete');
    $r->addRoute('POST', '/api/users/permissions', 'api_users_permissions');
    $r->addRoute('POST', '/api/settings/update', 'api_settings_update');
    $r->addRoute('POST', '/api/profile/edit', 'api_profile_edit');
    $r->addRoute('POST', '/api/profile/get_uploads/{id:\d+}', 'api_profile_get_uploads');
    $r->addRoute('POST', '/api/profile/get_uploads/self', 'api_profile_get_uploads_self');
    $r->addRoute('POST', '/api/profile/avatar', 'api_profile_avatar');
    $r->addRoute('POST', '/api/profile/avatar/delete', 'api_profile_avatar_delete'); // для личного удаления
    $r->addRoute('POST', '/api/profile/avatar/delete/{id:\d+}', 'api_profile_avatar_delete'); // для удаления через админ-панель
    $r->addRoute('POST', '/api/profile/change_password', 'api_profile_change_password');
    $r->addRoute('POST', '/api/uploads/create', 'api_uploads_create');
    $r->addRoute('POST', '/api/uploads/edit/{id:\d+}', 'api_uploads_edit');
    $r->addRoute('POST', '/api/uploads/delete/{id:\d+}', 'api_uploads_delete');
    $r->addRoute('POST', '/api/files/upload', 'api_files_upload');
    $r->addRoute('POST', '/api/files/upload/check', 'api_files_upload_check');
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

include __DIR__ . "/handlers.php";

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        $system->printError(404);
        die();
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        $system->printError(405);
        die();
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        print $handler($vars);
        break;
}