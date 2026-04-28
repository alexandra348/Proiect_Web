<?php

require_once __DIR__ . '/../config/database.php';

class Drink {
    private $conn;
    private $table = "drinks";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    //CREATE drink
    public function create($name, $price, $provider_id, $category_id) {
        $sql = "INSERT INTO " . $this->table . " 
                (name, price, provider_id, category_id)
                VALUES (:name, :price, :provider_id, :category_id)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":name" => $name,
            ":price" => $price,
            ":provider_id" => $provider_id,
            ":category_id" => $category_id
        ]);
    }

    //GET all drinks
    public function getAll() {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //GET drinks by location (join cu providers)
    public function getByLocation($location) {
        $sql = "SELECT d.* 
                FROM drinks d
                JOIN providers p ON d.provider_id = p.id
                WHERE p.location = :location";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":location" => $location]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //UPDATE drink
    public function update($id, $name, $price) {
        $sql = "UPDATE " . $this->table . "
                SET name = :name, price = :price
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":name" => $name,
            ":price" => $price
        ]);
    }

    // DELETE drink
    public function delete($id) {
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([":id" => $id]);
    }
}