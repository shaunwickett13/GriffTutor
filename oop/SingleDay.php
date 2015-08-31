<?php


/**
 * Class SingleDay
 * Represents a single day that a tutor potentially worked
 */
class SingleDay {

    /**
     * @var int The UNIX timestamp that represents the date
     */
    private $date;

    /**
     * @var bool Whether the tutor did or did not work on this day
     */
    private $didWork;

    /**
     * @var string A string representation of the hours that the tutor worked in the day
     */
    private $hoursWorked;

    /**
     * @var bool Whether the tutor had to take a lunch or not
     */
    private $lunchTaken;

    /**
     * @var string The time the tutor started work for the day
     */
    private $startTime;

    /**
     * @var string The time the tutor finished work for the day
     */
    private $endTime;

    /**
     * @var float The total number of hours worked in the day
     */
    private $numHoursWorked;

    /**
     * @param int $theDate UNIX timestamp representing the date that corresponds to this day
     * @param string $theHoursWorked The string of hours worked in the format "hhmm-hhmm, hhmm-hhmm"
     */
    public function __construct ($theDate, $theHoursWorked) {
        $this -> date = $theDate;

        $theHoursWorked = str_replace(" ", "", $theHoursWorked);
        $hoursArray = explode(",", $theHoursWorked);

        self::setupOtherFields($hoursArray);
    }

    /**
     * @param array $hoursArray Represents the hours worked in that day or "0" if tutor did not work
     */
    private function setupOtherFields ($hoursArray) {
        if ($hoursArray[0] == "0" || $hoursArray[0] == 0) {
            $this -> didWork = false;
            $this -> hoursWorked = "";
            $this -> lunchTaken = false;
            $this -> startTime = "";
            $this -> endTime = "";
            $this -> numHoursWorked = 0.0;
        }
        else {
            $this -> didWork = true;
            $this -> hoursWorked = self::setHoursWorked($hoursArray);
            $this -> startTime = self::setStartTime($hoursArray);
            $this -> endTime = self::setEndTime($hoursArray);
            self::setTotalHoursAndLunchTaken($hoursArray);
        }
    }

    /**
     * @param array $hoursArray The array of strings representing the hours worked in the day
     * @return string A list of all the times worked in the day
     */
    private function setHoursWorked ($hoursArray) {
        $retString = "";

        foreach ($hoursArray as $hourChunk) {
            $hourChunkSplit = explode("-", $hourChunk);
            $startTime = $hourChunkSplit[0];
            $endTime = $hourChunkSplit[1];

            $startTimeTo12 = self::convert24hTo12h($startTime, false);
            $endTimeTo12 = self::convert24hTo12h($endTime, false);

            $retString .= $startTimeTo12 . "-" . $endTimeTo12 . ", ";
        }

        return substr($retString, 0, strlen($retString) - 2);
    }

    /**
     * @param array $hoursArray The array of strings representing the hours worked in the day
     * @return string The time that the tutor started working for the day
     */
    private function setStartTime ($hoursArray) {
        $firstChunk = $hoursArray[0];

        $chunkArray = explode("-", $firstChunk);

        return self::convert24hTo12h($chunkArray[0]);
    }

    /**
     * @param array $hoursArray The array of strings representing the hours worked in the day
     * @return string The time that the tutor finished working for the day
     */
    private function setEndTime ($hoursArray) {
        $numHourChunks = count($hoursArray);

        $endChunk = $hoursArray[$numHourChunks - 1];

        $chunkArray = explode("-", $endChunk);

        return self::convert24hTo12h($chunkArray[1]);
    }

