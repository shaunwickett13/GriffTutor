<?php

require_once __DIR__.'/../oop/manager/UserManager.php';
$userManager = new UserManager();

$day = $_POST['day'];
$hour = $_POST['hour'];

$tutors = $userManager->getByHour($day,$hour);
$out = array();
foreach($tutors as $tutor) {
    $t = array();
    $t['name'] = $tutor->getName();
    $t['email'] = $tutor->getEmail();
    $t['bio'] = $tutor->getBio();
    $out[] = $t;
}

echo json_encode($out);

?>