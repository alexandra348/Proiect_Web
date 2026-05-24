<?php

require_once __DIR__ . '/../services/PreferenceService.php';
require_once __DIR__ . '/../exceptions/PreferenceException.php';

class PreferenceController {

    private PreferenceService $service;

    public function __construct(PreferenceService $service)
    {
        $this->service = $service;
    }

    // --------------------
    // WISHLIST
    // --------------------

    public function addWishlist($data)
    {
        try {
            $this->service->addToWishlist($data['user_id'], $data['drink_id']);

            return [
                "status" => 201,
                "message" => "Added to wishlist"
            ];

        } catch (PreferenceException $e) {
            return [
                "status" => 400,
                "error_code" => "ADD_WISHLIST_FAILED",
                "message" => $e->getMessage()
            ];
        }
    }

    public function getWishlist($user_id)
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->getWishlist($user_id)
            ];

        } catch (PreferenceException $e) {
            return [
                "status" => 400,
                "error_code" => "WISHLIST_ERROR",
                "message" => $e->getMessage()
            ];
        }
    }

    // --------------------
    // TRIED DRINKS
    // --------------------

    public function addTried($data)
    {
        try {
            $this->service->addTried(
                $data['user_id'],
                $data['drink_id'],
                $data['rating'],
                $data['notes'] ?? null
            );

            return [
                "status" => 201,
                "message" => "Added to tried list"
            ];

        } catch (PreferenceException $e) {
            return [
                "status" => 400,
                "error_code" => "ADD_TRIED_FAILED",
                "message" => $e->getMessage()
            ];
        }
    }

    public function getTriedList($user_id)
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->getTriedList($user_id)
            ];

        } catch (PreferenceException $e) {
            return [
                "status" => 400,
                "error_code" => "TRIED_ERROR",
                "message" => $e->getMessage()
            ];
        }
    }

    // --------------------
    // FAVORITE CATEGORIES
    // --------------------

    public function addFavoriteCategory($data)
    {
        try {
            $this->service->addFavoriteCategory($data['user_id'], $data['category_id']);

            return [
                "status" => 201,
                "message" => "Favorite category added"
            ];

        } catch (PreferenceException $e) {
            return [
                "status" => 400,
                "error_code" => "CATEGORY_ERROR",
                "message" => $e->getMessage()
            ];
        }
    }

    public function getFavoriteCategories($user_id)
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->getFavoriteCategories($user_id)
            ];

        } catch (PreferenceException $e) {
            return [
                "status" => 400,
                "error_code" => "CATEGORY_ERROR",
                "message" => $e->getMessage()
            ];
        }
    }

    // --------------------
    // FAVORITE INGREDIENTS
    // --------------------

    public function addFavoriteIngredient($data)
    {
        try {
            $this->service->addFavoriteIngredient($data['user_id'], $data['ingredient_id']);

            return [
                "status" => 201,
                "message" => "Favorite ingredient added"
            ];

        } catch (PreferenceException $e) {
            return [
                "status" => 400,
                "error_code" => "INGREDIENT_ERROR",
                "message" => $e->getMessage()
            ];
        }
    }

    public function getFavoriteIngredients($user_id)
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->getFavoriteIngredients($user_id)
            ];

        } catch (PreferenceException $e) {
            return [
                "status" => 400,
                "error_code" => "INGREDIENT_ERROR",
                "message" => $e->getMessage()
            ];
        }
    }

    // --------------------
    // AVOIDED INGREDIENTS
    // --------------------

    public function addAvoidedIngredient($data)
    {
        try {
            $this->service->addAvoidedIngredient($data['user_id'], $data['ingredient_id']);

            return [
                "status" => 201,
                "message" => "Avoided ingredient added"
            ];

        } catch (PreferenceException $e) {
            return [
                "status" => 400,
                "error_code" => "AVOIDED_ERROR",
                "message" => $e->getMessage()
            ];
        }
    }

    public function getAvoidedIngredients($user_id)
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->getAvoidedIngredients($user_id)
            ];

        } catch (PreferenceException $e) {
            return [
                "status" => 400,
                "error_code" => "AVOIDED_ERROR",
                "message" => $e->getMessage()
            ];
        }
    }

    // --------------------
    // RESTRICTIONS
    // --------------------

    public function getRestrictions()
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->getUserRestrictions(0) // dacă vrei global, ajustăm service
            ];

        } catch (PreferenceException $e) {
            return [
                "status" => 500,
                "error_code" => "RESTRICTION_ERROR",
                "message" => $e->getMessage()
            ];
        }
    }

    public function addRestriction($data)
    {
        try {
            $this->service->addUserRestriction($data['user_id'], $data['restriction_id']);

            return [
                "status" => 201,
                "message" => "Restriction added"
            ];

        } catch (PreferenceException $e) {
            return [
                "status" => 400,
                "error_code" => "RESTRICTION_ERROR",
                "message" => $e->getMessage()
            ];
        }
    }

    public function getUserRestrictions($user_id)
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->getUserRestrictions($user_id)
            ];

        } catch (PreferenceException $e) {
            return [
                "status" => 400,
                "error_code" => "RESTRICTION_ERROR",
                "message" => $e->getMessage()
            ];
        }
    }

    // --------------------
    // PROVIDERS
    // --------------------

    public function addFavoriteProvider($data)
    {
        try {
            $this->service->addFavoriteProvider($data['user_id'], $data['provider_id']);

            return [
                "status" => 201,
                "message" => "Favorite provider added"
            ];

        } catch (PreferenceException $e) {
            return [
                "status" => 400,
                "error_code" => "PROVIDER_ERROR",
                "message" => $e->getMessage()
            ];
        }
    }

    public function getFavoriteProviders($user_id)
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->getFavoriteProviders($user_id)
            ];

        } catch (PreferenceException $e) {
            return [
                "status" => 400,
                "error_code" => "PROVIDER_ERROR",
                "message" => $e->getMessage()
            ];
        }
    }
}