<?php

require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../repositories/ProviderRepository.php';

class AuthService {

    private UserRepository $userRepo;
    private ProviderRepository $providerRepo;

    public function __construct($db)
    {
        $this->userRepo = new UserRepository($db);
        $this->providerRepo = new ProviderRepository($db);
    }

    public function login(array $data): array
    {
        if (empty($data['email']) || empty($data['password'])) {
            throw new Exception("Email and password are required");
        }

        
        $user = $this->userRepo->findByEmail($data['email']);

        if ($user && password_verify($data['password'], $user['password'])) {
            unset($user['password']);

            return [
                "type" => "user",
                "data" => $user
            ];
        }

        
        $provider = $this->providerRepo->findByEmail($data['email']);

        if ($provider && password_verify($data['password'], $provider['password'])) {
            unset($provider['password']);

            return [
                "type" => "provider",
                "data" => $provider
            ];
        }

        throw new Exception("Invalid email or password");
    }
}