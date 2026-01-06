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
    public function getAll(?string $q = null): array
    {
        $sql = "
                SELECT
                    counterparties.id,
                    counterparties.name,
                    counterparties.legal_form,
                    counterparties.edrpou,
                    counterparties.inn,
                    counterparties.vat_certificate,
                    counterparties.legal_address,
                    counterparties.postal_address,
                    
                    cities.name AS city_name,
                    countries.name AS country_name,
                    regions.name AS region_name,
                    users.email AS created_by_email
                
                FROM counterparties
                    LEFT JOIN cities ON cities.id = counterparties.city_id
                    LEFT JOIN regions ON regions.id = cities.region_id
                    LEFT JOIN countries ON countries.id = regions.country_id
                    LEFT JOIN users ON users.id = counterparties.created_by_user_id
                    ";

        $params = [];

        if ($q) {
            $sql .= " WHERE counterparties.name LIKE :q OR counterparties.edrpou LIKE :q";
            $params = ['q' => '%' . $q . '%'];
        }

        $sql .= " ORDER BY counterparties.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
SELECT
        c.*,
        ci.name AS city_name
FROM counterparties c
    LEFT JOIN cities ci ON ci.id = c.city_id
WHERE c.id = :id
LIMIT 1
");

        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function update(int $id, array $data): void
    {
        $sql = "UPDATE counterparties SET 
                          name = :name,
                          legal_form = :legal_form,
                          edrpou = :edrpou,
                          inn = :inn,
                          vat_certificate = :vat_certificate,
                          legal_address = :legal_address,
                          postal_address = :postal_address,
                          city_id = :city_id
                          WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'legal_form' => $data['legal_form'],
            'edrpou' => $data['edrpou'],
            'inn' => $data['inn'],
            'vat_certificate' => $data['vat_certificate'] ?? null,
            'legal_address' => $data['legal_address'],
            'postal_address' => $data['postal_address'],
            'city_id' => $data['city_id']]);
    }
    
    public function save(array $data, int $userId): void
    {
        if (!empty($data['id'])) {
            $this->update($data['id'], $data);
            return;
        }

        $this->create($data, $userId);
    }
}