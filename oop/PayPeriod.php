<?php

/**
 * Class PayPeriod
 * Represents a period of time that the school uses to represent a single amount of payable time.
 */

class PayPeriod {

    /**
     * This is used to determine the two week pay periods that the college uses
     * Pay periods run from Friday to Thursday and are two weeks long.
     * This date is a Friday that was the start of a pay period. Using this date
     * and jumping forward in two week increments, all the future pay periods can
     * be determined.
     */
    const SEED_DATE = "January 2 2015";

    /**
     * The length of the pay period in days
     */
    const DAYS_IN_PERIOD = 14;

    /**
     * The timezone to be used in date calculation
     */
    const TIMEZONE = "America/New_York";

    /**
     * @var int The date that this pay period starts
     * This is stored as an int as it is a UNIX timestamp which can be manipulated with methods such as date()
     */
    private $periodStartDate;

    /**
     * @var array Holds the dates that make up the pay period this instance of the class is currently set to.
     */
    private $datesInPayPeriod;


    /**
     * The period is set to the current pay period which is based on the current date and
     * the seed date or if a custom date is passed in, the pay period is based around that date.
     * @param int UNIX timestamp that represents the date the pay period starts. If no value is passed, a default
     * start date is setup to make this pay period be the pay period that corresponds to the most recent period.
     */
    public function __construct ($startDate = null) {
        date_default_timezone_set(self::TIMEZONE);


        $this -> periodStartDate = ($startDate == null ? self::setDefaultStartDate() : $startDate);
        $this -> datesInPayPeriod = self::setDaysOfPayPeriod($this -> periodStartDate);
    }


    /**
     * Sets the day the pay period starts in relation to the current day
     */
    private function setDefaultStartDate() {
        $seed = strtotime(self::SEED_DATE);
        $currentDate = time();

        /*
         * This loops through from the seed date and advances the date by the length of the
         * pay period until it finds the date that represents the start date of the pay period
         * in relation to the current date.
         *
         * The length of the pay period is added in the loop comparison to assure that the
         * period start date is not advanced past the current date.
         */
        $periodStartDate = $seed;
        $offsetString = "+ " . self::DAYS_IN_PERIOD . " days";

        // While the pay period start date is not the closest pay period start date
        while (strtotime($offsetString, $periodStartDate) < $currentDate) {
            $periodStartDate = strtotime("+ " . (self::DAYS_IN_PERIOD) . " days", $periodStartDate);
        }

        return $periodStartDate;
    }



    /**
     * Stores all the dates in the pay period into an array
     *
     * @param int $periodStartDate The date this pay period instance starts stored as an int UNIX timestamp
     * @return array All the dates in the pay period stored as UNIX timestamps
     */
    private function setDaysOfPayPeriod ($periodStartDate) {
        $dates = array();
        for ($x = 0; $x < self::DAYS_IN_PERIOD; $x++) {

            if ($x == 0) {
                $date = $periodStartDate;
            }
            else {
                $date = strtotime("+ " . $x . " days", $periodStartDate);
            }

            $dates[$x] = $date;
        }

        return $dates;
    }

    /**
     * Gets all the dates in the currently set pay period
     * @return array All the dates in the pay period stored as UNIX timestamps
     */
    public function getDatesInPayPeriod() {
        return $this -> datesInPayPeriod;
    }

    /**
     * Advance to the next pay period in relation to the currently set pay period
     */
    public function setToNextPayPeriod() {
        $this -> periodStartDate = strtotime("+ " . (self::DAYS_IN_PERIOD) . " days", $this -> periodStartDate);
        $this -> datesInPayPeriod = self::setDaysOfPayPeriod($this -> periodStartDate);
    }

    /**
     * Go back to the previous pay period in relation to the currently set pay period
     */
    public function setToPreviousPayPeriod() {
        $this -> periodStartDate = strtotime("- " . (self::DAYS_IN_PERIOD) . " days", $this -> periodStartDate);
        $this -> datesInPayPeriod = self::setDaysOfPayPeriod($this -> periodStartDate);
    }

    /**
     * @return int The number of days the make up the pay period
     */
    public function getLengthOfPayPeriod () {
        return self::DAYS_IN_PERIOD;
    }
}