<?php

require_once(__DIR__ . '/../oop/PayPeriod.php');

date_default_timezone_set("America/New_York");

$startDate = $_POST['startDate'];
$payperiod = new PayPeriod(strtotime($startDate));

$payperiod -> setToNextPayPeriod();
$datesInPeriod = $payperiod -> getDatesInPayPeriod();
$formattedDates = array();

foreach ($datesInPeriod as $date) {
    $formattedDates[] = date("l n/j/y", $date);
}

echo json_encode($formattedDates);

?>
