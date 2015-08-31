<?php

// DEBUGGING
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/Manager.php';
require_once __DIR__.'/ScheduleManager.php';
require_once __DIR__.'/TimesheetManager.php';
require_once __DIR__.'/ReportManager.php';
require_once __DIR__.'/../User.php';
require_once __DIR__.'/../Tutor.php';
require_once __DIR__.'/../Supervisor.php';
require_once __DIR__.'/../Security.php';

class UserManager extends Manager {

    const TABLE_USER = 'User', TABLE_TUTOR = 'Tutor';
    const SUPERVISOR = 'supervisor', TUTOR = 'tutor';

    private static $scheduleManager = null;

    function __construct() {
        parent::__construct();
        if (self::$scheduleManager == null)
            self::$scheduleManager = new ScheduleManager();
    }

    /**
     * Convert a DB record to user object
     * @param array $r
     * @return null|Supervisor|Tutor
     */
    function convertRecord(Array $r) {
        // If it is empty, do nothing
        if (sizeof($r)==0)
            return null;

        // Load role and work depending on it
        $user = null;
        $role = $r['role'];

        // Load common attributes
        $ID = $r['userID'];
        $PIN = $r['PIN'];
        $name = $r['name'];

        // Supervisor
        if ($role == self::SUPERVISOR) {
            $user = new Supervisor($ID,$PIN, $name);
        }
        // If it is a tutor
        else if ($role == self::TUTOR) {
            $email = $r['email'];
            $phone = $r['phone']!=0? $r['phone'] : '';
            $bio = $r['bio'];
            $picture = $r['picture'];
            $timesheets = array(); // TODO
            $schedule = self::$scheduleManager->get($r['userID']);
            $user = new Tutor($ID,$PIN,$name, $email,$phone,$picture,$bio,$schedule,$timesheets);
        }
        return $user;
    }


    /**
     * Get the user with the given ID
     * @param $userID
     * @return null|Supervisor|Tutor
     */
    function get($userID) {
        $userID = $this->clean($userID);
        $query = 'SELECT User.userID, PIN, name, role, email, phone, bio, picture FROM '.self::TABLE_USER.' LEFT JOIN '.self::TABLE_TUTOR.' on User.userID = Tutor.userID WHERE User.userID = '.$userID;
        $record = $this->getDB()->get($query);
        return $this->convertRecord($record);
    }

    function getByHour($day,$hour) {
        $IDs = self::$scheduleManager->getUserIDs($day,$hour);
        $out = array();
        foreach($IDs as $ID)
            $out[] = $this->get($ID);
        return $out;
    }

    /**
     * Get tutors
     */
    function getTutors() {
        $query = 'SELECT * FROM '.self::TABLE_USER.' NATURAL JOIN '.self::TABLE_TUTOR;
        $records = $this->getDB()->getArray($query);
        $out = array();
        foreach($records as $r) {
            $user = $this->convertRecord($r);
            if ($user != null)
                $out[] = $user;
        }
        return $out;
    }

    /**
     * Get supervisors
     */
    function getSupervisors() {
        $query = 'SELECT * FROM '.self::TABLE_USER;
        $records = $this->getDB()->getArray($query);
        $out = array();
        foreach($records as $r) {
            $user = $this->convertRecord($r);
            if ($user != null)
                $out[] = $user;
        }
        return $out;
    }

    /**
     * Get an array with all the users
     * @return array
     */
    function getAll() {
        return array_merge($this->getTutors(),$this->getSupervisors());
    }

    /**
     * Check if a user exists
     * @param $userID
     * @return true|false
     */
    function userExists($userID) {
        return $this->get($userID) != null;
    }

    /**
     * Save the user, both in the DB and in the session
     * @param User $user
     */
    function save(User $user) {
        $this->saveDatabase($user);
        $this->saveSession($user);
    }

    /**
     * If the login is correct the user is returned, null otherwise
     * @param $ID
     * @param $PIN
     * @return null|Supervisor|Tutor
     */
    function login($ID,$PIN) {

        // Clean variables
        $ID = $this->getDB()->cleanString($ID);
        $PIN = $this->getDB()->cleanString($PIN);

        // Obtain user
        $user = $this->get($ID);

        // If the user does not exist
        if ($user == null)
            return null;
        // Check if the password is the same
        if ($user->getPIN() == Security::obtainHash($PIN, $user->getID())) {
            $this->saveSession($user);
            return $user;
        }
        return null;
    }

    /**
     * Close the session
     */
    function logout() {
        $this->closeSession();
    }

    /**
     * Load and return the user stored in the session, null otherwise
     * @return mixed|null
     */
    function loadSession() {
        if (!isset($_SESSION['USER']))
            return null;
        $user = unserialize($_SESSION['USER']);
        if ($user == null  ||  ! $user instanceof User)
            return null;
        return $user;
    }

    /**
     * Save the user in the session
     * @param User $user
     */
    function saveSession(User $user) {
        // Check the session is active
        if(session_id() == '') {
            session_start();
        }

        // Save the user in the session
        $_SESSION['USER'] = serialize($user);
    }

    /**
     * Destroy the user stored in the session
     */
    function closeSession() {
        $_SESSION['USER'] = null;
    }

    function register(Tutor $tutor) {
        if (!$this->userExists($tutor->getID())) {
            $this->saveDatabase($tutor);
        }
    }

    /**
     * Save the user in the db
     * @param User $user
     */
    function saveDatabase(User $user) {

        $save = array();
        $userExists = $this->userExists($user->getID());

        // Common fields
        $save['PIN'] = $user->getPIN();
        $save['name'] = $user->getName();

        // Update if it exists
        if ($userExists) {
            $primary = array('userID' => $user->getID());
            $this->getDB()->update(self::TABLE_USER, $save, $primary);
        }
        // If not,insert it
        else {
            $save['userID'] = $user->getID();
            $save['role'] = $user instanceof Supervisor? 'supervisor' : 'tutor';
            $this->getDB()->insert(self::TABLE_USER,$save);
        }

        // Fields for tutor
        if ($user instanceof Tutor) {
            // Save schedule
            self::$scheduleManager->save($user->getSchedule(), $user->getID());

            // Save timesheet TODO
            $save = array();
            $save['email'] = $user->getEmail();
            $save['phone'] = $user->getPhone();
            $save['bio'] = $user->getBio();
            $save['picture'] = $user->getPicture();

            // Update if it exists
            if ($userExists) {
                $primary = array('userID' => $user->getID());
                $this->getDB()->update(self::TABLE_TUTOR, $save, $primary);
            }
            // If not,insert it
            else {
                $save['userID'] = $user->getID();
                $this->getDB()->insert(self::TABLE_TUTOR,$save);
            }

        }

    }

    /**
     * Removes all traces of a tutor from the database/system
     * Use with caution!
     *
     * @param int $ID The ID of the tutor to destroy
     */
    function destroyTutor($ID) {
        if ($this -> userExists($ID)) {
            $db = $this -> getDB();
            
            $queryTutor = "DELETE FROM ". self::TABLE_TUTOR ." WHERE userID = ". $ID;
            $queryUser = "DELETE FROM ". self::TABLE_USER ." WHERE userID = ". $ID;

            $db -> query($queryTutor);
            $db -> query($queryUser);

            $timesheetManager = new TimesheetManager();
            $timesheetManager -> removeTimesheets($ID);

            $reportManager = new ReportManager();
            $reportManager -> removeReports($ID);

            $scheduleManager = self::$scheduleManager;
            $scheduleManager -> removeSchedule($ID);

        }
    }
}