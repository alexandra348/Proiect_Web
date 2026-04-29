<?php
require_once __DIR__ . '/../models/Statistics.php';

class StatisticsController {
    private $model;

    public function __construct($db) {
        $this->model = new Statistics($db);
    }

    
    public function dashboard() {
        $data = [
            "top_drinks" => $this->model->topDrinks(),
            "top_categories" => $this->model->topCategories(),
            "top_providers" => $this->model->topProviders(),
            "top_rated" => $this->model->topRated()
        ];

        return [
            "status" => 200,
            "data" => $data
        ];
    }

    
    public function topDrinks() {
        return [
            "status" => 200,
            "data" => $this->model->topDrinks()
        ];
    }

    
    public function topCategories() {
        return [
            "status" => 200,
            "data" => $this->model->topCategories()
        ];
    }

    
    public function topProviders() {
        return [
            "status" => 200,
            "data" => $this->model->topProviders()
        ];
    }

    
    public function topRated() {
        return [
            "status" => 200,
            "data" => $this->model->topRated()
        ];
    }
}