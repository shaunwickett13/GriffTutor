<?php
set_include_path(get_include_path() . PATH_SEPARATOR . "dompdf");

// TODO must configure DOMPDF directory it seems
require_once(__DIR__."/dompdf/dompdf_config.inc.php");
require_once(__DIR__.'/TimesheetHours.php');
require_once(__DIR__.'/PayPeriod.php');
require_once(__DIR__.'/Tutor.php');
require_once(__DIR__.'/SingleDay.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Class Timesheet
 * This represents a single timesheet that a tutor would fill out and hand into the payroll office.
 */
class Timesheet {

    /**
     * @var int UNIX timestamp representing the date this timesheet was filled out.
     */
    private $dateFilledOut;

    /**
     * @var string The name of the tutor
     */
    private $tutorName;

    /**
     * @var int The ID number of the tutor
     */
    private $tutorID;

    /**
     * @var string The phone number of the tutor
     */
    private $tutorPhone;

    /**
     * @var array An array of instances of the SingleDay that hold information on when the tutor worked each day of
     * the pay period
     */
    private $timeWorkedInPeriod;

    /**
     * @var bool|string The first day of the pay period
     */
    private $periodStart;

    /**
     * @var bool|string The last day of the pay period
     */
    private $periodEnd;

    /**
     * @var float The total number of hours the tutor worked for this pay period
     */
    private $totalHoursWorked;

    /**
     * @var string The filename that was created for this timesheet with no extension at the end
     */
    private $filename;

    /**
     * @var string The HTML used to display this timesheet
     */
    private $HTML;

    /**
     * @var DOMPDF The DOMPDF instance used for PDF generation
     */
    private $PDFgenerator;



    /**
     * @param int $filledOutDate The date the timesheet was filled out as a int UNIX timestamp, if no value is passed in
     * today's date is set as the date
     * @param Tutor $tutor The tutor that is associated with this timesheet
     * @param PayPeriod $payPeriod The payperiod that relates to this timesheet.
     * @param array $timesWorked The hours the tutor worked for this time period
     */
    public function __construct (Tutor $tutor, PayPeriod $payPeriod, Array $timesWorked, $filledOutDate = null) {
        

        // Auto set to today's date. Can be changed manually by method call
        $this -> dateFilledOut = $filledOutDate == null ? date("Y-m-d", time()) : date("Y-m-d", $filledOutDate);

        $this -> tutorName = $tutor -> getName();
        $this -> tutorID = $tutor -> getID();
        $this -> tutorPhone = $tutor -> getPhone();

        $timesheetHours = new TimesheetHours($payPeriod, $timesWorked);

        //
        $this -> timeWorkedInPeriod = $timesheetHours -> getPayPeriodSchedule();
        $this -> totalHoursWorked = $timesheetHours -> getTotalHoursWorked();


        $this -> periodStart = date("l n/j/y", $this -> timeWorkedInPeriod[0] -> getDate());
        $this -> periodEnd = date("l n/j/y", $this -> timeWorkedInPeriod[count($timesWorked) - 1] -> getDate());

        $this -> filename = "Timesheet_" . $this -> tutorID . "_" . date("mdy") . "_" . date("Gis");
        $this -> HTML = self::setTimesheetHTML();
        $this -> PDFgenerator = new DOMPDF();
    }


    /**
     * Generates a PDF of the Timesheet allowing the user to save it to their desktop and print it out.
     */
    public function toPDF() {
        $timesheetHTML = $this -> getHTML();

        $dompdf = $this -> PDFgenerator;

        $dompdf -> load_html($timesheetHTML);
        $dompdf -> render();

        $dompdf -> stream($this -> filename . ".pdf");
    }

    /**
     * @return string The generated filename for this Timesheet with no extension at the end. It is just the name.
     */
    public function getFilename () {
        return $this -> filename;
    }

    /**
    * @return string The HTML that is used to create this timesheet. Can be rendered with DOMPDF for output external
    * to this classes toPDF() function.
    */
    public function getHTML () {
        return $this -> HTML;
    }

    public function getDateCreated () {
        return $this -> dateFilledOut;
    }

    public function getTutorID () {
        return $this -> tutorID;
    }

    /**
     * Helper function that puts all the HTML that makes up the main table into a single string for ease of insertion
     * into the HEREDOC
     *
     * @return string The HTML that makes up the main table in the HTML for this timesheet.
     */
    private function generateTableData() {
        $HTML_TABLE_DATA = "";

        $allHoursMap = $this -> timeWorkedInPeriod;

        foreach ($allHoursMap as $singleDay) {

            $HTML_TABLE_DATA .= "<tr>";
            $HTML_TABLE_DATA .= "<td>" . date("n/j/y D", $singleDay -> getDate()) . "</td>";
            if ($singleDay -> didWork()) {
                $HTML_TABLE_DATA .= "<td>" . $singleDay -> getStartTime() . "</td>";
                $HTML_TABLE_DATA .= "<td>" . $singleDay -> getEndTime() . "</td>";
                $HTML_TABLE_DATA .= "<td>" . $singleDay -> getHoursWorked() . "</td>";
                $HTML_TABLE_DATA .= "<td>" . ($singleDay -> wasLunchTaken() ? "X" : "") . "</td>";
                $HTML_TABLE_DATA .= "<td>" . $singleDay -> getNumHoursWorked() . "</td>";

            } else {
                for ($i = 0; $i < 5; $i++) {
                    $HTML_TABLE_DATA .= "<td></td>";
                }
            }
            $HTML_TABLE_DATA .= "</tr>";
        }

        return $HTML_TABLE_DATA;
    }

    /**
     * @return string The HTML code stored as a string associated with this instance of Timesheet
     */
    private function setTimesheetHTML() {

        $name = $this->tutorName;
        $phone = $this->tutorPhone;
        $ID = $this->tutorID;
        $totalHours = $this->totalHoursWorked;
        $pStart = $this->periodStart;
        $pEnd = $this->periodEnd;
        $date = date("n/j/y", strtotime($this -> dateFilledOut));
        $HTML_TABLE_DATA = $this -> generateTableData();

        return <<<HTML
        <head>
            <style>
                @page {
                    margin:0px;
                }

                body {
                    width: 8.5in;
                    height: 11in;
                    margin: 0.4in;
                }

                span {
                    font-size: 12pt;
                    font-weight: 800;
                }

                table {
                    border-collapse: collapse; 
                    border: 1px solid black;
		    width: 7.7in;  
                }
                table th {
                    padding: .025in .1in;
                    border: 2px solid black;
                }

                table td {
                    text-align: center;
                    padding: .025in .1in .025in .1in;
                    border: 1px solid black;
                }

                div {
                    text-align: center;
                    font-weight: 800;
                }

                #logo {
                    width:1.2in;
                    height: 1.2in;
                }

                #title {
                    font-size: 20pt;
                    position: relative;
                    top: -0.5in;
                    margin-left: 1.25in;
                }

                #studentname, #ccid {
                    margin-left: 1.25in;
                }

                #totalhours1 {
                    margin-left: 5.5in;
                }

                #recordearnings {
                    margin-left: 1.75in;
                }

                #startingendingdate {
                    margin-left: 2.2in;
                }

                #coldate, #coltotal {
                    width:.9in;   
                }

                #colarrive, #coldepart, #collunch {
                    width:.65in;   
                }

                #colhours {
                    width:2.0255in;   
                }

                #lunchinfo, #total, #totalhours2 {
                    font-weight: 800;   
                }

                #lunchinfo {
                    font-size: 8pt;   
                }

                #total {
                    text-align: right;
                }
            </style> 
        </head>
        <body>
            <img src="../img/canisiuslogo.jpg" alt="Canisius Logo" id="logo">
            <span id="title">Student Employee Timesheet</span> 
            <br>
            <span id="studentname">Student Name: <u>&nbsp;&nbsp; $name &nbsp;&nbsp;</u></span>
            <span id="ccid">Canisius College ID#: <u>&nbsp;&nbsp; $ID &nbsp;&nbsp;</u></span>
            <br>
            <br>
            <span id="totalhours1">TOTAL HOURS: <u>&nbsp;&nbsp; $totalHours &nbsp;&nbsp;</u></span>
            <br>
            <br>
            <span id="recordearnings">Record earnings for <u>two weeks</u> for the pay period beginning</span>
            <br>
            <span id="startingendingdate">$pStart and ending $pEnd</span>
            <br>
            <br>
            <table>
                <tr>
                    <th id="coldate">DATE / DAY</th>
                    <th id="colarrive">Time of Arrival</th>
                    <th id="coldepart">Time of Departure</th>
                    <th id="colhours">Hours Worked</th>
                    <th id="collunch">Lunch Taken</th>
                    <th id="coltotal">Total Hours</th>
                </tr>
                $HTML_TABLE_DATA
                <tr>
                    <td id="lunchinfo">*Students working more than 6 hrs must take a 1/2 hr lunch</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td id="total">Total Hours:</td>
                    <td id="totalhours2">$totalHours</td>
                </tr>
            </table>
            <br>
            <span>I hereby certify that the above is a true statement of the hours worked by the student named and that<br> he/she has performed the assigned job in a satisfactory manner:</span>
            <br>
            <br>
            <span>Department: _____________________________________ Ext. #: ___________ Index #: _________________</span>
            <br>
            <br>
            <span>Supervisor's Signature: _____________________________________ Date: ____________________________</span>
            <br>
            <br>
            <div><u>SUBMIT TIMESHEET TO:</u> Denise T. Rogers, Payroll Office (OM 009)<br>
            NO LATER THAN FRIDAY, 10:00 a.m.<br>
            I certify that the above is a true statement of the hours I worked during the specified time period.
            </div>
            <br>
            <br>
            <span>Student's Signature: _______________________________________ Phone:<u>&nbsp;&nbsp; $phone &nbsp;&nbsp;</u> Date: <u>&nbsp;&nbsp; $date &nbsp;&nbsp;</u></span>
        </body>
HTML;
    }
}