    /**
     * Sets this instance's lunchTaken and numHoursWorked fields.
     *
     * @param array $hoursArray The array of strings representing the hours worked in the day
     */
    private function setTotalHoursAndLunchTaken ($hoursArray) {
        $hourTotal = 0.0;

        foreach ($hoursArray as $hourChunk) {
            $chunkArray = explode("-", $hourChunk);


            $firstChunk = self::convertStringTimeToMinutesPastMidnight($chunkArray[0]);
            $secondChunk = self::convertStringTimeToMinutesPastMidnight($chunkArray[1]);

            // Each chunk is stored as minutes past midnight so by subtracting you
            // get the net minutes worked.
            $totalMinutesInChunk = $secondChunk - $firstChunk;

            $hourTotal += ($totalMinutesInChunk / 60.0);
        }

        if ($hourTotal >= 6.5) {
            $hourTotal -= 0.5;
            $this -> lunchTaken = true;
        }
        else {
            $this -> lunchTaken = false;
        }

        $this -> numHoursWorked = $hourTotal;
    }

    /**
     * @param string $timeString A time stored in 24h time format as a string "hhmm" where "hh" are the hours and "mm"
     * are the minutes
     * @return string A time stored in 12h format with either an "am" or "pm" at the end of the returned string
     */
    private function convert24hTo12h ($timeString, $showAMPM = true) {
        $hour = substr($timeString, 0, 2);
        $minute = substr($timeString, 2, 2);

        $hourFirstDigit = substr($hour, 0, 1);
        $hourSecondDigit = substr($hour, 1, 1);

        if ($hourFirstDigit == "0") {
            $intHour = (int) $hourSecondDigit;
        }
        else {
            $intHour = (int) $hour;
        }

        if ($intHour > 12 ) {
            $intHour -= 12;
            $AMPM = "pm";
        }
        else if ($intHour == 12) {
            $AMPM = "pm";
        }
        else if ($intHour == 0) {
            $intHour = 12;
            $AMPM = "am";
        }
        else {
            $AMPM = "am";
        }

        return $intHour . ":" . $minute . ($showAMPM ? $AMPM : "");
    }

    /**
     * @param string$timeString A time stored in 24h time format as a string "hhmm" where "hh" are the hours and "mm"
     * are the minutes
     * @return int The number of minutes this time is past midnight
     */
    private function convertStringTimeToMinutesPastMidnight ($timeString) {
        $hour = substr($timeString, 0, 2);
        $minute = substr($timeString, 2, 2);

        $hourFirstDigit = substr($hour, 0, 1);
        $hourSecondDigit = substr($hour, 1, 1);

        // If the hour is 0015 it means that it is just 15 minutes past midnight so return the mintutes
        if ($hourFirstDigit == "0" && $hourSecondDigit == "0") {
            $intHour = 0;
        }
        else if ($hourFirstDigit == "0") {
            $intHour = (int) $hourSecondDigit;
        }
        else {
            $intHour = (int) $hour;
        }

        $intMinutes = (int) $minute;

        $MINUTES_IN_HOUR = 60;
        return ($intHour * $MINUTES_IN_HOUR) + $intMinutes;
    }

    /**
     * @return int The date as a UNIX timestamp
     */
    public function getDate () {
        return $this -> date;
    }

    /**
     * @return bool true if the tutor worked any hours this day, false otherwise
     */
    public function didWork () {
        return $this -> didWork;
    }

    /**
     * @return string The hours that the tutor worked on this day, the empty string if tutor did not work
     */
    public function getHoursWorked () {
        return $this -> hoursWorked;
    }

    /**
     * @return string The time that the tutor started work this day, the empty string if tutor did not work
     */
    public function getStartTime () {
        return $this -> startTime;
    }

    /**
     * @return string The time that the tutor finished work this day, the empty string if tutor did not work
     */
    public function getEndTime () {
        return $this -> endTime;
    }

    /**
     * @return bool true if the tutor had to take a lunch (worked > 6.5 hours), false if no lunch was taken
     */
    public function wasLunchTaken () {
        return $this -> lunchTaken;
    }

    /**
     * @return float The number of hours the tutor worked during the day, returns 0 if tutor did not work
     */
    public function getNumHoursWorked () {
        return $this -> numHoursWorked;
    }



}
