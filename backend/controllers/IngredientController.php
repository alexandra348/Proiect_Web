<?php

require_once __DIR__ . '/../services/IngredientService.php';
require_once __DIR__ . '/../exceptions/IngredientException.php';

class IngredientController {

    private IngredientService $service;

    public function __construct(IngredientService $service)
    {
        $this->service = $service;
    }

    public function getAllIngredients()
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->getAll()
            ];
        } catch (IngredientException $e) {
            return [
                "status" => 500,
                "error_code" => "FETCH_FAILED",
                "message" => $e->getMessage()
            ];
        }
    }

    public function getIngredientById($id)
    {
        try {
            $ingredient = $this->service->findById($id);

            return [
                "status" => 200,
                "data" => $ingredient
            ];

        } catch (IngredientException $e) {

            $message = $e->getMessage();

            $status = ($message === "Ingredient not found") ? 404 : 400;

            return [
                "status" => $status,
                "error_code" => "INGREDIENT_ERROR",
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
                "message" => "Ingredient created successfully"
            ];

        } catch (IngredientException $e) {
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
                "message" => "Ingredient updated successfully"
            ];

        } catch (IngredientException $e) {

            $message = $e->getMessage();

            $status = ($message === "Ingredient not found") ? 404 : 400;

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
                "message" => "Ingredient deleted successfully"
            ];

        } catch (IngredientException $e) {

            $message = $e->getMessage();

            $status = ($message === "Ingredient not found") ? 404 : 400;

            return [
                "status" => $status,
                "error_code" => "DELETE_FAILED",
                "message" => $message
            ];
        }
    }
}