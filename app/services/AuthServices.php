<?php
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../storage/json/JsonStorage.php';

class AuthService
{
    private JsonStorage $users;

    public function __construct()
    {
        $this->users = new JsonStorage('users.json');
        Session::start();
    }

    public function register(string $name, string $email, string $password): bool
    {
        foreach ($this->users->all() as $user) {
            if ($user['email'] === $email) return false;
        }

        $this->users->insert([
            'id' => uniqid(),
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return true;
    }

    public function login(string $email, string $password): bool
    {
        foreach ($this->users->all() as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                Session::login([
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email']
                ]);
                return true;
            }
        }
        return false;
    }

    public function logout(): void
    {
        Session::logout();
    }
}