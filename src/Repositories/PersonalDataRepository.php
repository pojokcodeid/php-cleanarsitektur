<?php

namespace App\Repositories;

use App\Models\PersonalData;
use PDO;

class PersonalDataRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createPersonalData($data) {
        $query = "INSERT INTO personal_data (user_id, address, phone) VALUES (:user_id, :address, :phone)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':phone', $data['phone']);

        if ($stmt->execute()) {
            return new PersonalData($this->db->lastInsertId(), $data['user_id'], $data['address'], $data['phone']);
        }

        return null;
    }

    public function findById($id) {
        $query = "SELECT * FROM personal_data WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new PersonalData($data['id'], $data['user_id'], $data['address'], $data['phone']);
        }

        return null;
    }

    public function updatePersonalData($id, $data) {
        $query = "UPDATE personal_data SET address = :address, phone = :phone WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':phone', $data['phone']);

        if ($stmt->execute()) {
            return $this->findById($id);
        }

        return null;
    }

    public function deletePersonalData($id) {
        $query = "DELETE FROM personal_data WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getAllPersonalData() {
        $query = "SELECT * FROM personal_data";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $personalDataList = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $personalDataList[] = new PersonalData($data['id'], $data['user_id'], $data['address'], $data['phone']);
        }

        return $personalDataList;
    }
}
