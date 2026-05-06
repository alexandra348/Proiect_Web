<?php
class Statistics {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    
    public function topDrinks() {
        $stmt = $this->conn->query("
            SELECT d.id, d.name, COUNT(w.drink_id) as total
            FROM drinks d
            JOIN wishlist w ON d.id = w.drink_id
            GROUP BY d.id, d.name
            ORDER BY total DESC
            LIMIT 10
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function topCategories() {
        $stmt = $this->conn->query("
            SELECT c.id, c.name, COUNT(d.id) as total
            FROM categories c
            JOIN drinks d ON c.id = d.category_id
            GROUP BY c.id, c.name
            ORDER BY total DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function topProviders() {
        $stmt = $this->conn->query("
            SELECT p.id, p.name, COUNT(d.id) as total
            FROM providers p
            JOIN drinks d ON p.id = d.provider_id
            GROUP BY p.id, p.name
            ORDER BY total DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function topRated() {
        $stmt = $this->conn->query("
            SELECT d.id, d.name, ROUND(AVG(td.rating),2) as avg_rating
            FROM drinks d
            JOIN tried_drinks td ON d.id = td.drink_id
            GROUP BY d.id, d.name
            ORDER BY avg_rating DESC
            LIMIT 10
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}