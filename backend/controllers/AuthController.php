<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Provider.php';

class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login($data) {
       
        if (empty($data['email']) || empty($data['password'])) {
            return [
                "status" => 400,
                "error_code" => "MISSING_CREDENTIALS",
                "message" => "Email and password are required"
            ];
        }

        $userModel = new User($this->db);
        $providerModel = new Provider($this->db);

        
        $user = $userModel->findByEmail($data['email']);

        if ($user && password_verify($data['password'], $user['password'])) {
            unset($user['password']); // security

            return [
                "status" => 200,
                "type" => "user",
                "data" => $user
            ];
        }

    
        $provider = $providerModel->findByEmail($data['email']);

        if ($provider && password_verify($data['password'], $provider['password'])) {
            unset($provider['password']); // security

            return [
                "status" => 200,
                "type" => "provider",
                "data" => $provider
            ];
        }

        
        return [
            "status" => 401,
            "error_code" => "INVALID_CREDENTIALS",
            "message" => "Invalid email or password"
        ];
    }
}