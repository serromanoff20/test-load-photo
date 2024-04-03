<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__.'/../', '.env');
$dotenv->load();

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . $_ENV["DB_HOST"] . ';dbname=' . $_ENV["DB_DATABASE"],
    'username' => $_ENV["DB_USERNAME"],
    'password' => $_ENV["DB_PASSWORD"],
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
