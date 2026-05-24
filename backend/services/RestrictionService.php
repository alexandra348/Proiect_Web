<?php

require_once __DIR__ . '/../repositories/RestrictionRepository.php';
require_once __DIR__ . '/../exceptions/RestrictionException.php';

class RestrictionService {

    private RestrictionRepository $repository;

    public function __construct(RestrictionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): array
    {
        try {
            return $this->repository->getAll();
        } catch (PDOException $e) {
            throw new RestrictionException("Failed to fetch restrictions", 0, $e);
        }
    }

    public function findById($id): array
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new RestrictionException("Invalid restriction ID");
        }

        try {
            $result = $this->repository->findById((int)$id);

            if (!$result) {
                throw new RestrictionException("Restriction not found");
            }

            return $result;

        } catch (PDOException $e) {
            throw new RestrictionException("Failed to fetch restriction", 0, $e);
        }
    }

    public function create(array $data): bool
    {
        if (empty($data['name'])) {
            throw new RestrictionException("Name is required");
        }

        try {
            return $this->repository->create($data);
        } catch (PDOException $e) {
            throw new RestrictionException("Failed to create restriction", 0, $e);
        }
    }

    public function update($id, array $data): bool
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new RestrictionException("Invalid restriction ID");
        }

        if (empty($data['name'])) {
            throw new RestrictionException("Name is required");
        }

        try {
            return $this->repository->update((int)$id, $data);
        } catch (PDOException $e) {
            throw new RestrictionException("Failed to update restriction", 0, $e);
        }
    }

    public function delete($id): bool
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new RestrictionException("Invalid restriction ID");
        }

        try {
            return $this->repository->delete((int)$id);
        } catch (PDOException $e) {
            throw new RestrictionException("Failed to delete restriction", 0, $e);
        }
    }
}