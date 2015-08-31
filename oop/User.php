<?php

require_once __DIR__.'/Security.php';

abstract class User {

    protected $ID;
    protected $PIN;

    /**
     * Constructor for a generic User
     *
     * @param int $ID
     * @param int $PIN
     * @param string name
     */
    function __construct ($ID, $PIN, $name) {
        $this -> ID = $ID;
        $this -> PIN = $PIN;
        $this -> name = $name;
    }

    /**
     * @return int
     */
    public function getID () {
        return $this -> ID;
    }

    /**
     * @return int
     */
    public function getPIN () {
        return $this -> PIN;
    }

    /**
     *
     * @param int $newPIN
     */
    public function setPIN ($newPIN, $hash = true) {

        if ($newPIN >= 0 and $newPIN <= 9999) {
            if ($hash)
                $newPIN = Security::obtainHash($newPIN, $this->getID());
            $this -> PIN = $newPIN;
        }
        else {
            // Some type of error handling
        }

    }
    /**
     * @param int $name
     */
    public function setName ($name) {
        $this -> name = $name;
    }

    /**
     * @return name
     */
    public function getName () {
        return $this->name;
    }
}

?>