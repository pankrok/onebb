<?php
use Symfony\Component\HttpFoundation\Request;
include (__DIR__ . '/../updater/Kernel.php');

$kernel = new Kernel('update', true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);