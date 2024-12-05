<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService {
    private $userRepository;

    public function __construct($db) {
        $this->userRepository = new UserRepository($db);
    }

    public function createUser($data) {
        return $this->userRepository->createUser($data);
    }

    public function findUserById($id) {
        return $this->userRepository->findById($id);
    }

    public function updateUser($id, $data) {
        return $this->userRepository->updateUser($id, $data);
    }

    public function deleteUser($id) {
        return $this->userRepository->deleteUser($id);
    }

    public function getAllUsers() {
        return $this->userRepository->getAllUsers();
    }
}
