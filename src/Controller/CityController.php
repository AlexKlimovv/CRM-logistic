<?php

class CityController
{
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function search()
    {
        header('Content-Type: application/json; charset=utf-8');

        $q = $_GET["q"] ?? '';

        if (mb_strlen($q) < 2) {
            echo json_encode([]);
            return;
        }

        $stmt = $this->pdo->prepare("SELECT id, name FROM cities WHERE name LIKE :q LIMIT 10");
        $stmt->execute(['q' => '%'.$q.'%']);

        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}