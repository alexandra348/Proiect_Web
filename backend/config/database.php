<?php

class Database {
    public function connect() {
        $host = getenv('DB_HOST');
        $db   = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASSWORD');

        try {
            $conn = new PDO(
                "pgsql:host=$host;dbname=$db",
                $user,
                $pass
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;

        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
            return null;
        }
    }
}