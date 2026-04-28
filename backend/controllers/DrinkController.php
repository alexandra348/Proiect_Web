<?php

require_once __DIR__ . '/../models/Drink.php';

class DrinkController {
    private $drinkModel;

    public function __construct() {
        $this->drinkModel = new Drink();
    }

    // GET all drinks
    public function getAll() {
        return $this->drinkModel->getAll();
    }

    // CREATE drink
    public function create($data) {
        return $this->drinkModel->create(
            $data['name'],
            $data['price'],
            $data['provider_id'],
            $data['category_id']
        );
    }

    // GET by location
    public function getByLocation($location) {
        return $this->drinkModel->getByLocation($location);
    }

    // DELETE drink
    public function delete($id) {
        return $this->drinkModel->delete($id);
    }
}