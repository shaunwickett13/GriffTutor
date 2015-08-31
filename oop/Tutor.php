<?php

require_once(__DIR__.'/Schedule.php');
require_once(__DIR__.'/User.php');

class Tutor extends User {

    /**
     * @var string The email of the tutor
     */
    protected $email;

    /**
     * @var string The phone number of the tutor
     */
    protected $phone;

    /**
     * @var Schedule The schedule associated with this tutor
     */
    protected $schedule;

    /**
     * @var array List of all the timesheets for this user
     */
    protected $timesheets;

    /**
     * @var string Not used
     */
    protected $picture;

    /**
     * @var string The tutors short biography
     */
    protected $bio;

    /**
     * The constructor for a Tutor
     *
     * @param int $ID
     * @param int $PIN
     * @param string $email
     * @param string $phone
     * @param Schedule $schedule
     * @param Timesheet $timesheet
     * @param string $picture Path to the file that contains the tutors picture
     * @param string $bio The tutors provided biography
     */
    public function __construct ($ID, $PIN, $name, $email = "", $phone = "", $picture = "", $bio = "", Schedule $schedule = null, array $timesheets = array()) {
        parent::__construct($ID, $PIN,$name);
        $this -> email = $email;
        $this -> phone = $phone;
        $this -> schedule = ($schedule != null)? $schedule : new Schedule();
        $this -> timesheets = $timesheets;
        $this -> picture = $picture;
        $this -> bio = $bio;
    }

    /**
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @param string $bio
     */
    public function setBio($bio)
    {
        $this->bio = $bio;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param string $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return Schedule
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @param Schedule $schedule
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * @return array
     */
    public function getTimesheets()
    {
        return $this->timesheets;
    }

    /**
     * @param array $timesheets
     */
    public function setTimesheets($timesheets)
    {
        $this->timesheets = $timesheets;
    }





}

?>