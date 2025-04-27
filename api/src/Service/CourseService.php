<?php

namespace Src\Service;

use Src\Repository\CourseRepository;

class CourseService
{
    private CourseRepository $repo;

    public function __construct()
    {
        $this->repo = new CourseRepository();
    }

    public function getAllCourses(?string $category): array
    {
        $courses = $this->repo->getAll($category);
        $categories = [];

        foreach ($courses as $course) {
            if (!in_array($course['category_id'], $categories)) {
                $categories[] = $course['category_id'];
            }
        }

        $categories = $this->repo->getCategoriesById($categories);
        $categoriesMap = [];
        foreach ($categories as $cat) {
            $categoriesMap[$cat['id']] = $cat['name'];
        }

        return array_map(function ($course) use ($categoriesMap) {
            $course['category'] = $categoriesMap[$course['category_id']] ?? 'Unknown';
            return $course;
        }, $courses);
    }

    public function getCourseById(string $id): ?array
    {
        return $this->repo->getById($id);
    }
}