<?php require_once( 'checkLogin.php'); ?>
<?php
    require_once(__DIR__.'/../oop/Schedule.php');
    require_once(__DIR__.'/../oop/TutoringReport.php');

    $action = isset($_POST['action'])? $_POST['action'] : '';

if ($action == 'submitReport') {
        $studentName = $_POST['student'];
        $summary = $_POST['summary'];

        $tutoringReport = new TutoringReport($USER, $studentName, $summary);

        // Maybe a new class such as ReportManager will save this to the database...
        // $reportManager = new $ReportManager();
        // $reportManager->save($tutoringReport);

        echo '<script>alert("The tutoring session report has been submitted successfully!")</script>';
    }

?>


<!--
Need to create DB table to hold these tutoring logs
With fileds:
User ID (ID of tutor) *primary key
Date
Name of student tutored
Summary of tutoring session

Do some form validation to make sure that everything is filled out and send to DB

Will do later.
-->
<?php include 'header.php'; ?>

<h2>Submit Tutoring Session Report</h2>
    <p>Give the name of the student you tutored and a brief description of in what ways you helped the student.</p>
    <form id="reportForm" method="post">
        <input type="hidden" name="action" value="submitReport">
        <input id="studentReport" type="text" name="student" placeholder="Name of student">
        <textarea id="summaryReport" name="summary" placeholder="Summary of tutoring session"></textarea>
        <input id="submitReport" type="submit" value="Submit">
    </form>


<h2>Manage Timesheets</h2>
<a href="createTimesheet.php">Create new timesheet</a>
<a href="viewTimesheets.php">View old timesheets</a>

<?php include 'footer.php'; ?>