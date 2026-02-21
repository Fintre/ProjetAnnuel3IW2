<?php 

namespace App;

session_start();

spl_autoload_register(function ($class){
        $namespaceArray = [
                            "namespace"=> ["App\\Controller\\", "App\\Core\\", "App\\Model\\", "App\\Service\\"],
                            "path"=> ["Controllers/", "Core/", "Models/", "Services/"],
                        ];
        $filname = str_ireplace($namespaceArray['namespace'],$namespaceArray['path'], $class  ). ".php";
        if(file_exists($filname)) {
            include $filname;
        }
    }
);

$uri = $_SERVER["REQUEST_URI"];
$uriExploded = explode("?",$uri);
if(is_array($uriExploded)){
    $uri = $uriExploded[0];
}
if(strlen($uri)>1){
    $uri = rtrim($uri, "/");
}

if(!file_exists("routes.yml")){
    die("Le fichier de routing routes.yml n'existe pas");
}
$routes = yaml_parse_file("routes.yml");

if(empty($routes[$uri])){
    
    $slug = ltrim($uri, '/');
    if(!empty($slug) && file_exists("Controllers/Page.php")){
        include "Controllers/Page.php";
        $pageControllerClass = "App\\Controller\\Page";
        if(class_exists($pageControllerClass)){
            $pageController = new $pageControllerClass();
            
            $_GET['slug'] = $slug;
            if(method_exists($pageController, 'renderBySlug')){
                $pageController->renderBySlug();
                exit;
            }
        }
    }
    die("Page 404");
}

if(empty($routes[$uri]["controller"]) || empty($routes[$uri]["action"])){
    die("Erreur, il n'y a aucun controller ou aucune action pour cette uri");
}

$controller = $routes[$uri]["controller"];
$action = $routes[$uri]["action"];

if(!file_exists("Controllers/".$controller.".php")){
    die("Erreur, le fichier du controller n'existe pas");
}

include "Controllers/".$controller.".php";

$controller = "App\\Controller\\".$controller;
if(!class_exists($controller)){
    die("Erreur, la class controller ".$controller." n'existe pas");
}

$objController = new $controller();

if(!method_exists($objController, $action)){
    die("Erreur, l'action ".$action." n'existe pas");
}

$objController->$action();

