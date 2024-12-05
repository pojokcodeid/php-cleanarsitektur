<?php

namespace App\Repositories;

use App\Models\User;
use PDO;

class UserRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createUser($data) {
        $query = "INSERT INTO users (name, email) VALUES (:name, :email)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);

        if ($stmt->execute()) {
            return new User($this->db->lastInsertId(), $data['name'], $data['email']);
        }

        return null;
    }

    public function findById($id) {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new User($data['id'], $data['name'], $data['email']);
        }

        return null;
    }

    public function updateUser($id, $data) {
        $query = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);

        if ($stmt->execute()) {
            return $this->findById($id);
        }

        return null;
    }

    public function deleteUser($id) {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getAllUsers() {
        $query = "SELECT * FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $users = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User($data['id'], $data['name'], $data['email']);
        }

        return $users;
    }
}
