<?php

namespace Src\Database\Factory;

use Src\Database\Connection;

class Course
{
    public static function seed(): void
    {
        $courses = json_decode(file_get_contents(__DIR__ . '/../../../data/course_list.json'), true);

        $db = Connection::getInstance(false);

        foreach ($courses as $course) {
            $stmt = $db->prepare('INSERT INTO courses (id, title, description, image_preview, category_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())');
            $stmt->execute([
                $course['course_id'],
                $course['title'],
                $course['description'],
                $course['image_preview'],
                $course['category_id'],
            ]);
        }
    }
}