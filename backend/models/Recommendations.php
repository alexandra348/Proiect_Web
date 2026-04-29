<?php
class Recommendations {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getRecommended($user_id) {

        // - exclude avoided ingredients
        // - exclude restrictions (simplificat)
        // - prioritize favorite categories

        $query = "
            SELECT DISTINCT d.*
            FROM drinks d
            WHERE d.id NOT IN (
                SELECT drink_id FROM drink_ingredients di
                JOIN user_avoided_ingredients uai 
                ON di.ingredient_id = uai.ingredient_id
                WHERE uai.user_id = :uid
            )
            ORDER BY d.price ASC
            LIMIT 20
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([":uid" => $user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}