<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Provider.php';

class AuthController {
    private $userModel;
    private $providerModel;

    public function __construct() {
        $this->userModel = new User();
        $this->providerModel = new Provider();
    }

    //REGISTER USER
    public function registerUser($data) {
        $result = $this->userModel->create(
            $data['name'],
            $data['email'],
            $data['password']
        );

        return ["success" => $result];
    }

    //REGISTER PROVIDER
    public function registerProvider($data) {
        $result = $this->providerModel->create(
            $data['name'],
            $data['email'],
            $data['password'],
            $data['type'],
            $data['location']
        );

        return ["success" => $result];
    }

    //LOGIN (USER sau PROVIDER)
    public function login($email, $password) {
        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return ["status" => "success", "role" => "user", "data" => $user];
        }

        $provider = $this->providerModel->findByEmail($email);

        if ($provider && password_verify($password, $provider['password'])) {
            return ["status" => "success", "role" => "provider", "data" => $provider];
        }

        return ["status" => "error", "message" => "Invalid credentials"];
    }
}