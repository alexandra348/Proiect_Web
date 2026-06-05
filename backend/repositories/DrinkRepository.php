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
        $query = "INSERT INTO drinks (name, price, provider_id, category_id, image_url)
                  VALUES (:name, :price, :provider_id, :category_id, :image_url)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['name'])) {
            $fields[] = 'name = :name';
            $params[':name'] = $data['name'];
        }

        if (isset($data['price'])) {
            $fields[] = 'price = :price';
            $params[':price'] = $data['price'];
        }

        if (isset($data['image_url'])) {
            $fields[] = 'image_url = :image_url';
            $params[':image_url'] = $data['image_url'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "
            UPDATE drinks
            SET " . implode(', ', $fields) . "
            WHERE id = :id
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM drinks WHERE id=:id");
        return $stmt->execute([":id" => $id]);
    }
}