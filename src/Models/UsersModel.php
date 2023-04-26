<?php

namespace App\Models;

use App\Utils\DB;

class UsersModel
{
    private $db;

    public function __construct()
    {
        $this->db = DB::Connection();
    }

    public function getUserIdByLogin(string $login)
    {
        $query = "SELECT id FROM users WHERE login = :login";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['login' => $login]);

        return $stmt->fetch();
    }

    public function loginExists(string $login) : bool
    {
        $query = "select if(exists(select 1 from users where login = :login), 1, 0)";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['login' => $login]);

        return array_values($stmt->fetch())[0];
    }

    public function addNewUser(string $login, string $password)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (login, hashed_password) VALUES (:login, :password)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            'login' => $login,
            'password' => $hashed_password
        ]);
    }

    public function getUserHashedPassword(string $login) : string
    {
        $id = $this->getUserIdByLogin($login)['id'];
        $query = "SELECT hashed_password FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch()['hashed_password'];
    }
}