<?php

require_once __DIR__ . '/../services/CategoryService.php';
require_once __DIR__ . '/../exceptions/CategoryException.php';

class CategoryController {

    private CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function getAllCategories()
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->getAll()
            ];
        } catch (CategoryException $e) {
            return [
                "status" => 500,
                "error_code" => "FETCH_FAILED",
                "message" => $e->getMessage()
            ];
        }
    }

    public function getCategoryById($id)
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->findById($id)
            ];

        } catch (CategoryException $e) {

            $message = $e->getMessage();
            $status = ($message === "Category not found") ? 404 : 400;

            return [
                "status" => $status,
                "error_code" => "CATEGORY_ERROR",
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
                "message" => "Category created successfully"
            ];

        } catch (CategoryException $e) {
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
                "message" => "Category updated successfully"
            ];

        } catch (CategoryException $e) {

            $message = $e->getMessage();
            $status = ($message === "Category not found") ? 404 : 400;

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
                "message" => "Category deleted successfully"
            ];

        } catch (CategoryException $e) {

            $message = $e->getMessage();
            $status = ($message === "Category not found") ? 404 : 400;

            return [
                "status" => $status,
                "error_code" => "DELETE_FAILED",
                "message" => $message
            ];
        }
    }
}