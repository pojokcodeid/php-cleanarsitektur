<?php

namespace App\Services;

use App\Repositories\PersonalDataRepository;

class PersonalDataService {
    private $personalDataRepository;

    public function __construct($db) {
        $this->personalDataRepository = new PersonalDataRepository($db);
    }

    public function createPersonalData($data) {
        return $this->personalDataRepository->createPersonalData($data);
    }

    public function findPersonalDataById($id) {
        return $this->personalDataRepository->findById($id);
    }

    public function updatePersonalData($id, $data) {
        return $this->personalDataRepository->updatePersonalData($id, $data);
    }

    public function deletePersonalData($id) {
        return $this->personalDataRepository->deletePersonalData($id);
    }

    public function getAllPersonalData() {
        return $this->personalDataRepository->getAllPersonalData();
    }
}
