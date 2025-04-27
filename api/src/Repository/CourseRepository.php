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

    public function getAll(?string $category): array
    {
        if (!empty($category)) {
            $stmt = $this->db->prepare('SELECT * FROM courses WHERE category_id = :category_id');
            $stmt->execute(['category_id' => $category]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $this->db->prepare('SELECT * FROM courses');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
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