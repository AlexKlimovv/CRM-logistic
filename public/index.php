<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/Repository/OrganizationRepository.php';
require_once __DIR__ . '/../src/Repository/UserRepository.php';
require_once __DIR__ . '/../src/Controller/AuthController.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($path) {
    case '/register':
        (new AuthController())->register();
        break;

    case '/login':
        (new AuthController())->login();
        break;

    default:
        echo 'Главная';
}