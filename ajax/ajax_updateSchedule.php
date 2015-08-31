<?php

// Inicio la sesión
@session_start();

// Load user
require_once __DIR__.'/../oop/manager/UserManager.php';
require_once(__DIR__.'/../oop/Schedule.php');
$userManager = new UserManager();
$USER = $userManager->loadSession();

// Check the login
if ($USER != null) {
    $newSchedule = new Schedule();
    foreach (Schedule::$DAYS as $day) {
        if (isset($_POST[$day])) {
            foreach ($_POST[$day] as $hour)
                $newSchedule->addHour($day,$hour);
        }
    }
    $USER->setSchedule($newSchedule);
    $userManager->save($USER);
    echo 'correct';
}
else {
    echo 'fake';
}