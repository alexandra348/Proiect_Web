<?php

require_once __DIR__ . '/../repositories/CategoryRepository.php';
require_once __DIR__ . '/../exceptions/CategoryException.php';

class CategoryService {

    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): array
    {
        try {
            return $this->repository->getAll();
        } catch (PDOException $e) {
            throw new CategoryException("Failed to fetch categories", 0, $e);
        }
    }

    public function findById($id): array
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new CategoryException("Invalid category ID");
        }

        try {
            $category = $this->repository->findById((int)$id);

            if (!$category) {
                throw new CategoryException("Category not found");
            }

            return $category;

        } catch (PDOException $e) {
            throw new CategoryException("Failed to fetch category", 0, $e);
        }
    }

    public function create($data): bool
    {
        if (empty($data['name'])) {
            throw new CategoryException("Name is required");
        }

        try {
            return $this->repository->create($data);
        } catch (PDOException $e) {
            throw new CategoryException("Failed to create category", 0, $e);
        }
    }

    public function update($id, $data): bool
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new CategoryException("Invalid category ID");
        }

        if (empty($data['name'])) {
            throw new CategoryException("Name is required");
        }

        try {
            $exists = $this->repository->findById((int)$id);

            if (!$exists) {
                throw new CategoryException("Category not found");
            }

            return $this->repository->update((int)$id, $data);

        } catch (PDOException $e) {
            throw new CategoryException("Failed to update category", 0, $e);
        }
    }

    public function delete($id): bool
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new CategoryException("Invalid category ID");
        }

        try {
            $exists = $this->repository->findById((int)$id);

            if (!$exists) {
                throw new CategoryException("Category not found");
            }

            return $this->repository->delete((int)$id);

        } catch (PDOException $e) {
            throw new CategoryException("Failed to delete category", 0, $e);
        }
    }
}