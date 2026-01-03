<?php

class CounterpartyController
{
    private CounterpartyRepository $repository;
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->repository = new CounterpartyRepository($pdo);
    }

    public function showCreateForm(array $data = [], array $errors = [])
    {
        require_once __DIR__ . '/../../views/counterparties/create.php';
    }

    public function createCounterparty()
    {
       $data = $_POST;
       $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Название компании обязательно';
        }

        if (empty($data['edrpou'])) {
            $errors['edrpou'] = 'ЕДРПОУ обязателен';
        }

        if (empty($data['inn'])) {
            $errors['inn'] = 'ИНН обязателен';
        }

        if (empty($data['legal_address'])) {
            $errors['legal_address'] = 'Юр. адрес обязателен';
        }

        if (empty($data['postal_address'])) {
            $errors['postal_address'] = 'Почтовый адрес обязателен';
        }
        if (empty($data['city_id'])) {
            $errors['city_id'] = 'Город не выбран';
        }

        if ($errors) {
            $this->showCreateForm($data, $errors);
            return;
        }

        try {
            if (!empty($data['id'])) {
                $this->repository->update((int)$data['id'], $data);
            } else {
                $this->repository->create($data, $_SESSION['user_id']);
            }
        } catch (Exception $e) {
            $errors['edrpou'] = $e->getMessage();
            $this->showCreateForm($data, $errors);
            return;
        }

        header('Location: /counterparties');
        exit();
    }

    public function list()
    {
        $counterparties = $this->repository->getAll();
        require __DIR__ . '/../../views/counterparties/list.php';
    }

    public function editForm()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo 'ID не передан';
            exit();
        }

        $counterparty = $this->repository->getById((int) $id);

        if (!$counterparty) {
            echo 'контр не найден';
            exit();
        }

        $data = $counterparty;
        $errors = [];

        require_once __DIR__ . '/../../views/counterparties/create.php';
    }
}