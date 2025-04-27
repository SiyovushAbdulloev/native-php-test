<?php

namespace Src\Service;

use Src\Repository\CategoryRepository;

class CategoryService
{
    private CategoryRepository $repo;

    public function __construct()
    {
        $this->repo = new CategoryRepository();
    }

    public function getAllCategories(): array
    {
        $categories = $this->repo->getAll();
        return $this->buildTree($categories);
    }

    private function buildTree(array $categories, $parentId = null): array
    {
        $tree = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] === $parentId) {
                $children = $this->buildTree($categories, $category['id']);
                if (!empty($children)) {
                    $category['children'] = $children;
                } else {
                    $category['children'] = [];
                }
                $tree[] = $category;
            }
        }

        return $tree;
    }

    public function getCategoryById(string $id): ?array
    {
        return $this->repo->getById($id);
    }
}