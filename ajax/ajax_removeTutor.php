<?php
	require_once(__DIR__ . '/../oop/manager/UserManager.php');
	$userID = $_POST['tutorID'];
	$userManager = new UserManager();
	$userManager -> destroyTutor($userID);
	echo 'success';
?>