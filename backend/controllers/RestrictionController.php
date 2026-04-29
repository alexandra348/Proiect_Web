<?php
require_once __DIR__ . '/../models/Restriction.php';

class RestrictionController {
    private $model;

    public function __construct($db) {
        $this->model = new Restriction($db);
    }

    
    public function getAllRestrictions() {
        return [
            "status" => 200,
            "data" => $this->model->getAll()
        ];
    }

    
    public function getRestrictionById($id) {
        if (!is_numeric($id)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_ID",
                "message" => "ID must be numeric"
            ];
        }

        $restriction = $this->model->findById($id);

        if (!$restriction) {
            return [
                "status" => 404,
                "error_code" => "RESTRICTION_NOT_FOUND",
                "message" => "Restriction not found"
            ];
        }

        return [
            "status" => 200,
            "data" => $restriction
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
                "message" => "Failed to create restriction"
            ];
        }

        return [
            "status" => 201,
            "message" => "Restriction created successfully"
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
                "error_code" => "RESTRICTION_NOT_FOUND",
                "message" => "Restriction not found"
            ];
        }

        $result = $this->model->update($id, $data);

        if (!$result) {
            return [
                "status" => 500,
                "error_code" => "UPDATE_FAILED",
                "message" => "Failed to update restriction"
            ];
        }

        return [
            "status" => 200,
            "message" => "Restriction updated successfully"
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
                "error_code" => "RESTRICTION_NOT_FOUND",
                "message" => "Restriction not found"
            ];
        }

        $result = $this->model->delete($id);

        if (!$result) {
            return [
                "status" => 500,
                "error_code" => "DELETE_FAILED",
                "message" => "Failed to delete restriction"
            ];
        }

        return [
            "status" => 200,
            "message" => "Restriction deleted successfully"
        ];
    }
}