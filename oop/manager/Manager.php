<?php

require_once __DIR__ . '/Database.php';

abstract class Manager {

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    protected function getDB() {
        return $this->db;
    }
    
    protected function clean($string) {
        return $this->db->cleanString($string);
    }

    abstract function convertRecord(Array $record);
    abstract function get($ID);
    abstract function getAll();

} 