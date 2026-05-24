<?php

require_once __DIR__ . '/../services/RecommendationsService.php';
require_once __DIR__ . '/../exceptions/RecommendationsException.php';

class RecommendationsController {

    private RecommendationsService $service;

    public function __construct(RecommendationsService $service)
    {
        $this->service = $service;
    }

    public function getRecommendations($user_id)
    {
        try {
            if (!is_numeric($user_id)) {
                return [
                    "status" => 400,
                    "error_code" => "INVALID_USER_ID",
                    "message" => "User ID must be numeric"
                ];
            }

            $recommendations = $this->service->getRecommended((int)$user_id);

            if (empty($recommendations)) {
                return [
                    "status" => 404,
                    "error_code" => "NO_RECOMMENDATIONS",
                    "message" => "No recommendations found for this user",
                    "data" => []
                ];
            }

            return [
                "status" => 200,
                "data" => $recommendations
            ];

        } catch (RecommendationsException $e) {
            return [
                "status" => 500,
                "error_code" => "RECOMMENDATION_ERROR",
                "message" => $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                "status" => 500,
                "error_code" => "SERVER_ERROR",
                "message" => "Unexpected server error"
            ];
        }
    }
}