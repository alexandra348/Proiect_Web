<?php

require_once __DIR__ . '/../config/database.php';

class Provider {
    private $conn;
    private $table = "providers";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    //CREATE provider (register)
    public function create($name, $email, $password, $type, $location) {
        $sql = "INSERT INTO " . $this->table . " 
                (name, email, password, type, location)
                VALUES (:name, :email, :password, :type, :location)";

        $stmt = $this->conn->prepare($sql);

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        return $stmt->execute([
            ":name" => $name,
            ":email" => $email,
            ":password" => $hashedPassword,
            ":type" => $type,
            ":location" => $location
        ]);
    }

    //GET provider by email (login)
    public function findByEmail($email) {
        $sql = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);

        $stmt->execute([":email" => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //GET provider by ID
    public function findById($id) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        $stmt->execute([":id" => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}