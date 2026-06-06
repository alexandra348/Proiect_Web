<?php

require_once __DIR__ . '/../config/database.php';

/* CONTROLLERS */
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

/* SERVICES */
require_once __DIR__ . '/../services/DrinkService.php';
require_once __DIR__ . '/../services/CategoryService.php';
require_once __DIR__ . '/../services/IngredientService.php';
require_once __DIR__ . '/../services/ProviderService.php';
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/RestrictionService.php';
require_once __DIR__ . '/../services/PreferenceService.php';
require_once __DIR__ . '/../services/StatisticsService.php';
require_once __DIR__ . '/../services/RecommendationsService.php';

/* REPOSITORIES */
require_once __DIR__ . '/../repositories/DrinkRepository.php';
require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../repositories/ProviderRepository.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../repositories/RestrictionRepository.php';
require_once __DIR__ . '/../repositories/PreferenceRepository.php';
require_once __DIR__ . '/../repositories/StatisticsRepository.php';
require_once __DIR__ . '/../repositories/RecommendationsRepository.php';

require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/RoleMiddleware.php';
require_once __DIR__ . '/../middleware/Authorization.php';

function sendResponse($response){
    http_response_code($response["status"] ?? 200);

    header("Content-Type: application/json");

    echo json_encode($response);

    exit;
}


$db=(new Database())->connect();

$uri=parse_url(
    $_SERVER["REQUEST_URI"],
    PHP_URL_PATH
);

$method=$_SERVER["REQUEST_METHOD"];

if (
    strpos($_SERVER['CONTENT_TYPE'] ?? '', 'multipart/form-data') !== false
) {
    $data = $_POST;
} else {
    $data = json_decode(file_get_contents("php://input"), true);
}


/* CONTROLLERS */

$drinkController=new DrinkController(
    new DrinkService(
        new DrinkRepository($db)
    )
);

$categoryController=new CategoryController(
    new CategoryService(
        new CategoryRepository($db)
    )
);

$ingredientController=new IngredientController(
    new IngredientService(
        new IngredientRepository($db)
    )
);

$providerController=new ProviderController(
    new ProviderService(
        new ProviderRepository($db)
    )
);

$userController=new UserController(
    new UserService(
        new UserRepository($db)
    )
);

$restrictionController=new RestrictionController(
    new RestrictionService(
        new RestrictionRepository($db)
    )
);

$preferenceController=new PreferenceController(
    new PreferenceService(
        new PreferenceRepository($db)
    )
);

$statisticsController=
new StatisticsController(
    new StatisticsService(
        new StatisticsRepository($db)
    )
);

$recommendationsController=
new RecommendationsController(
    new RecommendationsService(
        new RecommendationsRepository($db)
    )
);



/* AUTH */

if($uri=="/api/login" && $method=="POST"){

    $auth=new AuthController($db);
    sendResponse($auth->login($data));
}



/* DRINKS */

if($uri=="/api/drinks" && $method=="GET"){

    isset($_GET["id"])
        ? sendResponse($drinkController->getDrinkById($_GET["id"]))
        : sendResponse($drinkController->getAllDrinks());
}

if($uri=="/api/drinks" && $method=="POST"){

    $user = Authorization::requireRoles(['admin','provider']);
    if($user->role == 'provider')
        $data['provider_id'] = $user->user_id;
    sendResponse($drinkController->create($data));
}

if($uri=="/api/drinks/update" && $method=="POST"){

    $user = Authorization::requireRoles(['admin','provider']);
    
    if($user->role == 'provider') {
       $data['provider_id'] = $user->user_id; 
    }

    sendResponse($drinkController->update($_GET["id"], $data));
}

if($uri=="/api/drinks" && $method=="DELETE"){

    $user = Authorization::requireRoles(['admin','provider']);
    sendResponse($drinkController->delete($_GET["id"], $user));
}



/* CATEGORIES */

if($uri=="/api/categories" && $method=="GET"){

    isset($_GET["id"])
        ? sendResponse($categoryController->getCategoryById($_GET["id"]))
        : sendResponse($categoryController->getAllCategories());
}

