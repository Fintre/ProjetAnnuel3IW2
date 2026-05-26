<?php

namespace App;

session_start();

spl_autoload_register(
    function ($class) {
        $namespaceArray = [
                            "namespace" => ["App\\Controller\\", "App\\Core\\", "App\\Model\\", "App\\Service\\", "App\\Repository\\"],
                            "path" => ["Controllers/", "Core/", "Models/", "Services/", "Repository/"],
                        ];
        $filname = str_ireplace($namespaceArray['namespace'], $namespaceArray['path'], $class). ".php";
        if (file_exists($filname)) {
            include $filname;
        }
    }
);

$uri = $_SERVER["REQUEST_URI"];
$uriExploded = explode("?", $uri);
if (is_array($uriExploded)) {
    $uri = $uriExploded[0];
}
if (strlen($uri) > 1) {
    $uri = rtrim($uri, "/");
}

if (!file_exists("routes.yml")) {
    die("Le fichier de routing routes.yml n'existe pas");
}
$routes = yaml_parse_file("routes.yml");

if (empty($routes[$uri])) {
    http_response_code(404);
    $render = new \App\Core\Render("404", "headerFooter");
    $render->render();
    exit;
}

if (empty($routes[$uri]["controller"]) || empty($routes[$uri]["action"])) {
    die("Erreur, il n'y a aucun controller ou aucune action pour cette uri");
}

$controller = $routes[$uri]["controller"];
$action = $routes[$uri]["action"];

$currentMethod = $routes[$uri]["method"];
if ($currentMethod !== strtoupper($_SERVER['REQUEST_METHOD'])) {
    header("Location: /");
    exit;
}

$access = $routes[$uri]["access"] ?? null;
$isLoggedIn = isset($_SESSION["is_active"]) && $_SESSION["is_active"] === true;
if ($access === "guest" && $isLoggedIn) {
    header("Location: /accounts");
    exit;
}
if ($access === "auth" && !$isLoggedIn) {
    header("Location: /");
    exit;
}

if (!file_exists("Controllers/".$controller.".php")) {
    die("Erreur, le fichier du controller n'existe pas");
}

include "Controllers/".$controller.".php";

$controller = "App\\Controller\\".$controller;
if (!class_exists($controller)) {
    die("Erreur, la class controller ".$controller." n'existe pas");
}

$objController = new $controller();

if (!method_exists($objController, $action)) {
    die("Erreur, l'action ".$action." n'existe pas");
}

$objController->$action();
