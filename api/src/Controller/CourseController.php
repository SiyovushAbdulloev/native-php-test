<?php

namespace Src\Controller;

use Src\Service\CourseService;

class CourseController extends BaseController
{
    private CourseService $service;

    public function __construct()
    {
        $this->service = new CourseService();
    }

    public function index(?string $category): void
    {
        $categories = $this->service->getAllCourses($category);
        $this->jsonResponse($categories);
    }

    public function show(string $id): void
    {
        $category = $this->service->getCourseById($id);

        if ($category) {
            $this->jsonResponse($category);
        } else {
            $this->jsonResponse(['message' => 'Course not found'], 404);
        }
    }
}