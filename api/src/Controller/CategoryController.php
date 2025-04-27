<?php

namespace Src\Controller;

use Src\Service\CategoryService;

class CategoryController extends BaseController
{
    private CategoryService $service;

    public function __construct()
    {
        $this->service = new CategoryService();
    }

    public function index(): void
    {
        $categories = $this->service->getAllCategories();
        $this->jsonResponse($categories);
    }

    public function show(string $id): void
    {
        $category = $this->service->getCategoryById($id);

        if ($category) {
            $this->jsonResponse($category);
        } else {
            $this->jsonResponse(['message' => 'Category not found'], 404);
        }
    }
}