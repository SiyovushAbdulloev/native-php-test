<?php

namespace Src\Repository;

use PDO;
use Src\Database\Connection;

class CourseRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function getAll(?string $category = null): array
    {
        $sql = "
            WITH RECURSIVE category_path (id, name, parent_id, root_id, root_name) AS (
              SELECT
                id,
                name,
                parent_id,
                id AS root_id,
                name AS root_name
              FROM categories
              WHERE parent_id IS NULL
    
              UNION ALL
    
              SELECT
                c.id,
                c.name,
                c.parent_id,
                cp.root_id,
                cp.root_name
              FROM categories c
              INNER JOIN category_path cp ON c.parent_id = cp.id
            )
            SELECT
              courses.*,
              cp.root_name AS main_category
            FROM courses
            LEFT JOIN category_path cp ON courses.category_id = cp.id
        ";

        $params = [];

        if (!empty($category)) {
            $sql .= ' WHERE courses.category_id = :category_id';
            $params['category_id'] = $category;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(string $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM courses WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function getCategoriesById(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("SELECT id, name FROM categories WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}