<?php
class DrinkRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM drinks WHERE id=:id LIMIT 1");
        $stmt->execute([":id" => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $query = "
            SELECT d.*, p.name as provider, c.name as category
            FROM drinks d
            LEFT JOIN providers p ON d.provider_id = p.id
            LEFT JOIN categories c ON d.category_id = c.id
        ";

        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByProvider($provider_id) {
        $stmt = $this->conn->prepare("SELECT * FROM drinks WHERE provider_id=:id");
        $stmt->execute([":id" => $provider_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO drinks (name, price, provider_id, category_id)
                  VALUES (:name, :price, :provider_id, :category_id)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("
            UPDATE drinks SET name=:name, price=:price WHERE id=:id
        ");

        return $stmt->execute([
            ":name" => $data['name'],
            ":price" => $data['price'],
            ":id" => $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM drinks WHERE id=:id");
        return $stmt->execute([":id" => $id]);
    }
}