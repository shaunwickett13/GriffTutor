<?php
require_once(__DIR__.'/PayPeriod.php');
require_once(__DIR__.'/SingleDay.php');

/**
 * Class TimesheetHours
 * Represents all the hours a specific tutor worked over the course of a given pay period.
 */
class TimesheetHours {

    /**
     * @var array An array of SingleDays that hold all the information of the days and times that the tutor worked
     * over the course of the pay period.
     */
    private $payperiodSchedule;

    /**
     * @var float The total number of hours worked in this pay period
     */
    private $numHoursWorkedInPeriod;

    /**
     * When calling the constructor make sure to pass in the PayPeriod that is relative to the hours the tutor worked as
     * as well as an array of hours.
     *
     * The array of hours must have an entry for every day in the pay period stored as such:
     * The days the tutor did not work the array should hold 0 to represent the fact they did not work
     * The days the tutor did work the times they worked should be passed in as strings in the following format:
     * "hhmm-hhmm" or "hhmm-hhmm, hhmm-hhmm, hhmm-hhmm" where "hh" is the hour and "mm" is the minute.
     * TIMES MUST BE IN 24 HOUR FORMAT!
     *
     * @param PayPeriod $payperiod An instance of the PayPeriod class that holds the dates in relation to the tutors
     * hours worked
     * @param array $hoursArray An array of hours stored as strings, with an entry for each day, even the days that the
     * tutor did not work
     */
    public function __construct(Payperiod $payperiod, Array $hoursArray) {
        $this -> payperiodSchedule = self::setPayPeriodSchedule($payperiod, $hoursArray);

        $this -> numHoursWorkedInPeriod = self::setNumHoursWorked();
    }

    /**
     * @param PayPeriod $payperiod The payperiod that relates to the hours that the tutor worked
     * @param array $hoursArray holds the strings that represent all the hours worked by the tutor over the payperiod
     * @return array An array of instances of the SingleDay class which represents the hours worked over the payperiod
     */
    private function setPayPeriodSchedule(PayPeriod $payperiod, Array $hoursArray) {
        if ($payperiod -> getLengthOfPayPeriod() != count($hoursArray)) {
            echo count($hoursArray);
            echo $payperiod -> getLengthOfPayPeriod();
            // Something bad because they both do not have the same number of entries
            echo "Internal error: The pay period and number of entries in the hours array were not equal in length.";
            die();
        }

        $finalArray = array();

        $lengthOfArrays = $payperiod -> getLengthOfPayPeriod();
        $payperiodDates = $payperiod -> getDatesInPayPeriod();

        for ($i = 0; $i < $lengthOfArrays; $i++) {
            $singleDay = new SingleDay($payperiodDates[$i], $hoursArray[$i]);
            $finalArray[] = $singleDay;
        }

        return $finalArray;
    }

    /**
     * Loops through all the days that the tutor worked over the course of the payperiod and sums up the hours to
     * return the total number of hours worked over the pay period
     *
     * @return float The total number of hours worked over the pay period
     */
    private function setNumHoursWorked () {
        $arrayOfDays = $this -> payperiodSchedule;

        $runningTotal = 0.0;

        foreach ($arrayOfDays as $singleDay) {
            $runningTotal += $singleDay -> getNumHoursWorked();
        }

        return $runningTotal;
    }


    /**
     * @return array Instances of the SingleDay class the hold the information about when the tutor worked each day of
     * the pay period.
     */
    public function getPayPeriodSchedule () {
        return $this -> payperiodSchedule;
    }

    /**
     * @return float The number of hours the tutor worked in the pay period
     */
    public function getTotalHoursWorked () {
        return $this -> numHoursWorkedInPeriod;
    }
}