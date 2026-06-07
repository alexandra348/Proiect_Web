<?php
class ProviderRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO providers (name, email, password, type,address, city)
                  VALUES (:name, :email, :password, :type, :address, :city)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ":name" => $data['name'],
            ":email" => $data['email'],
            ":password" => password_hash($data['password'], PASSWORD_DEFAULT),
            ":type" => $data['type'],
            ":address" => $data['address'],
            ":city" => $data['city']
        ]);
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM providers WHERE email = :email LIMIT 1");
        $stmt->execute([":email" => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM providers WHERE id = :id LIMIT 1");
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   public function getAll() {
    $stmt = $this->conn->query("
        SELECT id, name, email, type, address, city
        FROM providers
        ORDER BY id
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  
    public function update($id, $data) {

        if(empty($data['password'])) {
             $stmt = $this->conn->prepare("
                UPDATE providers 
                SET name = :name,
                    email = :email,
                    type = :type,
                    address = :address,
                    city = :city
                WHERE id = :id
            ");

            return $stmt->execute([
                ":name" => $data['name'],
                ":email" => $data['email'],
                ":type" => $data['type'],
                ":address" => $data['address'],
                ":city" => $data['city'],
                ":id" => $id
            ]);
        }
        else {
            $stmt = $this->conn->prepare("
                UPDATE providers 
                SET name = :name,
                    email = :email,
                    password = :password,
                    type = :type,
                    address = :address,
                    city = :city
                WHERE id = :id
            ");

            return $stmt->execute([
                ":name" => $data['name'],
                ":email" => $data['email'],
                ":password" => $data['password'],
                ":type" => $data['type'],
                ":address" => $data['address'],
                ":city" => $data['city'],
                ":id" => $id
            ]);
        }
    
   }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM providers WHERE id=:id");
        return $stmt->execute([":id" => $id]);
    }
}