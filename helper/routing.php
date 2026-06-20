<?php
// helper/routing.php

/**
 * Sistem routing sederhana untuk memetakan URL ke file PHP yang sesuai.
 * Bisa di-extend untuk dukung dynamic routes, query params, dll.
 * Contoh penggunaan:
 * $routes = [
 *     '/' => 'src/views/HomeView.php',
 *     '/about' => 'src/views/AboutView.php',
 * ];
 * $result = resolveRoute($routes);
 * $page = $result['page'];
 * $route = $result['route'];
 * include $page;
 */
function resolveRoute($routes, $default = 'src/views/404.php') {
    // Normalize and extract the current path
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $baseDir = dirname($scriptName);
    if ($baseDir === '\\' || $baseDir === '/') {
        $baseDir = '';
    }

    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Strip base directory from request URI to make routes relative
    if (!empty($baseDir) && strpos($requestUri, $baseDir) === 0) {
        $requestUri = substr($requestUri, strlen($baseDir));
    }

    if ($requestUri !== '/' && substr($requestUri, -1) === '/') {
        $requestUri = rtrim($requestUri, '/');
    }
    if (empty($requestUri)) {
        $requestUri = '/';
    }

    // Resolve page if route exists
    if (array_key_exists($requestUri, $routes)) {
        return ['page' => $routes[$requestUri], 'route' => $requestUri];
    }

    // Fallback: 404 page, but still return the normalized route
    http_response_code(404);
    return ['page' => $default, 'route' => $requestUri];
}

/**
 * Helper to generate URL paths correctly formatted with the base subdirectory.
 */
function routeUrl($path = '') {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $baseDir = dirname($scriptName);
    if ($baseDir === '\\' || $baseDir === '/') {
        $baseDir = '';
    }
    return $baseDir . $path;
}

?>