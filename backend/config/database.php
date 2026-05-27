<?php

class Database {
    public function connect() {
        $host = getenv('DB_HOST');
        $db   = getenv('POSTGRES_DB');
        $user = getenv('POSTGRES_USER');
        $pass = getenv('POSTGRES_PASSWORD');

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