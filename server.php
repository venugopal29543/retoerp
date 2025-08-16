<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

// URL rewriting for Laravel
$_SERVER['SCRIPT_NAME'] = '/public/index.php';

// Parse the URI from the request
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// Static file handling: If the request is for a file that exists in the public directory
// serve it directly without going through the Laravel router
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

// Otherwise, pass the request to Laravel's front controller
require_once __DIR__.'/public/index.php';
