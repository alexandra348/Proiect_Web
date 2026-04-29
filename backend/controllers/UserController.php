<?php
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $model;

    public function __construct($db) {
        $this->model = new User($db);
    }

    
    public function register($data) {
        if (
            empty($data['name']) ||
            empty($data['email']) ||
            empty($data['password'])
        ) {
            return [
                "status" => 400,
                "error_code" => "MISSING_FIELDS",
                "message" => "Name, email and password are required"
            ];
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_EMAIL",
                "message" => "Invalid email format"
            ];
        }

        
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        $result = $this->model->create($data);

        if (!$result) {
            return [
                "status" => 500,
                "error_code" => "REGISTER_FAILED",
                "message" => "Failed to register user"
            ];
        }

        return [
            "status" => 201,
            "message" => "User registered successfully"
        ];
    }

    
    public function getUserById($id) {
        if (!is_numeric($id)) {
            return [
                "status" => 400,
                "error_code" => "INVALID_ID",
                "message" => "ID must be numeric"
            ];
        }

        $user = $this->model->findById($id);

        if (!$user) {
            return [
                "status" => 404,
                "error_code" => "USER_NOT_FOUND",
                "message" => "User not found"
            ];
        }

        
        unset($user['password']);

        return [
            "status" => 200,
            "data" => $user
        ];
    }
}