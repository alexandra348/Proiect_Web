<?php

require_once __DIR__ . '/../services/StatisticsService.php';
require_once __DIR__ . '/../exceptions/StatisticsException.php';

class StatisticsController
{
    private StatisticsService $service;

    public function __construct(StatisticsService $service)
    {
        $this->service = $service;
    }

    public function dashboard()
    {
        try {
            $data = [
                "top_drinks" => $this->service->topDrinks(),
                "top_categories" => $this->service->topCategories(),
                "top_providers" => $this->service->topProviders(),
                "top_rated" => $this->service->topRated(),
                "top_ingredients" => $this->service->topIngredients(),
                "avoided_ingredients" => $this->service->mostAvoidedIngredients(),
                "top_restrictions" => $this->service->topRestrictions()
            ];

            return [
                "status" => 200,
                "data" => $data
            ];

        } catch (StatisticsException $e) {
            return [
                "status" => 500,
                "error" => "Dashboard data could not be loaded"
            ];
        } catch (Exception $e) {
            return [
                "status" => 500,
                "error" => "Unexpected server error"
            ];
        }
    }

    public function topDrinks()
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->topDrinks()
            ];
        } catch (StatisticsException $e) {
            return [
                "status" => 500,
                "error" => "Failed to load top drinks"
            ];
        }
    }

    public function topCategories()
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->topCategories()
            ];
        } catch (StatisticsException $e) {
            return [
                "status" => 500,
                "error" => "Failed to load top categories"
            ];
        }
    }

    public function topProviders()
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->topProviders()
            ];
        } catch (StatisticsException $e) {
            return [
                "status" => 500,
                "error" => "Failed to load top providers"
            ];
        }
    }

    public function topRated()
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->topRated()
            ];
        } catch (StatisticsException $e) {
            return [
                "status" => 500,
                "error" => "Failed to load top rated drinks"
            ];
        }
    }

    public function topIngredients()
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->topIngredients()
            ];
        } catch (StatisticsException $e) {
            return [
                "status" => 500,
                "error" => "Failed to load top ingredients"
            ];
        }
    }

    public function mostAvoidedIngredients()
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->mostAvoidedIngredients()
            ];
        } catch (StatisticsException $e) {
            return [
                "status" => 500,
                "error" => "Failed to load avoided ingredients"
            ];
        }
    }

    public function topRestrictions()
    {
        try {
            return [
                "status" => 200,
                "data" => $this->service->topRestrictions()
            ];
        } catch (StatisticsException $e) {
            return [
                "status" => 500,
                "error" => "Failed to load restrictions"
            ];
        }
    }
}