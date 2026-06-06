<?php

class StatisticsRepository
{
    private PDO $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    
    public function topDrinks(): array
    {
        $stmt = $this->conn->query("
            SELECT
                d.id,
                d.name,
                COUNT(DISTINCT w.user_id) +
                COUNT(DISTINCT td.user_id) AS popularity
            FROM drinks d
            LEFT JOIN wishlist w
                ON d.id = w.drink_id
            LEFT JOIN tried_drinks td
                ON d.id = td.drink_id
            GROUP BY d.id, d.name
            ORDER BY popularity DESC, d.name ASC
            LIMIT 10
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function topCategories(): array
    {
        $stmt = $this->conn->query("
            SELECT
                c.id,
                c.name,
                COUNT(*) AS total
            FROM categories c
            JOIN user_favorite_categories ufc
                ON c.id = ufc.category_id
            GROUP BY c.id, c.name
            ORDER BY total DESC, c.name ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function topProviders(): array
    {
        $stmt = $this->conn->query("
            SELECT
                p.id,
                p.name,
                COUNT(*) AS total
            FROM providers p
            JOIN user_favorite_providers ufp
                ON p.id = ufp.provider_id
            GROUP BY p.id, p.name
            ORDER BY total DESC, p.name ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function topRated(): array
    {
        $stmt = $this->conn->query("
            SELECT
                d.id,
                d.name,
                ROUND(AVG(td.rating), 2) AS avg_rating,
                COUNT(td.id) AS votes
            FROM drinks d
            JOIN tried_drinks td
                ON d.id = td.drink_id
            GROUP BY d.id, d.name
            HAVING COUNT(td.id) >= 5
            ORDER BY avg_rating DESC, votes DESC
            LIMIT 10
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function topIngredients(): array
    {
        $stmt = $this->conn->query("
            SELECT
                i.id,
                i.name,
                COUNT(*) AS total
            FROM ingredients i
            JOIN user_favorite_ingredients ufi
                ON i.id = ufi.ingredient_id
            GROUP BY i.id, i.name
            ORDER BY total DESC, i.name ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function mostAvoidedIngredients(): array
    {
        $stmt = $this->conn->query("
            SELECT
                i.id,
                i.name,
                COUNT(*) AS total
            FROM ingredients i
            JOIN user_avoided_ingredients uai
                ON i.id = uai.ingredient_id
            GROUP BY i.id, i.name
            ORDER BY total DESC, i.name ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function topRestrictions(): array
    {
        $stmt = $this->conn->query("
            SELECT
                r.id,
                r.name,
                COUNT(*) AS total
            FROM restrictions r
            JOIN user_restrictions ur
                ON r.id = ur.restriction_id
            GROUP BY r.id, r.name
            ORDER BY total DESC, r.name ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}