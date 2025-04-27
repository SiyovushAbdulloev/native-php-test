<?php

namespace Src\Database\Factory;

use Src\Database\Connection;

class Category
{
    public static function seed(): void
    {
        $categories = json_decode(file_get_contents(__DIR__ . '/../../../data/categories.json'), true);

        $db = Connection::getInstance(false);

        foreach ($categories as $category) {
            $stmt = $db->prepare('INSERT INTO categories (id, name, parent_id, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())');
            $stmt->execute([
                $category['id'],
                $category['name'],
                $category['parent'] ?? null,
            ]);
        }
    }
}