if($uri=="/api/categories" && $method=="POST"){
    Authorization::requireRoles(['admin']);
    sendResponse($categoryController->create($data));
}

if($uri=="/api/categories" && $method=="PUT"){
    Authorization::requireRoles(['admin']);
    sendResponse($categoryController->update($_GET["id"],$data));
}

if($uri=="/api/categories" && $method=="DELETE"){
    Authorization::requireRoles(['admin']);
    sendResponse($categoryController->delete($_GET["id"]));
}



/* INGREDIENTS */

if($uri=="/api/ingredients" && $method=="GET"){

    isset($_GET["id"])
        ? sendResponse($ingredientController->getIngredientById($_GET["id"]))
        : sendResponse($ingredientController->getAllIngredients());
}

if($uri=="/api/ingredients/drink" && $method=="GET"){

     sendResponse($ingredientController->getIngredientsByDrink($_GET["id"]));
}

if($uri=="/api/ingredients" && $method=="POST"){
    Authorization::requireRoles(['admin']);
    sendResponse($ingredientController->create($data));
}

if($uri=="/api/ingredients" && $method=="PUT"){
    Authorization::requireRoles(['admin']);
    sendResponse($ingredientController->update($_GET["id"],$data));
}

if($uri=="/api/ingredients" && $method=="DELETE"){
    Authorization::requireRoles(['admin']);
    sendResponse($ingredientController->delete($_GET["id"]));
}



/* PROVIDERS */

if($uri=="/api/providers" && $method=="GET"){

    isset($_GET["id"])
        ? sendResponse($providerController->getProviderById($_GET["id"]))
        : sendResponse($providerController->getAllProviders());
}

if($uri=="/api/providers" && $method=="POST"){
    sendResponse($providerController->create($data));
}

if($uri=="/api/providers" && $method=="PUT"){
    $user = Authorization::requireRoles(['admin','provider']);

    if($user->role == 'provider') {
        sendResponse($providerController->update($user->user_id, $data));
    }
    else {
        sendResponse($providerController->update($_GET["id"],$data));
    }   
}

if($uri=="/api/providers" && $method=="DELETE"){
    $user = Authorization::requireRoles(['admin','provider']);

    if($user->role == 'provider') {
        sendResponse($providerController->delete($user->user_id));
    }
    else {
        sendResponse($providerController->delete($_GET["id"]));
    }
}



/* USERS */

if($uri=="/api/users" && $method=="GET"){

    Authorization::requireRoles(['admin']);
    isset($_GET["id"])
        ? sendResponse($userController->getById($_GET["id"]))
        : sendResponse($userController->getAll());
}

if($uri=="/api/users" && $method=="POST"){
    sendResponse($userController->register($data));
}

if($uri=="/api/users" && $method=="PUT"){
    $user = Authorization::requireRoles(['admin','user']);

    if($user->role = 'user') {
        sendResponse($userController->update($user->user_id,$data));
    }
    else {
        sendResponse($userController->update($_GET["id"],$data));
    }
    
}

if($uri=="/api/users" && $method=="DELETE"){
    $user = Authorization::requireRoles(['admin','user']);

    if($user->role = 'user') {
        sendResponse($userController->delete($user->user_id));
    }
    else {
        sendResponse($userController->delete($_GET["id"]));
    }
    
}



/* RESTRICTIONS */

if($uri=="/api/restrictions" && $method=="GET"){

    isset($_GET["id"])
        ? sendResponse($restrictionController->getRestrictionById($_GET["id"]))
        : sendResponse($restrictionController->getAllRestrictions());
}

if($uri=="/api/restrictions" && $method=="POST"){
    Authorization::requireRoles(['admin']);
    sendResponse($restrictionController->create($data));
}

if($uri=="/api/restrictions" && $method=="PUT"){
    Authorization::requireRoles(['admin']);
    sendResponse($restrictionController->update($_GET["id"],$data));
}

if($uri=="/api/restrictions" && $method=="DELETE"){
    Authorization::requireRoles(['admin']);
    sendResponse($restrictionController->delete($_GET["id"]));
}



/* PREFERENCES */

