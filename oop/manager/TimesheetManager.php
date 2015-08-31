<?php
set_include_path(get_include_path() . PATH_SEPARATOR . "/../dompdf");

require_once (__DIR__.'/../dompdf/dompdf_config.inc.php');
require_once (__DIR__.'/Manager.php');
require_once (__DIR__.'/../Timesheet.php');

class TimesheetManager extends Manager {

    /**
     * The table the timesheets are stored in
     */
    const TABLE_TIMESHEET = "Timesheet";

    /**
     * @var string The directory the timesheets are stored in on the server
     */
    static private $SAVE_DIRECTORY = null;

    /**
     * @var string The URL path needed to link to, to access the timesheets via a web browser
     */
    static private $WEB_SAVE_DIRECTORY = null;

    /**
     * @var DOMPDF Used for writing HTML to a PDF file
     */
    private $dompdf;

    /**
     * Constructor
     */
    public function __construct () {
        parent::__construct();
        self::setDirectories();
        $this -> dompdf = new DOMPDF();
    }

    /**
     * Sets both the direcories used by this class
     */
    static private function setDirectories() {
        if (is_null(self::$SAVE_DIRECTORY)) {
            self::$SAVE_DIRECTORY = __DIR__ . '/../../timesheets/';
        }
        if (is_null(self::$WEB_SAVE_DIRECTORY)) {
            self::$WEB_SAVE_DIRECTORY = "/~grifftutor/timesheets/";
        }
    }

    /**
     * Takes a record from a DB query and converts it into an array.
     * Each entry in the array represents one timesheet where each entry is also an array
     * with:
     * index 0: Date created
     * index 1: Tutor ID of who the timesheet belongs to
     * index 2: The filename of the timesheet returned without the directory or .pdf extension added.
     * @param Array Holds all the results from a DB query representing info on the timesheets,
     * @return Array | null An Array of Arrays containing information about the timesheets.
     */
    public function convertRecord (Array $record) {
        if (sizeof($record) == 0) {
            return null;
        }

        $timesheetDataList = array();

        foreach ($record as $timesheet) {
            $timesheetData = array();

            $date = $timesheet['dateCompleted'];
            $userID = $timesheet['userID'];
            $filename = $timesheet['filename'];

            $timesheetData[] = $date;
            $timesheetData[] = $userID;
            $timesheetData[] = $filename;

            $timesheetDataList[] = $timesheetData;
        }

        return $timesheetDataList;
    }

    /**
     * @param int $ID The student ID to get all the timesheets associated with
     * @return Array|null All the data for the timesheets associated with the user ID that was passed in
     */
    public function get ($ID) {
        $ID = $this->clean($ID);
        $query = 'SELECT dateCompleted, userID, filename FROM '.self::TABLE_TIMESHEET.' WHERE userID = '.$ID;
        $timesheetList = $this->getDB()->getArray($query);
        return self::convertRecord($timesheetList);
    }

    /**
     * @return Array|null All the data associated with all the timesheets saved on the server
     */
    public function getAll () {
        $query = 'SELECT dateCompleted, userID, filename FROM '.self::TABLE_TIMESHEET;
        $timesheetList = $this->getDB()->getArray($query);
        return self::convertRecord($timesheetList);
    }

    /**
     * Saves a timesheet in both the database, as well as locally for access via browser
     * @param Timesheet $timesheet The timesheet to save
     */
    public function save (Timesheet $timesheet) {
        self::saveLocally($timesheet);
        self::saveDatabase($timesheet);
    }

    /**
     * Saves a timesheet in a directory on the server
     * @param Timesheet $timesheet The timesheet to save locally
     */
    private function saveLocally (Timesheet $timesheet) {
        $html = $timesheet -> getHTML();
        $this -> dompdf -> load_html($html);
        $this -> dompdf->render();
        $output = $this -> dompdf->output();


        // This will currently save to the server timesheets created by a single user on the same day
        // Only one copy is stored in the database so only the most recent copy will be accessed using
        // the get() and getAll() methods but there is a potential for unaccessable files to exist and
        // pile up on the local server storage directory.
        $dir = self::$SAVE_DIRECTORY . $timesheet -> getFilename() . '.pdf';
        file_put_contents($dir, $output);
    }

    /**
     * Saves information about a timesheet in the database table
     * @param Timesheet $timesheet The timesheet to save in the database
     */
    private function saveDatabase (Timesheet $timesheet) {
        $db = $this -> getDB();

        $userID = $timesheet -> getTutorID();
        $dateCreated = $timesheet -> getDateCreated();
        $filename = $timesheet -> getFilename();

        // Deletes duplicate timesheets generated the same day by the same user
        $query = "DELETE FROM " .self::TABLE_TIMESHEET." WHERE userID = '".$userID."' AND dateCompleted = '". $dateCreated ."'";
        $db -> query($query);
  
        $save = array();
        $save['userID'] = $userID;
        $save['dateCompleted'] = $dateCreated;
        $save['filename'] = $filename;
        $db -> insert(self::TABLE_TIMESHEET, $save);
        
    }

    public function removeTimesheets($ID) {
        $query = "DELETE FROM ". self::TABLE_TIMESHEET ." WHERE userID = ". $ID;
        $this -> getDB() -> query($query);
    }

    /**
     * Gets the file path to the directory that the timesheets are saved in on the server.
     * @return string The path used to save the timesheets on the server
     */
    public function getSaveDirectory () {
        return self::$SAVE_DIRECTORY;
    }

    /**
     * Gets the path needed in links to access timesheets in the broswer
     * @return string The path used to access the timesheets in the browser
     */
    public function getWebDirectory () {
        return self::$WEB_SAVE_DIRECTORY;
    }

}