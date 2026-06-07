<?php

require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../repositories/ProviderRepository.php';
require_once __DIR__ . '/../utils/JWTUtils.php';

use Utils\JWTUtils;

class AuthService {

    private UserRepository $userRepo;
    private ProviderRepository $providerRepo;
    private JWTUtils $jwtUtils;

    public function __construct($db)
    {
        $this->userRepo = new UserRepository($db);
        $this->providerRepo = new ProviderRepository($db);
        $this->jwtUtils = new JWTUtils();
    }

    public function login(array $data): array
    {
        if (empty($data['email']) || empty($data['password'])) {
            throw new Exception("Email and password are required");
        }

        
        $user = $this->userRepo->findByEmail($data['email']);

        if ($user && password_verify($data['password'], $user['password'])) {
            unset($user['password']);

            $token = $this->jwtUtils->generateToken($user);

            return [
                  "data" => [
                  "token" => $token,
                  "user" => $user
                ]
            ];
        }

        
        $provider = $this->providerRepo->findByEmail($data['email']);

        if ($provider && password_verify($data['password'], $provider['password'])) {
            unset($provider['password']);

            $provider['role'] = 'provider';

            $token = $this->jwtUtils->generateToken($provider);

            return [
                "data" => [
                    "token" => $token,
                    "user" => $provider
                ]
            ];
        }

        throw new Exception("Invalid email or password");
    }
}