<?php

require_once __DIR__ . '/../repositories/RecommendationsRepository.php';
require_once __DIR__ . '/../exceptions/RecommendationsException.php';

class RecommendationsService {

    private RecommendationsRepository $repository;

    public function __construct(RecommendationsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getRecommended(int $userId): array
    {
        if ($userId <= 0) {
            throw new RecommendationsException("Invalid user ID");
        }

        try {
            return $this->repository->getRecommended($userId);
        } catch (PDOException $e) {
            throw new RecommendationsException(
                "Failed to fetch recommendations",
                0,
                $e
            );
        }
    }
}