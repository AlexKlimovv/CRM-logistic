<?php

class OrganizationRepository
{
    public function findByInn(string $inn): ?array
    {
        $stmt = db()->prepare('SELECT * FROM organizations WHERE inn = :inn');
        $stmt->execute(['inn' => $inn]);
        $org = $stmt->fetch(PDO::FETCH_ASSOC);
        return $org ?: null;
    }

    public function create(string $name, string $inn): int {
        $stmt = db()->prepare('INSERT INTO organizations (name, inn) VALUES (:name, :inn)');
        $stmt->execute(['name' => $name, 'inn' => $inn]);

        return (int) db()->lastInsertId();
    }
}