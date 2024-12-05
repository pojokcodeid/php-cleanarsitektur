<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use App\Core\Router;
use App\Core\Logger;
use App\Controllers\UserController;
use App\Controllers\PersonalDataController;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$db = (new Database())->getConnection();
$logger = Logger::getLogger();

// Membuat instance dari Router
$router = new Router($db);

// Menentukan route
$router->add('users', 'UserController@getAllUsers', ['GET']);
$router->add('users/create', 'UserController@createUser', ['POST']);
$router->add('users/{id}', 'UserController@getUserById', ['GET']);
$router->add('users/update/{id}', 'UserController@updateUser', ['PUT']);
$router->add('users/delete/{id}', 'UserController@deleteUser', ['DELETE']);
$router->add('personaldata', 'PersonalDataController@getAllPersonalData', ['GET']);
$router->add('personaldata/create', 'PersonalDataController@createPersonalData', ['POST']);
$router->add('personaldata/{id}', 'PersonalDataController@getPersonalDataById', ['GET']);
$router->add('personaldata/update/{id}', 'PersonalDataController@updatePersonalData', ['PUT']);
$router->add('personaldata/delete/{id}', 'PersonalDataController@deletePersonalData', ['DELETE']);

// Jalankan routing
$router->dispatch();
