<?php

require_once __DIR__ . '/../repositories/DrinkRepository.php';
require_once __DIR__ . '/../exceptions/DrinkException.php';

class DrinkService {

    private DrinkRepository $repository;

    public function __construct(DrinkRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): array
    {
        try {
            return $this->repository->getAll();
        } catch (PDOException $e) {
            throw new DrinkException("Failed to fetch drinks", 0, $e);
        }
    }

    public function findById($id): array
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new DrinkException("Invalid drink ID");
        }

        try {
            $drink = $this->repository->findById((int)$id);

            if (!$drink) {
                throw new DrinkException("Drink not found");
            }

            return $drink;

        } catch (PDOException $e) {
            throw new DrinkException("Failed to fetch drink", 0, $e);
        }
    }

    public function getByProvider($providerId): array
    {
        if (!is_numeric($providerId) || $providerId <= 0) {
            throw new DrinkException("Invalid provider ID");
        }

        try {
            return $this->repository->getByProvider((int)$providerId);
        } catch (PDOException $e) {
            throw new DrinkException("Failed to fetch provider drinks", 0, $e);
        }
    }

    public function create(array $data): bool
    {
        $this->validateCreate($data);

        try {
            return $this->repository->create([
                ":name" => $data['name'],
                ":price" => $data['price'],
                ":provider_id" => $data['provider_id'],
                ":category_id" => $data['category_id']
            ]);
        } catch (PDOException $e) {
            throw new DrinkException("Failed to create drink", 0, $e);
        }
    }

    public function update($id, array $data): bool
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new DrinkException("Invalid drink ID");
        }

        $this->validateUpdate($data);

        try {
            $exists = $this->repository->findById((int)$id);

            if (!$exists) {
                throw new DrinkException("Drink not found");
            }

            return $this->repository->update((int)$id, $data);

        } catch (PDOException $e) {
            throw new DrinkException("Failed to update drink", 0, $e);
        }
    }

    public function delete($id): bool
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new DrinkException("Invalid drink ID");
        }

        try {
            $exists = $this->repository->findById((int)$id);

            if (!$exists) {
                throw new DrinkException("Drink not found");
            }

            return $this->repository->delete((int)$id);

        } catch (PDOException $e) {
            throw new DrinkException("Failed to delete drink", 0, $e);
        }
    }

    // -------------------
    // VALIDATION
    // -------------------

    private function validateCreate(array $data): void
    {
        if (empty($data['name'])) {
            throw new DrinkException("Name is required");
        }

        if (!isset($data['price']) || $data['price'] < 0) {
            throw new DrinkException("Valid price is required");
        }

        if (empty($data['provider_id']) || !is_numeric($data['provider_id'])) {
            throw new DrinkException("Valid provider_id is required");
        }

        if (empty($data['category_id']) || !is_numeric($data['category_id'])) {
            throw new DrinkException("Valid category_id is required");
        }
    }

    private function validateUpdate(array $data): void
    {
        if (empty($data['name'])) {
            throw new DrinkException("Name is required");
        }

        if (!isset($data['price']) || $data['price'] < 0) {
            throw new DrinkException("Valid price is required");
        }
    }
}