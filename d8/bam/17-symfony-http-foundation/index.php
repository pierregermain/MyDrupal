<?php

// Enable Autoloading
require_once __DIR__ . '/vendor/autoload.php';

// Get Symfony Classes
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Create Request Object
$request = Request::createFromGlobals();

// Get the "name" parameter. If not present assign "World" to it
$input = $request->get('name', 'World');

// Create Response Object
$response = new Response(sprintf('Hello %s', htmlspecialchars($input, ENT_QUOTES, 'UTF-8')));

// Return Response Object
$response->send();