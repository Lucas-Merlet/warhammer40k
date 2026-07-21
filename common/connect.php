<?php

require_once(__DIR__ . '/env.php');
chargerEnv(__DIR__ . '/../.env');

try {
    $dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=' . $_ENV['DB_CHARSET'];

    $mysqlClient = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
} catch (Exception $e) {
    die ('Erreur de connexion à la base de données : ');
}