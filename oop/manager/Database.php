<?php

require_once __DIR__ . '/config.php';

class Database
{
    private $connection;

    /*
     * Initialize the database
     */
    public function __construct()
    {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
        if ($this->connection->connect_errno) {
            die('Error: ' . $this->connection->connect_error);
        }
        $this->connection->query("SET NAMES 'utf8'");
    }

    /*
     * Close the connection
     */
    public function __destruct()
    {
        $this->connection->close();
    }

    /*
     * Execute a query
     */
    public function query($query)
    {
        return $this->connection->query($query);
    }

    /*
     * Execute a query retrieving one row
     */
    public function get($query)
    {
        $result = $this->connection->query($query);
        if ($result === FALSE) {
            die($this->connection->error);
        }
        if ($result->num_rows == 0)
            return array();
        else {
            $out = $result->fetch_array();
            $result->free();
            return $out;
        }
    }

    /*
     * Execute a query with more than one row as result
     */
    public function getArray($query)
    {
        $result = $this->connection->query($query);
        $out = array();
        if (!$result) {
            return $out;
        }
        while ($row = $result->fetch_array()) {
            $out[] = $row;
        }
        $result->free();
        return $out;
    }

    /*
     * Aux functions for inserting and updating
     */
    public function insert($table, $array)
    {
        $keysToInsert = array_keys($array);
        $elementsToInsert = array_values($array);
        $query = 'INSERT INTO ' . $table . ' (';
        foreach ($keysToInsert as $k) {
            $query .= $this->cleanString($k) . ',';
        }
        $query = rtrim($query, ",");
        $query .= ') VALUES (';
        foreach ($elementsToInsert as $e) {
            $query .= '"' . $this->cleanString($e) . '",';
        }
        $query = rtrim($query, ",");
        $query .= ');';
        $this->query($query);
        return $this->connection->insert_id;
    }
    public function update($table, $array, Array $primary)
    {
        $query = 'UPDATE ' . $table . ' SET ';
        foreach ($array as $k => $v) {
            $query .= $this->cleanString($k) . ' = "' . $this->cleanString($v) . '", ';
        }
        $query = rtrim($query, ", ");
        $query .= ' WHERE ';
        foreach ($primary as $k => $v) {
            $query .=  $this->cleanString($k) . ' = "' .  $this->cleanString($v) . '"';
            break;
        }
        return $this->query($query);
    }

    /*
     * Clean a string of mysql code
     */
    public function cleanString($string)
    {
        return $this->connection->escape_string($string);
    }

}

?>