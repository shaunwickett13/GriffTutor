<?php
require_once(__DIR__.'/../Tutor.php');
require_once(__DIR__.'/../TutoringReport.php');
require_once(__DIR__.'/Manager.php');
require_once (__DIR__.'/UserManager.php');

class ReportManager extends Manager {

    /**
     * Columns:
     * 'userID' = Tutor ID (primary key) (type = int(11))
     * 'date' = Date of report (type = date)
     * 'studentName' = Name of student tutored (type = varchar(30))
     * 'report' = Report Summary (type = text)
     */


    const TABLE_REPORT = "Report";

    public function __construct () {
        parent::__construct();
    }

    /**
     * Convert the results of a DB query to an array of TutoringReport's
     * @param Array $record Holds the results of a DB query
     * @return Array | null Holds all the instances of TutoringReport
     */
    function convertRecord(Array $record) {
        if (sizeof($record) == 0) {
            return null;
        }

        $reportArray = array();

        foreach ($record as $r) {

            $userID =  $r['userID'];
            $date = $r['date'];
            $studentName = $r['studentName'];
            $report = $r['report'];

            $userManager = new UserManager();
            // Creates a Tutor object out of the ID
            $tutor = $userManager -> get($userID);

            $report = new TutoringReport($tutor, $studentName, $report, $date);

            $reportArray[] = $report;
        }

        return $reportArray;
    }

    /**
     * Gets all the tutoring reports for the tutor with the specified ID that have been saved
     * @param int $ID The ID number of a tutor
     * @return Array Holds all the instances of TutoringReport that have been filed by the tutor
     */
    function get($ID) {
        $ID = $this->clean($ID);
        $query = 'SELECT userID, date, studentName, report FROM ' . self::TABLE_REPORT . ' WHERE userID = ' . $ID;
        $record = $this -> getDB() -> getArray($query);
        return $this -> convertRecord($record);
    }

    /**
     * Gets all the tutoring reports that have been saved
     * @return Array Holds all the instances of TutoringReport
     */
    function getAll() {
        $query = 'SELECT userID, date, studentName, report FROM ' . self::TABLE_REPORT;
        $record = $this -> getDB() -> getArray($query);
        return $this -> convertRecord($record);
    }

    /**
     * Saves an instance of TutoringReport in the database.
     * @param TutoringReport $report The tutoring report to be saved in the database
     */
    function save(TutoringReport $report) {
        $db = $this -> getDB();
        $userID = $report -> getTutor() -> getID();
        $date = $report ->getDate();
        $studentName = $report -> getStudentName();
        $reportText = $report -> getReport();

        $save = array();
        $save['userID'] = $userID;
        $save['date'] = date("Y-m-d", $date);
        $save['studentName'] = $studentName;
        $save['report'] = $reportText;

        $db -> insert(self::TABLE_REPORT, $save);
        
    }


    /**
     * Removes all the reports assocaited with a specific tutor.
     * @param int $ID A tutor's ID number
     */
    function removeReports($ID) {
        $query = "DELETE FROM ". self::TABLE_REPORT ." WHERE userID = ".$ID;
        $this -> getDB() -> query($query);
    }
}