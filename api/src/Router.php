<?php

namespace Src;

use Src\Controller\CategoryController;
use Src\Controller\CourseController;

class Router {
    public static function route() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if (str_starts_with($uri, '/public/categories')) {
            $controller = new CategoryController();

            if (in_array($uri, ['/public/categories', '/public/categories/']) && $method === 'GET') {
                $controller->index();
            } elseif (preg_match('#^/public/categories/([a-f0-9\-]+)$#', $uri, $matches)) {
                $id = $matches[1];

                if ($method === 'GET') {
                    $controller->show($id);
                } else {
                    self::notFound();
                }
            } else {
                self::notFound();
            }
        }
        if (str_starts_with($uri, '/public/courses')) {
            $controller = new CourseController();

            if (in_array($uri, ['/public/courses', '/public/courses/']) && $method === 'GET') {
                $category = $_GET['category'] ?? '';
                $controller->index($category);
            } elseif (preg_match('#^/public/courses/([a-f0-9\-]+)$#', $uri, $matches)) {
                $id = $matches[1];

                if ($method === 'GET') {
                    $controller->show($id);
                } else {
                    self::notFound();
                }
            } else {
                self::notFound();
            }
        }
    }

    private static function notFound(string $message = 'Not found'): void
    {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['message' => $message]);
    }
}