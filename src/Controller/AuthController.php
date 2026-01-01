<?php

class AuthController
{
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orgInn = $_POST['organization_inn'];
            $email = $_POST['email'];
            $pass = $_POST['password'];

            if (empty($orgInn) || empty($email) || empty($pass)) {
                echo 'ERROR';
                return;
            }

            $userRepo = new UserRepository();
            $orgRepo = new OrganizationRepository();

            if ($userRepo->findByEmail($email)) {
                echo 'Email exists';
                return;
            }

            $org = $orgRepo->findByInn($orgInn);

            if (!$org) {
                echo 'Organization not found';
                return;
            }

            db()->beginTransaction();

            try {
                $userRepo->create($org['id'], $email, $pass);

                db()->commit();

                header('Location: /login');
                exit();

            } catch (Exception $e) {
                db()->rollBack();
                echo $e->getMessage();
                return;
            }
        }
        require __DIR__.'/../../views/register.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = $_POST['email'];
            $pass = $_POST['password'];

            if (empty($email) || empty($pass)) {
                echo 'ERROR';
                return;
            }

            $userRepo = new UserRepository();
            $user = $userRepo->findByEmail($email);

            if (!$user) {
                echo 'Invalid email';
                return;
            }

            if (!password_verify($pass, $user['password'])) {
                echo 'Invalid password';
                return;
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['organization_id'] = $user['organization_id'];

            header('Location: /counterparties');
            exit();
        }

        require __DIR__.'/../../views/login.php';
    }

    public function logout()
    {
        session_destroy();

        header('Location: /login');
        exit();
    }
}