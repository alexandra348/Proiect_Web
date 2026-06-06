<?php

require_once __DIR__ . '/../repositories/StatisticsRepository.php';
require_once __DIR__ . '/../exceptions/StatisticsException.php';

class StatisticsService
{
    private StatisticsRepository $repository;

    public function __construct(StatisticsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function topDrinks(): array
    {
        try {
            return $this->repository->topDrinks();
        } catch (PDOException $e) {
            throw new StatisticsException(
                "Failed to fetch top drinks",
                0,
                $e
            );
        }
    }

    public function topCategories(): array
    {
        try {
            return $this->repository->topCategories();
        } catch (PDOException $e) {
            throw new StatisticsException(
                "Failed to fetch top categories",
                0,
                $e
            );
        }
    }

    public function topProviders(): array
    {
        try {
            return $this->repository->topProviders();
        } catch (PDOException $e) {
            throw new StatisticsException(
                "Failed to fetch top providers",
                0,
                $e
            );
        }
    }

    public function topRated(): array
    {
        try {
            return $this->repository->topRated();
        } catch (PDOException $e) {
            throw new StatisticsException(
                "Failed to fetch top rated drinks",
                0,
                $e
            );
        }
    }

    public function topIngredients(): array
    {
        try {
            return $this->repository->topIngredients();
        } catch (PDOException $e) {
            throw new StatisticsException(
                "Failed to fetch top ingredients",
                0,
                $e
            );
        }
    }

    public function mostAvoidedIngredients(): array
    {
        try {
            return $this->repository->mostAvoidedIngredients();
        } catch (PDOException $e) {
            throw new StatisticsException(
                "Failed to fetch avoided ingredients",
                0,
                $e
            );
        }
    }

    public function topRestrictions(): array
    {
        try {
            return $this->repository->topRestrictions();
        } catch (PDOException $e) {
            throw new StatisticsException(
                "Failed to fetch restrictions",
                0,
                $e
            );
        }
    }
}