<?php
require_once('checkLogin.php');
require_once __DIR__.'/../oop/TutoringReport.php';
require_once __DIR__.'/../oop/manager/ReportManager.php';


?>


<?php include('header.php'); ?>

<script src="validators.js"></script>
<script>
    $( function () {

        $("#searchButton").click( function () {
            var searchTutor = $("#textName").val();
            var searchDate = $("#textDate").val();
            var searchStudent = $("#textStudent").val();

	    if (searchDate && !validateDate(searchDate)) {
                showErrorMessage("Your date was not formatted as MM/DD/YYYY.");
                return;
	    }            

	    if (searchTutor && !validateName(searchTutor)) {
                showErrorMessage("The tutor name was not formatted as a name.");
                return;
            }
       
	    if (searchStudent && !validateName(searchStudent)) {
                showErrorMessage("The student name was not formatted as a name.");
                return;
            }

	    if (searchDate) {
                $(".date").each( function () {
                    if ($(this).text().toLowerCase().indexOf(searchDate.toLowerCase()) == -1) {
                        $(this).closest("tr").hide();
                    }
                });
            }

	    if (searchTutor) {
                $(".tutor").each( function () {
                    if ($(this).text().toLowerCase().indexOf(searchTutor.toLowerCase()) == -1){
                        $(this).closest("tr").hide();
                    }
                });
	    }

	    if (searchStudent) {
                $(".student").each( function () {
                    if ($(this).text().toLowerCase().indexOf(searchStudent.toLowerCase()) == -1) {
                        $(this).closest("tr").hide();
                    }
                });
	    }

        });



        $("#resetButton").click( function () {
            $("tr").show();
            $("#textName").val("");
            $("#textDate").val("");
            $("#textStudent").val("");
        });
    });
</script>


<h2>Tutoring Session Reports</h2>

<form method="post" id="searchReports">
    <table>
        <tr>
            <td>
                <span>Date Created: </span>
            </td>
            <td>
                <input type="text" id="textDate" name="textDate" placeholder="MM/DD/YYYY">
            </td>
        </tr>
        <tr>
            <td>
                <span>Tutor Name: </span>
            </td>
            <td>
                <input type="text" id="textName" name="textName">
            </td>
        </tr>
        <tr>
            <td>
                <span>Student Name: </span>
            </td>
            <td>
                <input type="text" id="textStudent" name="textStudent">
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="button" id="searchButton" value="Search">
                <input type="button" id="resetButton" value="Reset">
            </td>
            
        </tr>
    </table>
</form>


<?php
$reportManager = new ReportManager();

$reportListBackwards = $reportManager -> getAll();


if (is_null($reportListBackwards)) {
    echo "There are currently no reports to display!";
}
else {
    $reportList = array_reverse($reportListBackwards);
    echo '<table id="viewTutoringReportTable">';
    echo '<tr>';
    echo '<th class="tableHeader" id="dateHeader">Date</th>';
    echo '<th class="tableHeader" id="tutorHeader">Tutor</th>';
    echo '<th class="tableHeader" id="studentHeader">Student</th>';
    echo '<th class="tableHeader" id="reportHeader">Submitted Report</th>';
    echo '</tr>';

    foreach($reportList as $report) {
        $time = strtotime($report -> getDate());

        $date = date("m/d/Y", $time);
        $tutorName = $report -> getTutor() -> getName();
        $student = $report -> getStudentName();
        $textReport = $report -> getReport();

        echo '<tr>';

        echo '<td class = "date">' . $date . '</td>';
        echo '<td class = "tutor">' . $tutorName . '</td>';
        echo '<td class = "student">' . $student . '</td>';
        echo '<td class = "report">' . $textReport . '</td>';

        echo '</tr>';
    }

    echo '</table>';
}
?>


<?php include('footer.php'); ?>