if($uri=="/api/preferences/wishlist" && $method=="GET"){
    $user = Authorization::requireRoles(
        ['user', 'provider', 'admin']
    );

    sendResponse($preferenceController->getWishlist($user->user_id));
}

if($uri=="/api/preferences/wishlist" && $method=="POST"){
    $user = Authorization::requireRoles(
        ['user', 'provider', 'admin']
    );

    $data['user_id'] = $user->user_id;

    sendResponse($preferenceController->addWishlist($data));
}

if($uri=="/api/preferences/tried" && $method=="GET"){
    $user = Authorization::requireRoles(
        ['user', 'provider', 'admin']
    );

    sendResponse($preferenceController->getTriedList($user->user_id));
}

if($uri=="/api/preferences/tried" && $method=="POST"){
    $user = Authorization::requireRoles(
        ['user', 'provider', 'admin']
    );

    $data['user_id'] = $user->user_id;

    sendResponse($preferenceController->addTried($data));
}

if($uri=="/api/preferences/categories" && $method=="GET"){
    $user = Authorization::requireRoles(
        ['user', 'provider', 'admin']
    );

    sendResponse($preferenceController->getFavoriteCategories($user->user_id));
}

if($uri=="/api/preferences/categories" && $method=="POST"){
    $user = Authorization::requireRoles(
        ['user', 'provider', 'admin']
    );

    $data['user_id'] = $user->user_id;

    sendResponse($preferenceController->addFavoriteCategory($data));
}

if($uri=="/api/preferences/favorite-ingredients" && $method=="GET"){
    $user = Authorization::requireRoles(
        ['user', 'provider', 'admin']
    );

    sendResponse($preferenceController->getFavoriteIngredients($user->user_id));
}

if($uri=="/api/preferences/favorite-ingredients" && $method=="POST"){
    $user = Authorization::requireRoles(
        ['user', 'provider', 'admin']
    );

    $data['user_id'] = $user->user_id;

    sendResponse($preferenceController->addFavoriteIngredient($data));
}

if($uri=="/api/preferences/avoided-ingredients" && $method=="GET"){
    $user = Authorization::requireRoles(
        ['user', 'provider', 'admin']
    );

    sendResponse($preferenceController->getAvoidedIngredients($user->user_id));
}

if($uri=="/api/preferences/avoided-ingredients" && $method=="POST"){
    $user = Authorization::requireRoles(
        ['user', 'provider', 'admin']
    );

    $data['user_id'] = $user->user_id;

    sendResponse($preferenceController->addAvoidedIngredient($data));
}

if($uri=="/api/preferences/restrictions" && $method=="GET"){
    $user = Authorization::requireRoles(
        ['user', 'provider', 'admin']
    );

    sendResponse($preferenceController->getUserRestrictions($user->user_id));
}

if($uri=="/api/preferences/restrictions" && $method=="POST"){
    $user = Authorization::requireRoles(
        ['user', 'provider', 'admin']
    );

    $data['user_id'] = $user->user_id;

    sendResponse($preferenceController->addRestriction($data));
}

if($uri=="/api/preferences/providers" && $method=="GET"){
    $user = Authorization::requireRoles(
        ['user', 'provider', 'admin']
    );

    sendResponse($preferenceController->getFavoriteProviders($user->user_id));
}

if($uri=="/api/preferences/providers" && $method=="POST"){
    $user = Authorization::requireRoles(
        ['user', 'provider', 'admin']
    );

    $data['user_id'] = $user->user_id;

    sendResponse($preferenceController->addFavoriteProvider($data));
}

/* STATISTICS */

if($uri=="/api/statistics" && $method=="GET") {

        Authorization::requireRoles(['admin','provider','user']);
        sendResponse($statisticsController->dashboard());

}


/* RECOMMENDATIONS */

if($uri=="/api/recommendations" && $method=="GET") {

    $user = Authorization::requireRoles(
        ['user', 'provider', 'admin']
    );

    sendResponse($recommendationsController->getRecommendations($user->user_id));
}

http_response_code(404);

echo json_encode([
    "status"=>404,
    "message"=>"Route not found"
]);