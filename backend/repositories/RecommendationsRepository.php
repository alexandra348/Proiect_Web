<?php
class RecommendationsRepository {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getRecommended(int $user_id)
    {
        $query = "
            SELECT
                d.*,

                (
                    COALESCE(cat.score, 0)
                    + COALESCE(ing.score, 0)
                    + COALESCE(prov.score, 0)
                    + COALESCE(hist.score, 0)
                    + COALESCE(wl.score, 0)
                    + COALESCE(pop.score, 0)
                    + COALESCE(price.score, 0)
                ) AS recommendation_score

            FROM drinks d

            LEFT JOIN (
                SELECT category_id, 20 AS score
                FROM user_favorite_categories
                WHERE user_id = :uid
            ) cat ON cat.category_id = d.category_id

            LEFT JOIN (
                SELECT
                    di.drink_id,
                    COUNT(*) * 5 AS score
                FROM drink_ingredients di
                JOIN user_favorite_ingredients ufi
                    ON di.ingredient_id = ufi.ingredient_id
                WHERE ufi.user_id = :uid
                GROUP BY di.drink_id
            ) ing ON ing.drink_id = d.id

            LEFT JOIN (
                SELECT provider_id, 15 AS score
                FROM user_favorite_providers
                WHERE user_id = :uid
            ) prov ON prov.provider_id = d.provider_id

            LEFT JOIN (
                SELECT
                    d2.category_id,
                    AVG(td.rating) * 4 AS score
                FROM tried_drinks td
                JOIN drinks d2 ON d2.id = td.drink_id
                WHERE td.user_id = :uid
                GROUP BY d2.category_id
            ) hist ON hist.category_id = d.category_id

            LEFT JOIN (
                SELECT drink_id, 30 AS score
                FROM wishlist
                WHERE user_id = :uid
            ) wl ON wl.drink_id = d.id

            LEFT JOIN (
                SELECT
                    drink_id,
                    AVG(rating) * 2 AS score
                FROM tried_drinks
                GROUP BY drink_id
            ) pop ON pop.drink_id = d.id

            LEFT JOIN (
                SELECT
                    id,
                    GREATEST(0, 20 - price) AS score
                FROM drinks
            ) price ON price.id = d.id

            WHERE d.id NOT IN (
                SELECT drink_id
                FROM tried_drinks
                WHERE user_id = :uid
            )

            AND d.id NOT IN (
                SELECT di.drink_id
                FROM drink_ingredients di
                JOIN user_avoided_ingredients uai
                    ON di.ingredient_id = uai.ingredient_id
                WHERE uai.user_id = :uid
            )

            ORDER BY recommendation_score DESC
            LIMIT 10
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':uid', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}