<?php

require_once '../config/Database.php';
class User extends Database {

    const TABLE_NAME= "users";

    public int $id;
    public string $username;
    public string $password;
    public string $role;

    public function __construct() {
        $this->conn = $this->getConnection();
    }

    public function register() {



        $query = "INSERT INTO " . self::TABLE_NAME . " (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->role = htmlspecialchars(strip_tags($this->role));

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);

        return $stmt->execute();
    }

    public function login() {

        $query = "SELECT * FROM " . self::TABLE_NAME . " WHERE username = :username";

        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$user || !password_verify($this->password, $user['password'])) {
            return false;
        }

        $this->id = $user['id'];
        $this->role = $user['role'];

        return false;
    }
}