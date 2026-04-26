<?php

require_once __DIR__ . '/../backend/config/database.php';

header("Content-Type: application/json");

$db = new Database();
$conn = $db->connect();

if ($conn) {
    echo json_encode([
        "status" => "success",
        "message" => "Connected to PostgreSQL!"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Connection failed"
    ]);
}