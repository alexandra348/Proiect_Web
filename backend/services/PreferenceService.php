<?php

require_once __DIR__ . '/../repositories/PreferenceRepository.php';
require_once __DIR__ . '/../exceptions/PreferenceException.php';

class PreferenceService {

    private PreferenceRepository $repository;

    public function __construct(PreferenceRepository $repository)
    {
        $this->repository = $repository;
    }

    // -------------------
    // WISHLIST
    // -------------------

    public function addToWishlist($userId, $drinkId): bool
    {
        $this->validateUserAndDrink($userId, $drinkId);

        try {
            return $this->repository->addToWishlist((int)$userId, (int)$drinkId);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to add to wishlist", 0, $e);
        }
    }

    public function getWishlist($userId): array
    {
        $this->validateUser($userId);

        try {
            return $this->repository->getWishlist((int)$userId);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to fetch wishlist", 0, $e);
        }
    }

    public function deleteWishDrink($user_id,$id)
    {

        try {
            return $this->repository->deleteFromWishlist((int)$user_id, (int)$id);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to delete drink from wishlist", 0, $e);
        }
    }

    // -------------------
    // TRIED DRINKS
    // -------------------

    public function addTried($userId, $drinkId, $rating, $notes): bool
    {
        $this->validateUserAndDrink($userId, $drinkId);

        if ($rating < 0 || $rating > 5) {
            throw new PreferenceException("Rating must be between 0 and 5");
        }

        try {
            return $this->repository->addTried(
                (int)$userId,
                (int)$drinkId,
                $rating,
                $notes ?? ''
            );
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to add tried drink", 0, $e);
        }
    }

    public function getTriedList($userId): array
    {
        $this->validateUser($userId);

        try {
            return $this->repository->getTriedList((int)$userId);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to fetch tried drinks", 0, $e);
        }
    }

    public function deleteTriedDrink($user_id,$id)
    {

        try {
            return $this->repository->deleteFromTriedDrinks((int)$user_id,(int)$id);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to delete tried drink", 0, $e);
        }
    }

    // -------------------
    // FAVORITE CATEGORIES
    // -------------------

    public function addFavoriteCategory($userId, $categoryId): bool
    {
        $this->validateUserAndGeneric($userId, $categoryId);

        try {
            return $this->repository->addFavoriteCategory((int)$userId, (int)$categoryId);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to add favorite category", 0, $e);
        }
    }

    public function getFavoriteCategories($userId): array
    {
        $this->validateUser($userId);

        try {
            return $this->repository->getFavoriteCategories((int)$userId);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to fetch favorite categories", 0, $e);
        }
    }

    public function deleteFavoriteCategory($user_id, $id)
    {
        $this->validateUserAndGeneric($user_id, $id);

        try {
            return $this->repository->deleteFromFavoriteCategories((int)$user_id, (int)$id);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to delete favorite category", 0, $e);
        }
    }

    // -------------------
    // FAVORITE INGREDIENTS
    // -------------------

    public function addFavoriteIngredient($userId, $ingredientId): bool
    {
        $this->validateUserAndGeneric($userId, $ingredientId);

        try {
            return $this->repository->addFavoriteIngredient((int)$userId, (int)$ingredientId);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to add favorite ingredient", 0, $e);
        }
    }

    public function getFavoriteIngredients($userId): array
    {
        $this->validateUser($userId);

        try {
            return $this->repository->getFavoriteIngredients((int)$userId);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to fetch favorite ingredients", 0, $e);
        }
    }

    public function deleteFavoriteIngredient($user_id, $id)
    {
        $this->validateUserAndGeneric($user_id, $id);

        try {
            return $this->repository->deleteFromFavoriteIngredients((int)$user_id, (int)$id);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to delete favorite ingredient", 0, $e);
        }
    }

    // -------------------
    // AVOIDED INGREDIENTS
    // -------------------

    public function addAvoidedIngredient($userId, $ingredientId): bool
    {
        $this->validateUserAndGeneric($userId, $ingredientId);

        try {
            return $this->repository->addAvoidedIngredient((int)$userId, (int)$ingredientId);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to add avoided ingredient", 0, $e);
        }
    }

    public function getAvoidedIngredients($userId): array
    {
        $this->validateUser($userId);

        try {
            return $this->repository->getAvoidedIngredients((int)$userId);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to fetch avoided ingredients", 0, $e);
        }
    }

    public function deleteAvoidIngredient($user_id, $id)
    {
        $this->validateUserAndGeneric($user_id, $id);

        try {
            return $this->repository->deleteFromAvoidIngredients((int)$user_id, (int)$id);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to delete avoid ingredient", 0, $e);
        }
    }

    // -------------------
    // RESTRICTIONS
    // -------------------

    public function addUserRestriction($userId, $restrictionId): bool
    {
        $this->validateUserAndGeneric($userId, $restrictionId);

        try {
            return $this->repository->addUserRestriction((int)$userId, (int)$restrictionId);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to add user restriction", 0, $e);
        }
    }

    public function getUserRestrictions($userId): array
    {
        $this->validateUser($userId);

        try {
            return $this->repository->getUserRestrictions((int)$userId);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to fetch user restrictions", 0, $e);
        }
    }

    // -------------------
    // FAVORITE PROVIDERS
    // -------------------

    public function addFavoriteProvider($userId, $providerId): bool
    {
        $this->validateUserAndGeneric($userId, $providerId);

        try {
            return $this->repository->addFavoriteProvider((int)$userId, (int)$providerId);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to add favorite provider", 0, $e);
        }
    }

    public function getFavoriteProviders($userId): array
    {
        $this->validateUser($userId);

        try {
            return $this->repository->getFavoriteProviders((int)$userId);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to fetch favorite providers", 0, $e);
        }
    }

    public function deleteFavoriteProvider($user_id, $id)
    {
        $this->validateUserAndGeneric($user_id, $id);

        try {
            return $this->repository->deleteFromFavoriteProviders((int)$user_id, (int)$id);
        } catch (PDOException $e) {
            throw new PreferenceException("Failed to delete favorite provider", 0, $e);
        }
    }

    // -------------------
    // VALIDATION
    // -------------------

    private function validateUser($userId): void
    {
        if (!is_numeric($userId) || $userId <= 0) {
            throw new PreferenceException("Invalid user ID");
        }
    }

    private function validateUserAndDrink($userId, $drinkId): void
    {
        if (!is_numeric($userId) || $userId <= 0) {
            throw new PreferenceException("Invalid user ID");
        }

        if (!is_numeric($drinkId) || $drinkId <= 0) {
            throw new PreferenceException("Invalid drink ID");
        }
    }

    private function validateUserAndGeneric($userId, $id): void
    {
        if (!is_numeric($userId) || $userId <= 0) {
            throw new PreferenceException("Invalid user ID");
        }

        if (!is_numeric($id) || $id <= 0) {
            throw new PreferenceException("Invalid ID");
        }
    }
}