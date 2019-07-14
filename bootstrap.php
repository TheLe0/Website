<?php

    include_once __DIR__ . '/vendor/autoload.php';

    use Dotenv\Dotenv;
    use src\Util\Bind;
    use src\DAO\Connection;

    $dotenv = Dotenv::create(__DIR__);
    $dotenv->load();

    Bind::set('host',  $_ENV['HOST']);
    Bind::set('port',  $_ENV['PORT']);
    Bind::set('db',  $_ENV['DB']);
    Bind::set('db_user',  $_ENV['DB_USER']);
    Bind::set('db_senha',  $_ENV['DB_SENHA']);

    $conn = new Connection();
    Bind::set('conn', $conn->connect());
