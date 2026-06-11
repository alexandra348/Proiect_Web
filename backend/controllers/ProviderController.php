<?php

require_once __DIR__ . '/../services/ProviderService.php';
require_once __DIR__ . '/../exceptions/ProviderException.php';

class ProviderController {

    private ProviderService $service;

    public function __construct(ProviderService $service)
    {
        $this->service = $service;
    }

    public function getAllProviders()
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->getAll()
            ];
        } catch (ProviderException $e) {
            return [
                "status" => 500,
                "error_code" => "FETCH_FAILED",
                "message" => $e->getMessage()
            ];
        }
    }

    public function getProviderById($id)
    {
        try {
            $provider = $this->service->findById($id);

            return [
                "status" => 200,
                "data" => $provider
            ];

        } catch (ProviderException $e) {

            $message = $e->getMessage();

            $status = ($message === "Provider not found") ? 404 : 400;

            return [
                "status" => $status,
                "error_code" => "PROVIDER_ERROR",
                "message" => $message
            ];
        }
    }

    public function create($data)
    {
        try {
            $this->service->create($data);

            return [
                "status" => 201,
                "message" => "Provider created successfully"
            ];

        } catch (ProviderException $e) {
            return [
                "status" => 400,
                "error_code" => "CREATE_FAILED",
                "message" => $e->getMessage()
            ];
        }
    }

    public function update($id, $data, $role)
    {
        try {
            $this->service->update($id, $data, $role);

            return [
                "status" => 200,
                "message" => "Provider updated successfully",
                "passwordChanged"=>!empty($data['password'])
            ];

        } catch (ProviderException $e) {

            $message = $e->getMessage();

            $status = ($message === "Provider not found") ? 404 : 400;

            return [
                "status" => $status,
                "error_code" => "UPDATE_FAILED",
                "message" => $message
            ];
        }
    }

    public function delete($id)
    {
        try {
            $this->service->delete($id);

            return [
                "status" => 200,
                "message" => "Provider deleted successfully"
            ];

        } catch (ProviderException $e) {

            $message = $e->getMessage();

            $status = ($message === "Provider not found") ? 404 : 400;

            return [
                "status" => $status,
                "error_code" => "DELETE_FAILED",
                "message" => $message
            ];
        }
    }
}