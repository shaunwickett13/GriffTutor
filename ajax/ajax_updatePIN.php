<?php

// Inicio la sesión
@session_start();

// Load user
require_once __DIR__.'/../oop/manager/UserManager.php';
require_once __DIR__.'/../oop/Security.php';
$userManager = new UserManager();
$USER = $userManager->loadSession();

// Check the login
if ($USER != null) {
    $oldPIN = Security::obtainHash($_POST['oldPIN'],$USER->getID());
    $newPIN1 = $_POST['newPIN1'];
    $newPIN2 = $_POST['newPIN2'];
    if ($newPIN1 != $newPIN2) {
        echo 'different';
    }
    else if ($oldPIN != $USER->getPIN()) {
        echo 'wrong';
    }
    else {
        $USER->setPIN($newPIN1);
        $userManager->save($USER);
        echo 'correct';
    }
}
else {
    echo 'wrong';
}