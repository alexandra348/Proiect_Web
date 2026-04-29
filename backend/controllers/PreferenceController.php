<?php
require_once __DIR__ . '/../models/Preference.php';

class PreferenceController {
    private $model;

    public function __construct($db) {
        $this->model = new Preference($db);
    }

    

    public function addWishlist($data) {
        if (empty($data['user_id']) || empty($data['drink_id'])) {
            return [
                "status" => 400,
                "error_code" => "MISSING_FIELDS",
                "message" => "user_id and drink_id are required"
            ];
        }

        $result = $this->model->addToWishlist($data['user_id'], $data['drink_id']);

        if (!$result) {
            return [
                "status" => 500,
                "error_code" => "ADD_WISHLIST_FAILED",
                "message" => "Failed to add to wishlist"
            ];
        }

        return [
            "status" => 201,
            "message" => "Added to wishlist"
        ];
    }

    public function getWishlist($user_id) {
        if (!is_numeric($user_id)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_USER_ID"
            ];
        }

        return [
            "status" => 200,
            "data" => $this->model->getWishlist($user_id)
        ];
    }

    

    public function getTriedList($user_id) {
        if (!is_numeric($user_id)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_USER_ID"
            ];
        }

        return [
            "status" => 200,
            "data" => $this->model->getTriedList($user_id)
        ];
    }

    public function addTried($data) {
        if (
            empty($data['user_id']) ||
            empty($data['drink_id']) ||
            !isset($data['rating'])
        ) {
            return [
                "status" => 400,
                "error_code" => "MISSING_FIELDS",
                "message" => "user_id, drink_id and rating are required"
            ];
        }

        $result = $this->model->addTried(
            $data['user_id'],
            $data['drink_id'],
            $data['rating'],
            $data['notes'] ?? null
        );

        if (!$result) {
            return [
                "status" => 500,
                "error_code" => "ADD_TRIED_FAILED",
                "message" => "Failed to add tried drink"
            ];
        }

        return [
            "status" => 201,
            "message" => "Added to tried list"
        ];
    }

    

    public function addFavoriteCategory($data) {
        if (empty($data['user_id']) || empty($data['category_id'])) {
            return [
                "status" => 400,
                "error_code" => "MISSING_FIELDS"
            ];
        }

        return [
            "status" => 201,
            "message" => "Favorite category added",
            "data" => $this->model->addFavoriteCategory($data['user_id'], $data['category_id'])
        ];
    }

    public function getFavoriteCategories($user_id) {
        if (!is_numeric($user_id)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_USER_ID"
            ];
        }

        return [
            "status" => 200,
            "data" => $this->model->getFavoriteCategories($user_id)
        ];
    }

    

    public function addFavoriteIngredient($data) {
        if (empty($data['user_id']) || empty($data['ingredient_id'])) {
            return [
                "status" => 400,
                "error_code" => "MISSING_FIELDS"
            ];
        }

        return [
            "status" => 201,
            "message" => "Favorite ingredient added",
            "data" => $this->model->addFavoriteIngredient($data['user_id'], $data['ingredient_id'])
        ];
    }

    public function getFavoriteIngredients($user_id) {
        if (!is_numeric($user_id)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_USER_ID"
            ];
        }

        return [
            "status" => 200,
            "data" => $this->model->getFavoriteIngredients($user_id)
        ];
    }

    public function addAvoidedIngredient($data) {
        if (empty($data['user_id']) || empty($data['ingredient_id'])) {
            return [
                "status" => 400,
                "error_code" => "MISSING_FIELDS"
            ];
        }

        return [
            "status" => 201,
            "message" => "Avoided ingredient added",
            "data" => $this->model->addAvoidedIngredient($data['user_id'], $data['ingredient_id'])
        ];
    }

    public function getAvoidedIngredients($user_id) {
        if (!is_numeric($user_id)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_USER_ID"
            ];
        }

        return [
            "status" => 200,
            "data" => $this->model->getAvoidedIngredients($user_id)
        ];
    }



    public function getRestrictions() {
        return [
            "status" => 200,
            "data" => $this->model->getAllRestrictions()
        ];
    }

    public function addRestriction($data) {
        if (empty($data['user_id']) || empty($data['restriction_id'])) {
            return [
                "status" => 400,
                "error_code" => "MISSING_FIELDS"
            ];
        }

        return [
            "status" => 201,
            "message" => "Restriction added",
            "data" => $this->model->addUserRestriction($data['user_id'], $data['restriction_id'])
        ];
    }

    public function getUserRestrictions($user_id) {
        if (!is_numeric($user_id)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_USER_ID"
            ];
        }

        return [
            "status" => 200,
            "data" => $this->model->getUserRestrictions($user_id)
        ];
    }


    public function addFavoriteProvider($data) {
        if (empty($data['user_id']) || empty($data['provider_id'])) {
            return [
                "status" => 400,
                "error_code" => "MISSING_FIELDS"
            ];
        }

        return [
            "status" => 201,
            "message" => "Favorite provider added",
            "data" => $this->model->addFavoriteProvider($data['user_id'], $data['provider_id'])
        ];
    }

    public function getFavoriteProviders($user_id) {
        if (!is_numeric($user_id)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_USER_ID"
            ];
        }

        return [
            "status" => 200,
            "data" => $this->model->getFavoriteProviders($user_id)
        ];
    }
}