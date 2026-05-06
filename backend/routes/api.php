<?php

require_once __DIR__ . '/../config/database.php';

require_once __DIR__ . '/../controllers/DrinkController.php';
require_once __DIR__ . '/../controllers/CategoryController.php';
require_once __DIR__ . '/../controllers/IngredientController.php';
require_once __DIR__ . '/../controllers/ProviderController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/RestrictionController.php';
require_once __DIR__ . '/../controllers/PreferenceController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/StatisticsController.php';
require_once __DIR__ . '/../controllers/RecommendationsController.php';


function sendResponse($response) {
    http_response_code($response["status"] ?? 200);
    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
}


$db = (new Database())->connect();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];


if ($uri === "/api/drinks" && $method === "GET") {
    $controller = new DrinkController($db);
    
    if (isset($_GET['id'])) {
        sendResponse($controller->getDrinkById($_GET['id']));
    } else {
        sendResponse($controller->getAllDrinks());
    }
    exit;
}

if ($uri === "/api/drinks" && $method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $controller = new DrinkController($db);
    sendResponse($controller->create($data));
    exit;
}

if ($uri === "/api/drinks" && $method === "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);

    $controller = new DrinkController($db);
    sendResponse($controller->delete($data['id']));
    exit;
}



if ($uri === "/api/categories" && $method === "GET") {
    $controller = new CategoryController($db);
    sendResponse($controller->getAllCategories());
    exit;
}

if ($uri === "/api/categories" && $method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $controller = new CategoryController($db);
    sendResponse($controller->create($data));
    exit;
}

if ($uri === "/api/categories" && $method === "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);

    $controller = new CategoryController($db);
    sendResponse($controller->delete($data['id']));
    exit;
}



if ($uri === "/api/ingredients" && $method === "GET") {
    $controller = new IngredientController($db);
    sendResponse($controller->getAllIngredients());
    exit;
}

if ($uri === "/api/ingredients" && $method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $controller = new IngredientController($db);
    sendResponse($controller->create($data));
    exit;
}



if ($uri === "/api/providers" && $method === "GET") {
    $controller = new ProviderController($db);

    if (isset($_GET['id'])) {
        sendResponse($controller->getProviderById($_GET['id']));
    } else {
        sendResponse($controller->getAllProviders());
    }
    exit;
}

if ($uri === "/api/providers" && $method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $controller = new ProviderController($db);
    sendResponse($controller->create($data));
    exit;
}

if ($uri === "/api/users" && $method === "GET") {
    $controller = new UserController($db);
    if (isset($_GET['id']))
        sendResponse($controller->getUserById($_GET['id']));
    else
        sendResponse($controller->getAllUsers());
    exit;
}

if ($uri === "/api/users" && $method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $controller = new UserController($db);
    sendResponse($controller->register($data));
    exit;
}



if ($uri === "/api/users" && $method === "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);

    $controller = new UserController($db);
    sendResponse($controller->update($data['id'], $data));
    exit;
}

if ($uri === "/api/users" && $method === "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);

    $controller = new UserController($db);
    sendResponse($controller->delete($data['id']));
    exit;
}


if ($uri === "/api/restrictions" && $method === "GET") {
    $controller = new RestrictionController($db);
    sendResponse($controller->getAllRestrictions());
    exit;
}

if ($uri === "/api/restrictions" && $method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $controller = new RestrictionController($db);
    sendResponse($controller->create($data));
    exit;
}



if ($uri === "/api/login" && $method === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $controller = new AuthController($db);
    sendResponse($controller->login($data));
    exit;
}

if ($uri === "/api/statistics" && $method === "GET") {
    $controller = new StatisticsController($db);
    sendResponse($controller->dashboard());
    exit;
}

if ($uri === "/api/recommendations" && $method === "GET") {

    $user_id = $_GET['user_id'] ?? null;

    $controller = new RecommendationsController($db);
    sendResponse($controller->getRecommendations($user_id));
    exit;
}


http_response_code(404);
echo json_encode(["error" => "Route not found"]);