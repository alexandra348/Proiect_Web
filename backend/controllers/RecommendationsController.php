<?php
require_once __DIR__ . '/../models/Recommendations.php';

class RecommendationsController {
    private $model;

    public function __construct($db) {
        $this->model = new Recommendations($db);
    }

    public function getRecommendations($user_id) {
        if (!is_numeric($user_id)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_USER_ID",
                "message" => "User ID must be numeric"
            ];
        }

        $recommendations = $this->model->getRecommended($user_id);

        if (!$recommendations) {
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
    }
}