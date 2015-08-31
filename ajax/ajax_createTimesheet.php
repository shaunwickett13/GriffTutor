<?php

require_once('checkLogin.php');

if ($USER != null) {
    require_once(__DIR__ . '/../oop/PayPeriod.php');
    require_once(__DIR__ . '/../oop/SingleDay.php');
    require_once(__DIR__ . '/../oop/Timesheet.php');
    require_once(__DIR__ . '/../oop/manager/TimesheetManager.php');


    $hoursArray = json_decode($_POST['hours']);
    $startDate = $_POST['startDate'];
    $payperiod = new PayPeriod(strtotime($startDate));

    $timesheet = new Timesheet($USER, $payperiod, $hoursArray);
    $timesheetManager = new TimesheetManager();
    $timesheetManager->save($timesheet);

    $timesheet->toPDF();
}

?>