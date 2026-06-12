<?php
class DrinkRepository
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function findById($id)
    {
        $query = "
        SELECT
            d.*,
            c.name AS category,
            p.name AS provider,
            p.address,
            p.email,
            p.type,
            p.city
        FROM drinks d
        JOIN categories c
            ON d.category_id = c.id
        JOIN providers p
            ON d.provider_id = p.id
        WHERE d.id = :id
        LIMIT 1
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([":id" => $id]);

        $drink = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$drink) {
            return null;
        }


        $ingredientsQuery = "
        SELECT
            i.id,
            i.name
        FROM drink_ingredients di
        JOIN ingredients i
            ON di.ingredient_id = i.id
        WHERE di.drink_id = :id
    ";

        $stmt = $this->conn->prepare($ingredientsQuery);
        $stmt->execute([":id" => $id]);

        $drink["ingredients"] = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $reviewsQuery = "
        SELECT
            td.rating,
            td.notes,
            u.name AS user_name
        FROM tried_drinks td
        JOIN users u
            ON td.user_id = u.id
        WHERE td.drink_id = :id
    ";

        $stmt = $this->conn->prepare($reviewsQuery);
        $stmt->execute([":id" => $id]);

        $drink["reviews"] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $drink;
    }

    public function getAll()
    {
        $query = "
            SELECT d.*, p.name as provider, c.name as category
            FROM drinks d
            LEFT JOIN providers p ON d.provider_id = p.id
            LEFT JOIN categories c ON d.category_id = c.id
        ";

        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByProvider($provider_id)
    {
        $stmt = $this->conn->prepare("SELECT d.*, c.name category FROM drinks d JOIN categories c
        ON d.category_id = c.id WHERE provider_id=:id");
        $stmt->execute([":id" => $provider_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "
            INSERT INTO drinks
            (
                name,
                price,
                provider_id,
                category_id,
                image_url
            )
            VALUES
            (
                :name,
                :price,
                :provider_id,
                :category_id,
                :image_url
            )
            RETURNING id
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);

        return $stmt->fetchColumn();
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

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM drinks WHERE id=:id");
        return $stmt->execute([":id" => $id]);
    }

    public function search(string $term): array
    {
        $query = "
            SELECT DISTINCT d.*,
                c.name AS category,
                p.name AS provider,
                p.city
            FROM drinks d

            JOIN categories c
                ON d.category_id = c.id

            JOIN providers p
                ON d.provider_id = p.id

            LEFT JOIN drink_ingredients di
                ON d.id = di.drink_id

            LEFT JOIN ingredients i
                ON di.ingredient_id = i.id

            WHERE
                LOWER(d.name) LIKE LOWER(:term)
                OR LOWER(p.name) LIKE LOWER(:term)
                OR LOWER(p.city) LIKE LOWER(:term)
                OR LOWER(c.name) LIKE LOWER(:term)
                OR LOWER(i.name) LIKE LOWER(:term)

            ORDER BY d.name
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ":term" => "%{$term}%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
