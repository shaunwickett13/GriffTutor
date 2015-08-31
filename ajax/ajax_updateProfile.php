<?php

// Inicio la sesión
@session_start();

// Load user
require_once __DIR__.'/../oop/manager/UserManager.php';
$userManager = new UserManager();
$USER = $userManager->loadSession();

// Check the login
if ($USER != null) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $bio = $_POST['bio'];
    $USER->setName($name);
    $USER->setEmail($email);
    $USER->setPhone($phone);
    $USER->setBio($bio);
    $userManager->save($USER);
    echo 'true';
}
else {
    echo 'false';
}