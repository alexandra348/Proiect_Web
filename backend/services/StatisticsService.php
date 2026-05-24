<?php

require_once __DIR__ . '/../repositories/StatisticsRepository.php';
require_once __DIR__ . '/../exceptions/StatisticsException.php';

class StatisticsService {

    private StatisticsRepository $repository;

    public function __construct(StatisticsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function topDrinks() {
        try {
            return $this->repository->topDrinks();
        } catch (PDOException $e) {
            throw new StatisticsException("Failed to fetch top drinks", 0, $e);
        }
    }

    public function topCategories() {
        try {
            return $this->repository->topCategories();
        } catch (PDOException $e) {
            throw new StatisticsException("Failed to fetch top categories", 0, $e);
        }
    }

    public function topProviders() {
        try {
            return $this->repository->topProviders();
        } catch (PDOException $e) {
            throw new StatisticsException("Failed to fetch top providers", 0, $e);
        }
    }

    public function topRated() {
        try {
            return $this->repository->topRated();
        } catch (PDOException $e) {
            throw new StatisticsException("Failed to fetch top rated drinks", 0, $e);
        }
    }
}