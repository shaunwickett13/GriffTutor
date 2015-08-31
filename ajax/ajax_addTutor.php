<?php

// Inicio la sesión
@session_start();

// Load user
require_once __DIR__.'/../oop/manager/UserManager.php';
$userManager = new UserManager();
$USER = $userManager->loadSession();

// Check the login
if ($USER != null  &&  $USER instanceof Supervisor) {
    $ID = $_POST['ID'];
    if ($userManager->userExists($ID)) {
        echo 'userAlreadyExists';
    }
    else {
        $PIN = $_POST['PIN'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $tutor = new Tutor($ID, null, $name, $email,$phone);
        $tutor->setPIN($PIN);
        $userManager->register($tutor);
        echo 'true';
    }
}
else {
    echo 'false';
}