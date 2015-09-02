<?php

require_once __DIR__.'/oop/manager/Database.php';
require_once __DIR__.'/oop/Security.php';

echo "Enter info for new admin user";
echo "____________________________________"
$ID = readline("Enter ID: ");

$name = readline("Enter name: ");

$PIN = readline("Enter 4 digit pin: ");

$hashedPW = Security::obtainHash($PIN, $ID);

$query = "INSERT INTO `User` (`userID`, `PIN`, `name`, `role`) VALUES
($ID, $PIN, $name, 'supervisor');";

$db = new Database();
$db -> $this->query($query);

echo "\nAdmin user created!";

?>