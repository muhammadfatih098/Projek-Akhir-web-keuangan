<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../services/AuthServices.php';
require_once __DIR__ . '/../core/Session.php';

class AuthController
{
    private AuthService $auth;

    public function __construct()
    {
        $this->auth = new AuthService();
    }
    
    public function login(): bool
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($this->auth->login($email, $password)) {
            return true;
        }

        return false;
    }
    
    public function logout(): void
    {
        $this->auth->logout();
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
    
    public function signup(): bool
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        return $this->auth->register($name, $email, $password);
    }
}