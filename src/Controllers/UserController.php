<?php

namespace App\Controllers;

use App\Services\UserService;
use App\Validators\UserValidator;
use App\Core\Logger;
use PDOException;

class UserController {
    private $userService;
    private $logger;

    public function __construct($db) {
        $this->userService = new UserService($db);
        $this->logger = Logger::getLogger();
    }

    public function getAllUsers() {
        header('Content-Type: application/json');

        try {
            $users = $this->userService->getAllUsers();
            http_response_code(200);
            echo json_encode($users);
        } catch (PDOException $e) {
            $this->logger->error("Database error: " . $e->getMessage());
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            $this->logger->error("Internal server error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(["error" => "Internal Server Error"]);
        }
    }

    public function createUser() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        $validator = new UserValidator();
        $validation = $validator->validate($data);

        if ($validation->failed()) {
            $this->logger->warning("Validation failed: " . json_encode($validation->errors()));
            http_response_code(400);
            echo json_encode(["errors" => $validation->errors()]);
            return;
        }

        try {
            $result = $this->userService->createUser($data);
            http_response_code(201);
            echo json_encode($result);
        } catch (PDOException $e) {
            $this->logger->error("Database error: " . $e->getMessage());
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            $this->logger->error("Internal server error: " . $e->getMessage());
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
                $this->logger->info("User not found: ID $id");
                http_response_code(404);
                echo json_encode(["error" => "User not found"]);
            }
        } catch (PDOException $e) {
            $this->logger->error("Database error: " . $e->getMessage());
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            $this->logger->error("Internal server error: " . $e->getMessage());
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
            $this->logger->warning("Validation failed: " . json_encode($validation->errors()));
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
                $this->logger->info("User not found: ID $id");
                http_response_code(404);
                echo json_encode(["error" => "User not found"]);
            }
        } catch (PDOException $e) {
            $this->logger->error("Database error: " . $e->getMessage());
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            $this->logger->error("Internal server error: " . $e->getMessage());
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
                $this->logger->info("User not found: ID $id");
                http_response_code(404);
                echo json_encode(["error" => "User not found"]);
            }
        } catch (PDOException $e) {
            $this->logger->error("Database error: " . $e->getMessage());
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            $this->logger->error("Internal server error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(["error" => "Internal Server Error"]);
        }
    }
}
