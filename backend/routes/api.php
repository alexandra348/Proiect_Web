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

$data=json_decode(
    file_get_contents("php://input"),
    true
);


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

    sendResponse(
        $auth->login($data)
    );
}



/* DRINKS */

if($uri=="/api/drinks" && $method=="GET"){

    isset($_GET["id"])
        ? sendResponse(
            $drinkController
            ->getDrinkById($_GET["id"])
        )
        : sendResponse(
            $drinkController
            ->getAllDrinks()
        );
}

if($uri=="/api/drinks" && $method=="POST"){
    sendResponse(
        $drinkController->create($data)
    );
}

if($uri=="/api/drinks" && $method=="PUT"){
    sendResponse(
        $drinkController->update(
            $_GET["id"],
            $data
        )
    );
}

if($uri=="/api/drinks" && $method=="DELETE"){
    sendResponse(
        $drinkController->delete(
            $_GET["id"]
        )
    );
}



/* CATEGORIES */

if($uri=="/api/categories" && $method=="GET"){

    isset($_GET["id"])
        ? sendResponse(
            $categoryController
            ->getCategoryById($_GET["id"])
        )
        : sendResponse(
            $categoryController
            ->getAllCategories()
        );
}

if($uri=="/api/categories" && $method=="POST"){
    sendResponse(
        $categoryController
        ->create($data)
    );
}

if($uri=="/api/categories" && $method=="PUT"){
    sendResponse(
        $categoryController
        ->update($_GET["id"],$data)
    );
}

if($uri=="/api/categories" && $method=="DELETE"){
    sendResponse(
        $categoryController
        ->delete($_GET["id"])
    );
}



/* INGREDIENTS */

if($uri=="/api/ingredients" && $method=="GET"){

    isset($_GET["id"])
        ? sendResponse(
            $ingredientController
            ->getIngredientById($_GET["id"])
        )
        : sendResponse(
            $ingredientController
            ->getAllIngredients()
        );
}

if($uri=="/api/ingredients" && $method=="POST"){
    sendResponse(
        $ingredientController
        ->create($data)
    );
}

if($uri=="/api/ingredients" && $method=="PUT"){
    sendResponse(
        $ingredientController
        ->update($_GET["id"],$data)
    );
}

if($uri=="/api/ingredients" && $method=="DELETE"){
    sendResponse(
        $ingredientController
        ->delete($_GET["id"])
    );
}



/* PROVIDERS */

if($uri=="/api/providers" && $method=="GET"){

    isset($_GET["id"])
        ? sendResponse(
            $providerController
            ->getProviderById($_GET["id"])
        )
        : sendResponse(
            $providerController
            ->getAllProviders()
        );
}

if($uri=="/api/providers" && $method=="POST"){
    sendResponse(
        $providerController
        ->create($data)
    );
}

if($uri=="/api/providers" && $method=="PUT"){
    sendResponse(
        $providerController
        ->update($_GET["id"],$data)
    );
}

if($uri=="/api/providers" && $method=="DELETE"){
    sendResponse(
        $providerController
        ->delete($_GET["id"])
    );
}



/* USERS */

if($uri=="/api/users" && $method=="GET"){

    isset($_GET["id"])
        ? sendResponse(
            $userController
            ->getById($_GET["id"])
        )
        : sendResponse(
            $userController
            ->getAll()
        );
}

if($uri=="/api/users" && $method=="POST"){
    sendResponse(
        $userController
        ->register($data)
    );
}

if($uri=="/api/users" && $method=="PUT"){
    sendResponse(
        $userController
        ->update($_GET["id"],$data)
    );
}

if($uri=="/api/users" && $method=="DELETE"){
    sendResponse(
        $userController
        ->delete($_GET["id"])
    );
}



/* RESTRICTIONS */

if($uri=="/api/restrictions" && $method=="GET"){

    isset($_GET["id"])
        ? sendResponse(
            $restrictionController
            ->getRestrictionById($_GET["id"])
        )
        : sendResponse(
            $restrictionController
            ->getAllRestrictions()
        );
}

if($uri=="/api/restrictions" && $method=="POST"){
    sendResponse(
        $restrictionController
        ->create($data)
    );
}

if($uri=="/api/restrictions" && $method=="PUT"){
    sendResponse(
        $restrictionController
        ->update($_GET["id"],$data)
    );
}

if($uri=="/api/restrictions" && $method=="DELETE"){
    sendResponse(
        $restrictionController
        ->delete($_GET["id"])
    );
}



/* PREFERENCES */

if($uri=="/api/preferences/wishlist" && $method=="GET")
    sendResponse($preferenceController->getWishlist($_GET["user_id"]));

if($uri=="/api/preferences/wishlist" && $method=="POST")
    sendResponse($preferenceController->addWishlist($data));

if($uri=="/api/preferences/tried" && $method=="GET")
    sendResponse($preferenceController->getTriedList($_GET["user_id"]));

if($uri=="/api/preferences/tried" && $method=="POST")
    sendResponse($preferenceController->addTried($data));

if($uri=="/api/preferences/categories" && $method=="GET")
    sendResponse($preferenceController->getFavoriteCategories($_GET["user_id"]));

if($uri=="/api/preferences/categories" && $method=="POST")
    sendResponse($preferenceController->addFavoriteCategory($data));

if($uri=="/api/preferences/favorite-ingredients" && $method=="GET")
    sendResponse($preferenceController->getFavoriteIngredients($_GET["user_id"]));

if($uri=="/api/preferences/favorite-ingredients" && $method=="POST")
    sendResponse($preferenceController->addFavoriteIngredient($data));

if($uri=="/api/preferences/avoided-ingredients" && $method=="GET")
    sendResponse($preferenceController->getAvoidedIngredients($_GET["user_id"]));

if($uri=="/api/preferences/avoided-ingredients" && $method=="POST")
    sendResponse($preferenceController->addAvoidedIngredient($data));

if($uri=="/api/preferences/restrictions" && $method=="GET")
    sendResponse($preferenceController->getUserRestrictions($_GET["user_id"]));

if($uri=="/api/preferences/restrictions" && $method=="POST")
    sendResponse($preferenceController->addRestriction($data));

if($uri=="/api/preferences/providers" && $method=="GET")
    sendResponse($preferenceController->getFavoriteProviders($_GET["user_id"]));

if($uri=="/api/preferences/providers" && $method=="POST")
    sendResponse($preferenceController->addFavoriteProvider($data));



/* STATISTICS */

if($uri=="/api/statistics" && $method=="GET")
    sendResponse(
        $statisticsController->dashboard()
    );


/* RECOMMENDATIONS */

if($uri=="/api/recommendations" && $method=="GET")
    sendResponse(
        $recommendationsController
        ->getRecommendations(
            $_GET["user_id"]
        )
    );


http_response_code(404);

echo json_encode([
    "status"=>404,
    "message"=>"Route not found"
]);