<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\UserController;
use App\Controllers\PersonalDataController;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$db = (new Database())->getConnection();
$userController = new UserController($db);
$personalDataController = new PersonalDataController($db);

$requestMethod = $_SERVER["REQUEST_METHOD"];
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$pathParts = explode('/', trim($path, '/'));

$controller = $pathParts[0] ?? '';
$id = $pathParts[1] ?? null;

switch ($controller) {
    case 'users':
        switch ($requestMethod) {
            case 'POST':
                $userController->createUser();
                break;
            case 'GET':
                if ($id) {
                    $userController->getUserById($id);
                } else {
                    $userController->getAllUsers();
                }
                break;
            case 'PUT':
                if ($id) {
                    $userController->updateUser($id);
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "ID is required for update"]);
                }
                break;
            case 'DELETE':
                if ($id) {
                    $userController->deleteUser($id);
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "ID is required for delete"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["error" => "Method not allowed"]);
                break;
        }
        break;
    case 'personaldata':
        switch ($requestMethod) {
            case 'POST':
                $personalDataController->createPersonalData();
                break;
            case 'GET':
                if ($id) {
                    $personalDataController->getPersonalDataById($id);
                } else {
                    $personalDataController->getAllPersonalData();
                }
                break;
            case 'PUT':
                if ($id) {
                    $personalDataController->updatePersonalData($id);
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "ID is required for update"]);
                }
                break;
            case 'DELETE':
                if ($id) {
                    $personalDataController->deletePersonalData($id);
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "ID is required for delete"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["error" => "Method not allowed"]);
                break;
        }
        break;
    default:
        http_response_code(404);
        echo json_encode(["error" => "Not found"]);
        break;
}
