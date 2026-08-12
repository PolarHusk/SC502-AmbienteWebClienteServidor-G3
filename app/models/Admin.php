<?php
require_once '../app/config/Database.php';

class Admin {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }
}