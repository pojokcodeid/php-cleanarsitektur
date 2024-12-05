<?php

namespace App\Controllers;

use App\Services\PersonalDataService;
use PDOException;

class PersonalDataController {
    private $personalDataService;

    public function __construct($db) {
        $this->personalDataService = new PersonalDataService($db);
    }

    public function createPersonalData() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        try {
            $result = $this->personalDataService->createPersonalData($data);
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

    public function getPersonalDataById($id) {
        header('Content-Type: application/json');

        try {
            $personalData = $this->personalDataService->findPersonalDataById($id);
            if ($personalData) {
                http_response_code(200);
                echo json_encode($personalData);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Personal data not found"]);
            }
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Internal Server Error"]);
        }
    }

    public function updatePersonalData($id) {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        try {
            $result = $this->personalDataService->updatePersonalData($id, $data);
            if ($result) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Personal data not found"]);
            }
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
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
                http_response_code(404);
                echo json_encode(["error" => "Personal data not found"]);
            }
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
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
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Internal Server Error"]);
        }
    }
}
