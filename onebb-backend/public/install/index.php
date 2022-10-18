<?php
use Symfony\Component\HttpFoundation\Request;
include (__DIR__ . '/kernel.php');

$kernel = new Kernel('install', true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);