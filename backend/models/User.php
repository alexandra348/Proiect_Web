<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO users (name, email, password)
                  VALUES (:name, :email, :password)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ":name" => $data['name'],
            ":email" => $data['email'],
            ":password" => password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email=:email");
        $stmt->execute([":email" => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT id, name, email FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}