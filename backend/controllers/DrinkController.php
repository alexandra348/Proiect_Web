<?php

require_once __DIR__ . '/../services/DrinkService.php';
require_once __DIR__ . '/../exceptions/DrinkException.php';

class DrinkController {

    private DrinkService $service;

    public function __construct(DrinkService $service)
    {
        $this->service = $service;
    }

    public function getAllDrinks()
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->getAll()
            ];
        } catch (DrinkException $e) {
            return [
                "status" => 500,
                "error_code" => "FETCH_FAILED",
                "message" => $e->getMessage()
            ];
        }
    }

    public function getDrinkById($id)
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->findById($id)
            ];

        } catch (DrinkException $e) {

            $message = $e->getMessage();

            $status = ($message === "Drink not found") ? 404 : 400;

            return [
                "status" => $status,
                "error_code" => "DRINK_ERROR",
                "message" => $message
            ];
        }
    }

    public function searchDrink($term)
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->searchDrink($term)
            ];

        } catch (DrinkException $e) {

            $message = $e->getMessage();

            $status = ($message === "No drink found") ? 404 : 400;

            return [
                "status" => $status,
                "error_code" => "DRINK_ERROR",
                "message" => $message
            ];
        }
    }


    public function getDrinkByProvider($id)
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->getByProvider($id)
            ];

        } catch (DrinkException $e) {

            $message = $e->getMessage();

            $status = ($message === "Drink not found") ? 404 : 400;

            return [
                "status" => $status,
                "error_code" => "DRINK_ERROR",
                "message" => $message
            ];
        }
    }

    public function create($data)
    {
        try {
            $id = $this->service->create($data);

            return [
                "drink_id" => $id,
                "status" => 201,
                "message" => "Drink created successfully"
            ];

        } catch (DrinkException $e) {
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
                "message" => "Drink updated successfully"
            ];

        } catch (DrinkException $e) {

            $message = $e->getMessage();

            $status = ($message === "Drink not found") ? 404 : 400;

            return [
                "status" => $status,
                "error_code" => "UPDATE_FAILED",
                "message" => $message
            ];
        }
    }

    public function delete($id, $user)
    {
        try {
            $this->service->delete($id, $user);

            return [
                "status" => 200,
                "message" => "Drink deleted successfully"
            ];

        } catch (DrinkException $e) {

            $message = $e->getMessage();

            $status = ($message === "Drink not found") ? 404 : 400;

            return [
                "status" => $status,
                "error_code" => "DELETE_FAILED",
                "message" => $message
            ];
        }
    }
}