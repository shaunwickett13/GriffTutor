<?php

require_once __DIR__.'/Manager.php';
require_once __DIR__.'/../Schedule.php';

class ScheduleManager extends Manager {

    const TABLE_SCHEDULE = 'Schedule';


    function __construct() {
        parent::__construct();
    }

    /**
     * Convert a DB record to user object
     * @param array $r
     * @return null|Supervisor|Tutor
     */
    function convertRecord(array $record) {
        $schedule = new Schedule();
        foreach ($record as $r) {
            $schedule->addHour($r['day'],$r['hour']);
        }
        return $schedule;
    }


    /**
     * Get the user with the given ID
     * @param $userID
     * @return null|Supervisor|Tutor
     */
    function get($userID) {
        $userID = $this->clean($userID);
        $query = 'SELECT day, hour FROM '.self::TABLE_SCHEDULE.' WHERE userID = '.$userID;
        $record = $this->getDB()->getArray($query);
        return $this->convertRecord($record);
    }

    function getAll() {
        $query = 'SELECT day, hour FROM '.self::TABLE_SCHEDULE.' GROUP BY day,hour';
        $record = $this->getDB()->getArray($query);
        return $this->convertRecord($record);
    }

    function getUserIDs($day,$hour) {
        $day = $this->clean($day);
        $hour = $this->clean($hour);
        $query = 'SELECT userID FROM '.self::TABLE_SCHEDULE.' WHERE day = "'.$day.'" AND hour = "'.$hour.'"';
        $record = $this->getDB()->getArray($query);
        $out = array();
        foreach ($record as $r)
            $out[] = $r['userID'];
        return $out;
    }


    /**
     * Save the user, both in the DB and in the session
     *
     */
    function save(Schedule $schedule, $userID) {
        $userID = $this->clean($userID);
        // Remove all the hours of the user
        $query = 'DELETE * FROM '.self::TABLE_SCHEDULE.' WHERE userID = '.$userID;
        $this->getDB()->query($query);

        // Add all the new hours
        foreach ($schedule->getSchedule() as $day=>$hours) {
            foreach ($hours as $h) {
                $save = array('userID'=>$userID, 'day'=>$day, 'hour'=>$h);
                $this->getDB()->insert(self::TABLE_SCHEDULE,$save);
            }
        }
    }

    /**
     * Removes a given tutors hours from the schedule.
     * @param int $ID A tutor's ID number
     */
    function removeSchedule($ID) {
        $query = "DELETE FROM ". self::TABLE_SCHEDULE ." WHERE userID = ".$ID;
        $this -> getDB() -> query($query);
    }
}