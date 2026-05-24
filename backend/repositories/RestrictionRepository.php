<?php
class RestrictionRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    
    public function getAll() {
        return $this->conn
            ->query("SELECT * FROM restrictions ORDER BY name ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function findById($id) {
        $stmt = $this->conn->prepare("
            SELECT * FROM restrictions WHERE id=:id
        ");
        $stmt->execute([":id"=>$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

  
    public function create($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO restrictions (name)
            VALUES (:name)
        ");

        return $stmt->execute([
            ":name" => $data['name']
        ]);
    }

   
    public function update($id, $data) {
        $stmt = $this->conn->prepare("
            UPDATE restrictions SET name=:name WHERE id=:id
        ");

        return $stmt->execute([
            ":name" => $data['name'],
            ":id" => $id
        ]);
    }


    public function delete($id) {
        $stmt = $this->conn->prepare("
            DELETE FROM restrictions WHERE id=:id
        ");

        return $stmt->execute([":id"=>$id]);
    }
}