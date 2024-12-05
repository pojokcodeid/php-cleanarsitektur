<?php

namespace App\Controllers;

use App\Services\UserService;
use App\Validators\UserValidator;
use PDOException;

class UserController {
    private $userService;

    public function __construct($db) {
        $this->userService = new UserService($db);
    }

    public function createUser() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        $validator = new UserValidator();
        $validation = $validator->validate($data);

        if ($validation->failed()) {
            http_response_code(400);
            echo json_encode(["errors" => $validation->errors()]);
            return;
        }

        try {
            $result = $this->userService->createUser($data);
            http_response_code(201);
            echo json_encode($result);
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Internal Server Error"]);
        }
    }

    public function getUserById($id) {
        header('Content-Type: application/json');

        try {
            $user = $this->userService->findUserById($id);
            if ($user) {
                http_response_code(200);
                echo json_encode($user);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "User not found"]);
            }
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Internal Server Error"]);
        }
    }

    public function updateUser($id) {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        $validator = new UserValidator();
        $validation = $validator->validate($data);

        if ($validation->failed()) {
            http_response_code(400);
            echo json_encode(["errors" => $validation->errors()]);
            return;
        }

        try {
            $result = $this->userService->updateUser($id, $data);
            if ($result) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "User not found"]);
            }
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Internal Server Error"]);
        }
    }

    public function deleteUser($id) {
        header('Content-Type: application/json');

        try {
            $result = $this->userService->deleteUser($id);
            if ($result) {
                http_response_code(200);
                echo json_encode(["message" => "User deleted successfully"]);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "User not found"]);
            }
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Internal Server Error"]);
        }
    }

    public function getAllUsers() {
        header('Content-Type: application/json');

        try {
            $users = $this->userService->getAllUsers();
            http_response_code(200);
            echo json_encode($users);
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Internal Server Error"]);
        }
    }
}
