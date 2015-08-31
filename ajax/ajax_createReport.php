<?php
	require_once(__DIR__ . '/../admin/checkLogin.php');
	require_once(__DIR__ . '/../oop/TutoringReport.php');
	require_once(__DIR__ . '/../oop/manager/ReportManager.php');


	$studentName = $_POST['name'];
	$reportText = $_POST['report'];
	
	error_log($studentName . $reportText);

	$report = new TutoringReport($USER, $studentName, $reportText);

	$reportManager = new ReportManager();

	$reportManager -> save($report);

	echo 'success';
?>
