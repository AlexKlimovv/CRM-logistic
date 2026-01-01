<?php

class CounterpartyRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(array $data, int $createdByUserId): void
    {
        $sql = "INSERT INTO counterparties (
                    name,
                    legal_form,
                    edrpou,
                    inn,
                    vat_certificate,
                    legal_address,
                    postal_address,
                    city_id,
                    created_by_user_id
                    ) VALUES (
                    :name,
                    :legal_form,
                    :edrpou,
                    :inn,
                    :vat_certificate,
                    :legal_address,
                    :postal_address,
                    :city_id,
                    :created_by_user_id)";

        $stmt = $this->pdo->prepare($sql);
        try {
            $stmt->execute([
                'name' => $data['name'],
                'legal_form' => $data['legal_form'],
                'edrpou' => $data['edrpou'],
                'inn' => $data['inn'],
                'vat_certificate' => $data['vat_certificate'] ?? null,
                'legal_address' => $data['legal_address'],
                'postal_address' => $data['postal_address'],
                'city_id' => $data['city_id'],
                'created_by_user_id' => $createdByUserId
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new Exception('Контрагент с такими реквизитами уже существует');
            }
            throw $e;
        }
    }

    /**
     * @return PDO
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT id, name, legal_form, edrpou, inn, vat_certificate, legal_address, postal_address, city_id, created_by_user_id 
                                        FROM counterparties c ORDER BY c.id DESC");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM counterparties WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}