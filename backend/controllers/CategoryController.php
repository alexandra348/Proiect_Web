<?php
require_once __DIR__ . '/../models/Category.php';

class CategoryController {
    private $model;

    public function __construct($db) {
        $this->model = new Category($db);
    }

    public function getAllCategories() {
        return [
            "status" => 200,
            "data" => $this->model->getAll()
        ];
    }

    public function getCategoryById($id) {
        if (!is_numeric($id)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_ID",
                "message" => "ID must be numeric"
            ];
        }

        $cat = $this->model->findById($id);

        if (!$cat) {
            return [
                "status" => 404,
                "error_code" => "CATEGORY_NOT_FOUND",
                "message" => "Category not found"
            ];
        }

        return [
            "status" => 200,
            "data" => $cat
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
                "message" => "Failed to create category"
            ];
        }

        return [
            "status" => 201,
            "message" => "Category created successfully"
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
                "error_code" => "CATEGORY_NOT_FOUND",
                "message" => "Category not found"
            ];
        }

        $result = $this->model->update($id, $data);

        if (!$result) {
            return [
                "status" => 500,
                "error_code" => "UPDATE_FAILED",
                "message" => "Failed to update category"
            ];
        }

        return [
            "status" => 200,
            "message" => "Category updated successfully"
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
                "error_code" => "CATEGORY_NOT_FOUND",
                "message" => "Category not found"
            ];
        }

        $result = $this->model->delete($id);

        if (!$result) {
            return [
                "status" => 500,
                "error_code" => "DELETE_FAILED",
                "message" => "Failed to delete category"
            ];
        }

        return [
            "status" => 200,
            "message" => "Category deleted successfully"
        ];
    }
}