<?php

require_once __DIR__ . '/../services/AuthService.php';

class AuthController {

    private AuthService $service;

    public function __construct($db)
    {
        $this->service = new AuthService($db);
    }

    public function login($data)
    {
        try {
            $result = $this->service->login($data);

            return [
                "status" => 200,
                "type" => $result["type"],
                "data" => $result["data"]
            ];

        } catch (Exception $e) {

            $status = ($e->getMessage() === "Invalid email or password") ? 401 : 400;

            return [
                "status" => $status,
                "error_code" => "LOGIN_FAILED",
                "message" => $e->getMessage()
            ];
        }
    }
}