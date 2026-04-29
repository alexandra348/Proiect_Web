<?php
class Preference {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Wishlist
    public function addToWishlist($user_id, $drink_id) {
        $stmt = $this->conn->prepare("
            INSERT INTO wishlist (user_id, drink_id)
            VALUES (:u, :d)
        ");
        return $stmt->execute([":u"=>$user_id, ":d"=>$drink_id]);
    }

    public function getWishlist($user_id) {
        $stmt = $this->conn->prepare("
            SELECT d.* FROM wishlist w
            JOIN drinks d ON w.drink_id = d.id
            WHERE w.user_id = :id
        ");
        $stmt->execute([":id"=>$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tried drinks
    public function addTried($user_id, $drink_id, $rating, $notes) {
        $stmt = $this->conn->prepare("
            INSERT INTO tried_drinks (user_id, drink_id, rating, notes)
            VALUES (:u, :d, :r, :n)
        ");
        return $stmt->execute([
            ":u"=>$user_id,
            ":d"=>$drink_id,
            ":r"=>$rating,
            ":n"=>$notes
        ]);
    }

    public function getTriedList($user_id) {
        $stmt = $this->conn->prepare("
            SELECT d.* FROM tried_drinks w
            JOIN drinks d ON w.drink_id = d.id
            WHERE w.user_id = :id
        ");
        $stmt->execute([":id"=>$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Favorite Categories

    public function addFavoriteCategory($user_id, $category_id) {
        $stmt = $this->conn->prepare("
            INSERT INTO user_favorite_categories (user_id, category_id)
            VALUES (:u, :c)
            ON CONFLICT DO NOTHING
        ");
        return $stmt->execute([":u"=>$user_id, ":c"=>$category_id]);
    }

    public function getFavoriteCategories($user_id) {
        $stmt = $this->conn->prepare("
            SELECT c.* FROM user_favorite_categories ufc
            JOIN categories c ON ufc.category_id = c.id
            WHERE ufc.user_id = :id
        ");
        $stmt->execute([":id"=>$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    //  FAVORITE INGREDIENTS

    public function addFavoriteIngredient($user_id, $ingredient_id) {
        $stmt = $this->conn->prepare("
            INSERT INTO user_favorite_ingredients (user_id, ingredient_id)
            VALUES (:u, :i)
            ON CONFLICT DO NOTHING
        ");
        return $stmt->execute([":u"=>$user_id, ":i"=>$ingredient_id]);
    }

    public function getFavoriteIngredients($user_id) {
        $stmt = $this->conn->prepare("
            SELECT i.* FROM user_favorite_ingredients ufi
            JOIN ingredients i ON ufi.ingredient_id = i.id
            WHERE ufi.user_id = :id
        ");
        $stmt->execute([":id"=>$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    //  AVOIDED INGREDIENTS

    public function addAvoidedIngredient($user_id, $ingredient_id) {
        $stmt = $this->conn->prepare("
            INSERT INTO user_avoided_ingredients (user_id, ingredient_id)
            VALUES (:u, :i)
            ON CONFLICT DO NOTHING
        ");
        return $stmt->execute([":u"=>$user_id, ":i"=>$ingredient_id]);
    }

    public function getAvoidedIngredients($user_id) {
        $stmt = $this->conn->prepare("
            SELECT i.* FROM user_avoided_ingredients uai
            JOIN ingredients i ON uai.ingredient_id = i.id
            WHERE uai.user_id = :id
        ");
        $stmt->execute([":id"=>$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  
    //  RESTRICTIONS

    public function addUserRestriction($user_id, $restriction_id) {
        $stmt = $this->conn->prepare("
            INSERT INTO user_restrictions (user_id, restriction_id)
            VALUES (:u, :r)
            ON CONFLICT DO NOTHING
        ");
        return $stmt->execute([":u"=>$user_id, ":r"=>$restriction_id]);
    }

    public function getUserRestrictions($user_id) {
        $stmt = $this->conn->prepare("
            SELECT r.* FROM user_restrictions ur
            JOIN restrictions r ON ur.restriction_id = r.id
            WHERE ur.user_id = :id
        ");
        $stmt->execute([":id"=>$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // FAVORITE PROVIDERS

    public function addFavoriteProvider($user_id, $provider_id) {
        $stmt = $this->conn->prepare("
            INSERT INTO user_favorite_providers (user_id, provider_id)
            VALUES (:u, :p)
            ON CONFLICT DO NOTHING
        ");
        return $stmt->execute([":u"=>$user_id, ":p"=>$provider_id]);
    }

    public function getFavoriteProviders($user_id) {
        $stmt = $this->conn->prepare("
            SELECT p.* FROM user_favorite_providers ufp
            JOIN providers p ON ufp.provider_id = p.id
            WHERE ufp.user_id = :id
        ");
        $stmt->execute([":id"=>$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}