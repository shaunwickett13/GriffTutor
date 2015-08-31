<?php
require_once(__DIR__.'/User.php');

class Supervisor extends User {


    /**
     * Constructor for the Supervisor class
     *
     * @param int $ID
     * @param int $PIN
     */
    public function __construct ($ID, $PIN,$name) {
        parent::__construct($ID, $PIN,$name);
    }

}

?>