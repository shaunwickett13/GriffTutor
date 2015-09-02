<?php

require_once __DIR__.'/oop/manager/Database.php';
require_once __DIR__.'/oop/Security.php';
require_once __DIR__.'/oop/manager/UserManager.php';

echo "\nENTER INFO FOR NEW ADMIN USER\n";
echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n";

$ID = readline("ID: ");

$name = readline("Name: ");

$PIN = readline("4 digit pin: ");

$hashedPIN = Security::obtainHash($PIN, $ID);
echo 'hashed PIN is '. $hashedPIN;

$query = "INSERT INTO `User` (`userID`, `PIN`, `name`, `role`) VALUES
($ID, `$hashedPIN`, `$name`, `supervisor`);";

echo "\ncreating DB connection\n";
$db = new Database();

echo "running query\n";
$db -> query($query);

$um = new UserManager();

$success = $um -> userExists($ID);

if ($success) {
	echo "\nadmin user created";
}
else {
	echo "\nfailed to create user";
}
echo '';
?>
