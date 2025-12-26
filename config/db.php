<?php

$host = 'localhost';
$db   = 'crm_logistic';
$user = 'auth_user';
$pass = 'Auth_pass_123!';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

function db():PDO
{
    static $pdo = null;
    if ($pdo === null) {
        global $dsn, $user, $pass, $options;

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    return $pdo;
}