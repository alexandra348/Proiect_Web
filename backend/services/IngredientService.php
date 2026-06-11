<?php

require_once __DIR__ . '/../repositories/IngredientRepository.php';
require_once __DIR__ . '/../exceptions/IngredientException.php';
require_once __DIR__ . '/../repositories/DrinkRepository.php';

class IngredientService {

    private IngredientRepository $repository;
    private DrinkRepository $drinkRepository;

    public function __construct(IngredientRepository $repository, DrinkRepository $drinkRepository)
    {
        $this->repository = $repository;
        $this->drinkRepository = $drinkRepository;
    }

    public function getAll(): array
    {
        try {
            return $this->repository->getAll();
        } catch (PDOException $e) {
            throw new IngredientException("Failed to fetch ingredients", 0, $e);
        }
    }

    public function findById($id): array
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new IngredientException("Invalid ingredient ID");
        }

        try {
            $ingredient = $this->repository->findById((int)$id);

            if (!$ingredient) {
                throw new IngredientException("Ingredient not found");
            }

            return $ingredient;

        } catch (PDOException $e) {
            throw new IngredientException("Failed to fetch ingredient", 0, $e);
        }
    }

    public function findByDrinkId($drinkId): array
    {
        if (!is_numeric($drinkId) || $drinkId <= 0) {
            throw new IngredientException("Invalid drink ID");
        }

        try {

            return $this->repository->getIngredientsByDrink((int)$drinkId);

        } catch (PDOException $e) {
            throw new IngredientException("Failed to fetch ingredients", 0, $e);
        }
    }

    public function addIngredientToDrink($user, $drinkId, $ingredientId): bool
    {
        if (!is_numeric($drinkId) || $drinkId <= 0) {
            throw new IngredientException("Invalid drink ID");
        }

        if (!is_numeric($ingredientId) || $ingredientId <= 0) {
            throw new IngredientException("Invalid ingredient ID");
        }

        try {
            $exists = $this->drinkRepository->findById((int)$drinkId);

            if (!$exists) {
                throw new DrinkException("Drink not found");
            }

            if($user->role === 'provider' && $exists['provider_id'] != $user->user_id){
               throw new DrinkException("You cannot add ingredient to this drink");
            }

            return $this->repository->addIngredientToDrink((int)$drinkId,(int)$ingredientId);

        } catch (PDOException $e) {
            throw new IngredientException("Failed to add ingredient to drink", 0, $e);
        }
    }

    public function deleteIngredientFromDrink($user, $drinkId, $ingredientId): bool
    {
        if (!is_numeric($drinkId) || $drinkId <= 0) {
            throw new IngredientException("Invalid drink ID");
        }

        if (!is_numeric($ingredientId) || $ingredientId <= 0) {
            throw new IngredientException("Invalid ingredient ID");
        }

        try {

            $exists = $this->drinkRepository->findById((int)$drinkId);

            if (!$exists) {
                throw new DrinkException("Drink not found");
            }

            if($user->role === 'provider' && $exists['provider_id'] != $user->user_id){
               throw new DrinkException("You cannot delete ingredient from this drink");
            }

            return $this->repository->deleteIngredientFromDrink((int)$drinkId,(int)$ingredientId);
        } catch (PDOException $e) {
            throw new IngredientException("Failed to delete ingredient from drink", 0, $e);
        }
    }

    public function create(array $data): bool
    {
        if (empty($data['name'])) {
            throw new IngredientException("Name is required");
        }

        try {
            return $this->repository->create($data);
        } catch (PDOException $e) {
            throw new IngredientException("Failed to create ingredient", 0, $e);
        }
    }

    public function update($id, array $data): bool
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new IngredientException("Invalid ingredient ID");
        }

        if (empty($data['name'])) {
            throw new IngredientException("Name is required");
        }

        try {
            $exists = $this->repository->findById((int)$id);

            if (!$exists) {
                throw new IngredientException("Ingredient not found");
            }

            return $this->repository->update((int)$id, $data);

        } catch (PDOException $e) {
            throw new IngredientException("Failed to update ingredient", 0, $e);
        }
    }

    public function delete($id): bool
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new IngredientException("Invalid ingredient ID");
        }

        try {
            $exists = $this->repository->findById((int)$id);

            if (!$exists) {
                throw new IngredientException("Ingredient not found");
            }

            return $this->repository->delete((int)$id);

        } catch (PDOException $e) {
            throw new IngredientException("Failed to delete ingredient", 0, $e);
        }
    }
}