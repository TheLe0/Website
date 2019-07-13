<?php
    include_once __DIR__ . '/vendor/autoload.php';

    use Dotenv\Dotenv;

    $dotenv = Dotenv::create(__DIR__);
    $dotenv->load();

    $host = $_ENV['HOST'];
    $port = $_ENV['PORT'];
    $db = $_ENV['DB'];
    $db_user = $_ENV['DB_USER'];
    $db_senha = $_ENV['DB_SENHA'];
