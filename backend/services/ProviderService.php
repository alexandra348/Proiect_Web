<?php

require_once __DIR__ . '/../repositories/ProviderRepository.php';
require_once __DIR__ . '/../exceptions/ProviderException.php';

class ProviderService {

    private ProviderRepository $repository;

    public function __construct(ProviderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $data): bool
    {
        $this->validateCreate($data);

        try {
            return $this->repository->create($data);
        } catch (PDOException $e) {
            throw new ProviderException("Failed to create provider", 0, $e);
        }
    }

    public function findByEmail(string $email): array
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ProviderException("Invalid email format");
        }

        try {
            $provider = $this->repository->findByEmail($email);

            if (!$provider) {
                throw new ProviderException("Provider not found");
            }

            return $provider;

        } catch (PDOException $e) {
            throw new ProviderException("Failed to fetch provider by email", 0, $e);
        }
    }

    public function findById($id): array
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new ProviderException("Invalid provider ID");
        }

        try {
            $provider = $this->repository->findById((int)$id);

            if (!$provider) {
                throw new ProviderException("Provider not found");
            }

            return $provider;

        } catch (PDOException $e) {
            throw new ProviderException("Failed to fetch provider", 0, $e);
        }
    }

    public function getAll(): array
    {
        try {
            return $this->repository->getAll();
        } catch (PDOException $e) {
            throw new ProviderException("Failed to fetch providers", 0, $e);
        }
    }

    public function update($id, array $data): bool
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new ProviderException("Invalid provider ID");
        }

        $this->validateUpdate($data);

        try {
            $exists = $this->repository->findById((int)$id);

            if (!$exists) {
                throw new ProviderException("Provider not found");
            }

            return $this->repository->update((int)$id, $data);

        } catch (PDOException $e) {
            throw new ProviderException("Failed to update provider", 0, $e);
        }
    }

    public function delete($id): bool
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new ProviderException("Invalid provider ID");
        }

        try {
            $exists = $this->repository->findById((int)$id);

            if (!$exists) {
                throw new ProviderException("Provider not found");
            }

            return $this->repository->delete((int)$id);

        } catch (PDOException $e) {
            throw new ProviderException("Failed to delete provider", 0, $e);
        }
    }

    

    private function validateCreate(array $data): void
    {
        if (empty($data['name'])) {
            throw new ProviderException("Name is required");
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new ProviderException("Valid email is required");
        }

        if (empty($data['password']) || strlen($data['password']) < 6) {
            throw new ProviderException("Password must be at least 6 characters");
        }

        if (empty($data['type'])) {
            throw new ProviderException("Type is required");
        }

        if (empty($data['address'])) {
            throw new ProviderException("Address is required");
        }

        if (empty($data['city'])) {
            throw new ProviderException("City is required");
        }
    }

    private function validateUpdate(array $data): void
    {
        if (empty($data['name'])) {
            throw new ProviderException("Name is required");
        }

        if (empty($data['type'])) {
            throw new ProviderException("Type is required");
        }

        if (empty($data['address'])) {
            throw new ProviderException("Address is required");
        }

        if (empty($data['city'])) {
            throw new ProviderException("City is required");
        }
    }
}