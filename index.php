<?php

require_once 'ApiHandler.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

handleApiRequest($requestUri, $requestMethod);
