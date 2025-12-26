<?php

use PDO;
class UserRepository
{
    public function create(int $organizationId, string $email, string $password) : int
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = db()->prepare('INSERT INTO users (organization_id, email, password) VALUES (:organization_id, :email, :password)');
        $stmt->execute(['organization_id' => $organizationId, 'email' => $email, 'password' => $hash]);
        return (int) db()->lastInsertId();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = db()->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }
}