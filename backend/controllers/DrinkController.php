<?php
require_once __DIR__ . '/../models/Drink.php';

class DrinkController {
    private $model;

    public function __construct($db) {
        $this->model = new Drink($db);
    }

    public function getAllDrinks() {
        return [
            "status" => 200,
            "data" => $this->model->getAll()
        ];
    }

    public function getDrinkById($id) {
        if (!is_numeric($id)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_ID",
                "message" => "ID must be numeric"
            ];
        }

        $drink = $this->model->findById($id);

        if (!$drink) {
            return [
                "status" => 404,
                "error_code" => "DRINK_NOT_FOUND",
                "message" => "Drink not found"
            ];
        }

        return [
            "status" => 200,
            "data" => $drink
        ];
    }

    public function create($data) {
        if (empty($data['name']) || empty($data['price']) || empty($data['provider_id']) || empty($data['category_id'])) {
            return [
                "status" => 400,
                "error_code" => "MISSING_FIELDS",
                "message" => "Missing required fields"
            ];
        }

        if (!is_numeric($data['price']) || $data['price'] <= 0) {
            return [
                "status" => 400,
                "error_code" => "INVALID_PRICE",
                "message" => "Price must be a positive number"
            ];
        }

        $result = $this->model->create($data);

        if (!$result) {
            return [
                "status" => 500,
                "error_code" => "CREATE_FAILED",
                "message" => "Failed to create drink"
            ];
        }

        return [
            "status" => 201,
            "message" => "Drink created successfully"
        ];
    }

    public function update($id, $data) {
        if (!is_numeric($id)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_ID",
                "message" => "ID must be numeric"
            ];
        }

        $exists = $this->model->findById($id);

        if (!$exists) {
            return [
                "status" => 404,
                "error_code" => "DRINK_NOT_FOUND",
                "message" => "Drink not found"
            ];
        }

        $result = $this->model->update($id, $data);

        if (!$result) {
            return [
                "status" => 500,
                "error_code" => "UPDATE_FAILED",
                "message" => "Failed to update drink"
            ];
        }

        return [
            "status" => 200,
            "message" => "Drink updated successfully"
        ];
    }

    public function delete($id) {
        if (!is_numeric($id)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_ID",
                "message" => "ID must be numeric"
            ];
        }

        $exists = $this->model->findById($id);

        if (!$exists) {
            return [
                "status" => 404,
                "error_code" => "DRINK_NOT_FOUND",
                "message" => "Drink not found"
            ];
        }

        $result = $this->model->delete($id);

        if (!$result) {
            return [
                "status" => 500,
                "error_code" => "DELETE_FAILED",
                "message" => "Failed to delete drink"
            ];
        }

        return [
            "status" => 200,
            "message" => "Drink deleted successfully"
        ];
    }
}