<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/Repository/OrganizationRepository.php';
require_once __DIR__ . '/../src/Repository/UserRepository.php';
require_once __DIR__ . '/../src/Repository/CounterpartyRepository.php';
require_once __DIR__ . '/../src/Controller/AuthController.php';
require_once __DIR__ . '/../src/Controller/CounterpartyController.php';
require_once __DIR__ . '/../src/Validator/CounterpartyValidator.php';
require_once __DIR__ . '/../src/Controller/CityController.php';

$pdo = db();

$publicRoutes = ['/login', '/register'];

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (!isset($_SESSION['user_id']) && !in_array($path, $publicRoutes)) {
    header('Location: /login');
    exit();
}

if ($path === '/counterparties/create' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    (new CounterpartyController($pdo))->showCreateForm();
    exit();
}

if ($path === '/counterparties/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    (new CounterpartyController($pdo))->createCounterparty();
    exit();
}

if ($path === '/counterparties' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    (new CounterpartyController($pdo))->list();
    exit();
}

if ($path === '/cities/search' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    (new CityController($pdo))->search();
    exit();
}

if ($path === '/counterparties/edit' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    (new CounterpartyController($pdo))->editForm();
    exit();
}

switch ($path) {
    case '/register':
        (new AuthController())->register();
        break;

    case '/login':
        (new AuthController())->login();
        break;

        case '/logout':
            (new AuthController())->logout();
            break;

    default:
        echo 'Главная';
        echo '<a href="/logout">Logout</a>';
}