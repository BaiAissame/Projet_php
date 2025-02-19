<?php

class LogoutController
{
    public static function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        session_destroy();

        header("Location: /login");
        exit();
    }
}
