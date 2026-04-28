<?php

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/DrinkController.php';

header("Content-Type: application/json");

$auth = new AuthController();
$drink = new DrinkController();

$method = $_SERVER['REQUEST_METHOD'];
$path = $_GET['path'] ?? '';


// LOGIN
if ($path == "login" && $method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    echo json_encode($auth->login($data['email'], $data['password']));
    exit;
}

// REGISTER USER
if ($path == "register/user" && $method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    echo json_encode($auth->registerUser($data));
    exit;
}

// REGISTER PROVIDER
if ($path == "register/provider" && $method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    echo json_encode($auth->registerProvider($data));
    exit;
}

// GET DRINKS
if ($path == "drinks" && $method == "GET") {
    echo json_encode($drink->getAll());
    exit;
}

// CREATE DRINK
if ($path == "drinks" && $method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    echo json_encode($drink->create($data));
    exit;
}

// DELETE DRINK
if ($path == "drinks/delete" && $method == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    echo json_encode($drink->delete($data['id']));
    exit;
}

// DEFAULT
echo json_encode(["status" => "error", "message" => "Route not found"]);