<?php
require_once __DIR__ . '/../models/Ingredient.php';

class IngredientController {
    private $model;

    public function __construct($db) {
        $this->model = new Ingredient($db);
    }

    public function getAllIngredients() {
        return [
            "status" => 200,
            "data" => $this->model->getAll()
        ];
    }

    public function getIngredientById($id) {
        if (!is_numeric($id)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_ID",
                "message" => "ID must be numeric"
            ];
        }

        $ingredient = $this->model->findById($id);

        if (!$ingredient) {
            return [
                "status" => 404,
                "error_code" => "INGREDIENT_NOT_FOUND",
                "message" => "Ingredient not found"
            ];
        }

        return [
            "status" => 200,
            "data" => $ingredient
        ];
    }

    public function create($data) {
        if (empty($data['name'])) {
            return [
                "status" => 400,
                "error_code" => "MISSING_NAME",
                "message" => "Name is required"
            ];
        }

        $result = $this->model->create($data);

        if (!$result) {
            return [
                "status" => 500,
                "error_code" => "CREATE_FAILED",
                "message" => "Failed to create ingredient"
            ];
        }

        return [
            "status" => 201,
            "message" => "Ingredient created successfully"
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
                "error_code" => "INGREDIENT_NOT_FOUND",
                "message" => "Ingredient not found"
            ];
        }

        $result = $this->model->update($id, $data);

        if (!$result) {
            return [
                "status" => 500,
                "error_code" => "UPDATE_FAILED",
                "message" => "Failed to update ingredient"
            ];
        }

        return [
            "status" => 200,
            "message" => "Ingredient updated successfully"
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
                "error_code" => "INGREDIENT_NOT_FOUND",
                "message" => "Ingredient not found"
            ];
        }

        $result = $this->model->delete($id);

        if (!$result) {
            return [
                "status" => 500,
                "error_code" => "DELETE_FAILED",
                "message" => "Failed to delete ingredient"
            ];
        }

        return [
            "status" => 200,
            "message" => "Ingredient deleted successfully"
        ];
    }
}