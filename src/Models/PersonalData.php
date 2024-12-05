<?php

namespace App\Models;

class PersonalData {
    public $id;
    public $userId;
    public $address;
    public $phone;

    public function __construct($id, $userId, $address, $phone) {
        $this->id = $id;
        $this->userId = $userId;
        $this->address = $address;
        $this->phone = $phone;
    }
}
