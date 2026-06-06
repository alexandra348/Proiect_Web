<?php
class IngredientRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

   
    public function getAll() {
        return $this->conn
            ->query("SELECT * FROM ingredients ORDER BY name ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIngredientsByDrink($drinkId) {
        $stmt = $this->conn->prepare("SELECT i.id, i.name FROM ingredients i JOIN drink_ingredients di 
                                      ON i.id = di.ingredient_id WHERE di.drink_id=:drinkId");
        $stmt->execute([":drinkId"=>$drinkId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM ingredients WHERE id=:id");
        $stmt->execute([":id"=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function create($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO ingredients (name)
            VALUES (:name)
        ");

        return $stmt->execute([
            ":name" => $data['name']
        ]);
    }

    
    public function update($id, $data) {
        $stmt = $this->conn->prepare("
            UPDATE ingredients SET name=:name WHERE id=:id
        ");

        return $stmt->execute([
            ":name" => $data['name'],
            ":id" => $id
        ]);
    }

    
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM ingredients WHERE id=:id");
        return $stmt->execute([":id"=>$id]);
    }
}