<?php

class CounterpartyController
{
    private CounterpartyRepository $repository;

    public function __construct(PDO $pdo)
    {
        $this->repository = new CounterpartyRepository($pdo);
    }

    public function showCreateForm(array $data = [], array $errors = [])
    {
        require_once __DIR__ . '/../../views/counterparties/create.php';
    }

    public function createCounterparty()
    {
        $validator = new CounterpartyValidator();
        [$data, $errors] = $validator->validate($_POST);

        if ($errors) {
            $this->showCreateForm($data, $errors);
            return;
        }

        try {
            $this->repository->save($data, $_SESSION['user_id']);
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
        $q = $_GET['q'] ?? null;

        $counterparties = $this->repository->getAll($q);

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