<?php

namespace App\Controllers;

use App\Services\PersonalDataService;
use App\Validators\PersonalDataValidator;
use App\Core\Logger;
use PDOException;

class PersonalDataController {
    private $personalDataService;
    private $logger;

    public function __construct($db) {
        $this->personalDataService = new PersonalDataService($db);
        $this->logger = Logger::getLogger();
    }

    public function createPersonalData() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        $validator = new PersonalDataValidator();
        $validation = $validator->validate($data);

        if ($validation->failed()) {
            $this->logger->warning("Validation failed: " . json_encode($validation->errors()));
            http_response_code(400);
            echo json_encode(["errors" => $validation->errors()]);
            return;
        }

        try {
            $result = $this->personalDataService->createPersonalData($data);
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

    public function getPersonalDataById($id) {
        header('Content-Type: application/json');

        try {
            $personalData = $this->personalDataService->findPersonalDataById($id);
            if ($personalData) {
                http_response_code(200);
                echo json_encode($personalData);
            } else {
                $this->logger->info("Personal data not found: ID $id");
                http_response_code(404);
                echo json_encode(["error" => "Personal data not found"]);
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

    public function updatePersonalData($id) {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        $validator = new PersonalDataValidator();
        $validation = $validator->validate($data);

        if ($validation->failed()) {
            $this->logger->warning("Validation failed: " . json_encode($validation->errors()));
            http_response_code(400);
            echo json_encode(["errors" => $validation->errors()]);
            return;
        }

        try {
            $result = $this->personalDataService->updatePersonalData($id, $data);
            if ($result) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                $this->logger->info("Personal data not found: ID $id");
                http_response_code(404);
                echo json_encode(["error" => "Personal data not found"]);
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

    public function deletePersonalData($id) {
        header('Content-Type: application/json');

        try {
            $result = $this->personalDataService->deletePersonalData($id);
            if ($result) {
                http_response_code(200);
                echo json_encode(["message" => "Personal data deleted successfully"]);
            } else {
                $this->logger->info("Personal data not found: ID $id");
                http_response_code(404);
                echo json_encode(["error" => "Personal data not found"]);
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

    public function getAllPersonalData() {
        header('Content-Type: application/json');

        try {
            $personalDataList = $this->personalDataService->getAllPersonalData();
            http_response_code(200);
            echo json_encode($personalDataList);
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
