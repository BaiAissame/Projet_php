<?php

require_once __DIR__ . "/core/Router.php";
require_once __DIR__ . "/controllers/HomeController.php";
require_once __DIR__ . "/controllers/LoginController.php";
require_once __DIR__ . "/controllers/RegisterController.php";
require_once __DIR__ . "/controllers/DashboardController.php";
require_once __DIR__ . "/controllers/LogoutController.php";
require_once __DIR__ . "/controllers/PhotoController.php";
require_once __DIR__ . "/controllers/GroupController.php";



function startSessionIfNeeded()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
}

startSessionIfNeeded();


$router = new Router();
$router->get("/", HomeController::class, "index");
$router->get("/login", LoginController::class, "index");
$router->post("/login", LoginController::class, "post");

$router->get("/groupe", GroupController::class, "index");
$router->get("/groupe/{id}", GroupController::class, "chat"); 

$router->get("/register", RegisterController::class, "index");
$router->post("/register", RegisterController::class, "post");
$router->get("/dashboard", DashboardController::class,"index");
$router->get("/logout", LogoutController::class, "index"); 
$router->get("/upload", PhotoController::class, "index");
$router->post("/upload/{id}", PhotoController::class, "upload");
$router->get("/photo/{public_link}", PhotoController::class, "viewPublicPhoto");
$router->post("/delete-photo/{id}", PhotoController::class, "deletePhoto");
$router->get("/groupe/{id}/members", GroupController::class, "manageMembers");
$router->post("/groupe/{id}/add-member", GroupController::class, "addUser");
$router->post("/groupe/{id}/remove-member", GroupController::class, "removeUser");
$router->post("/groupe/{id}/update-role", GroupController::class, "updateMemberRole");


$router->start();
