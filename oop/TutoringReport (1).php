<?php
require_once(__DIR__.'/Tutor.php');

/**
 * Class TutoringReport
 * A TutoringReport instance should be created each time a tutor helps/assists a fellow student throughout the course of
 * his/her shift tutoring. They need to provide the name of student that they helped as well as a summary of how the
 * student was helped or perhaps some of the students trouble areas or concepts that they are having trouble with.
 */
class TutoringReport {

    /**
     * @var Tutor The tutor who filled out this report
     */
    private $tutor;

    /**
     * @var int The date the report was filled out stored as a unix timestamp
     */
    private $date;

    /**
     * @var string The student who was tutored
     */
    private $studentName;

    /**
     * @var string The summary that the tutor provided about the tutoring session
     */
    private $report;

    /**
     * @param Tutor $tutor The tutor who filled out the report
     * @param string $studentName The student's name who was tutored
     * @param string $report The tutors summary of what was covered in this tutoring session
     * @param int $date The date the timesheet was created as a UNIX timestamp
     */
    public function __construct(Tutor $tutor, $studentName, $report, $date = null) {
        $this -> tutor = $tutor;
        $this -> date = (is_null($date) ? time() : $date);
        $this -> studentName = $studentName;
        $this -> report = $report;
    }

    /**
     * @return Tutor The tutor that filled out this report
     */
    public function getTutor() {
        return $this -> tutor;
    }

    /**
     * @return int|null The date that this report was filled out as a UNIX timestamp
     */
    public function getDate() {
        return $this -> date;
    }

    /**
     * @return string The name of the student that this report refers to
     */
    public function getStudentName () {
        return $this -> studentName;
    }

    /**
     * @return string The report text itself
     */
    public function getReport () {
        return $this -> report;
    }
}