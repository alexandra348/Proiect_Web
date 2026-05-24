<?php

require_once __DIR__ . '/../services/RestrictionService.php';
require_once __DIR__ . '/../exceptions/RestrictionException.php';

class RestrictionController {

    private RestrictionService $service;

    public function __construct(RestrictionService $service)
    {
        $this->service = $service;
    }

    public function getAllRestrictions()
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->getAll()
            ];
        } catch (RestrictionException $e) {
            return [
                "status" => 500,
                "error_code" => "FETCH_FAILED",
                "message" => $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                "status" => 500,
                "error_code" => "SERVER_ERROR",
                "message" => "Unexpected server error"
            ];
        }
    }

    public function getRestrictionById($id)
    {
        try {
            $restriction = $this->service->findById($id);

            return [
                "status" => 200,
                "data" => $restriction
            ];

        } catch (RestrictionException $e) {

            $message = $e->getMessage();

            $status = ($message === "Restriction not found") ? 404 : 400;

            return [
                "status" => $status,
                "error_code" => "RESTRICTION_ERROR",
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
                "message" => "Restriction created successfully"
            ];

        } catch (RestrictionException $e) {
            return [
                "status" => 400,
                "error_code" => "CREATE_FAILED",
                "message" => $e->getMessage()
            ];
        }
    }

    public function update($id, $data)
    {
        try {
            $this->service->update($id, $data);

            return [
                "status" => 200,
                "message" => "Restriction updated successfully"
            ];

        } catch (RestrictionException $e) {

            $message = $e->getMessage();

            $status = ($message === "Restriction not found") ? 404 : 400;

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
                "message" => "Restriction deleted successfully"
            ];

        } catch (RestrictionException $e) {

            $message = $e->getMessage();

            $status = ($message === "Restriction not found") ? 404 : 400;

            return [
                "status" => $status,
                "error_code" => "DELETE_FAILED",
                "message" => $message
            ];
        }
    }
}