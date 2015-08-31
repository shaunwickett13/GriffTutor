<?php
require_once('checkLogin.php');
require_once(__DIR__.'/../oop/manager/TimesheetManager.php');
require_once(__DIR__.'/../oop/manager/UserManager.php');
require_once(__DIR__.'/../oop/Tutor.php');

$isSupervisor = $USER instanceof Supervisor;

$action = isset($_POST['action'])? $_POST['action'] : '';

if ($action == "searchName") {

}

?>

<?php include('header.php'); ?>
<script src="validators.js"></script>
<script>

    $(function () {

        $("#buttonSearch").click( function () {
            var userInput = $("#textName").val();
            var noResults = true;

	    if (!validateName(userInput)) {
		showErrorMessage("Please enter a valid name.");
		return;
	    }
            $(".name").each( function () {
                // If the name they are searching for is not the current thing hide it
                var currentName = $(this).text();
                if (currentName.toLowerCase().indexOf(userInput.toLowerCase()) == -1) {
                    $(this).closest("tr").hide();
                }
                else {
                    noResults = false;
                }
            });

            if (noResults) {
                $("#buttonRest").trigger("click");
                showErrorMessage("There were no timesheets found!");
            }
        });

        $("#buttonReset").click( function () {
            $("tr").show();
            $("#textName").val("");
        });
    });

</script>

<h2>View All Timesheets</h2>

<?php
// Only supervisors will see the searching area
if ($isSupervisor) {
    echo '
        <h4>Search Timesheets by Name</h4>
        <form method="post" id="searchForm">
            <table>
                <input type="hidden" name="action" value="searchName">
                <tr>
                    <td><span>Name:</span></td>
                    <td><input type="text" name="name" id="textName"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="button" id="buttonSearch" value="Search">
                        <input type="button" id="buttonReset" value="Reset">
                    </td>
                </tr>
            </table>
        </form>
        ';
}
?>

<?php

// Create the managers
$timesheetManager = new TimesheetManager();
$userManager = new UserManager();


$timesheetListBackwards = array();

if ($isSupervisor) {
    $timesheetListBackwards = $timesheetManager -> getAll();
}
else {
    $timesheetListBackwards = $timesheetManager -> get($USER -> getID());
}



$directory = $timesheetManager -> getSaveDirectory();

if (is_null($timesheetListBackwards)) {
    echo "There are currently no timesheets to display.";
}
else {

    $timesheetList = array_reverse($timesheetListBackwards);

    echo '<table id="viewTimesheetTable">';
    echo '<tr>';
    echo '<th>Date</th>';
    echo ($isSupervisor ? "<th>Tutor</th>" : "");
    echo '<th>Timesheet</th>';
    echo '</tr>';

    foreach ($timesheetList as $timesheetData) {
        $date = $timesheetData[0];
        $userID = $timesheetData[1];
        $filename = $timesheetData[2];

        $tutor = $userManager -> get($userID);
        $tutorName =  $tutor -> getName(); // get the tutor name using the ID
        $pathToTimesheet = $timesheetManager -> getWebDirectory() . $filename . ".pdf";

        echo "<tr>";
        echo '<td class="date">' . $date . '</td>';
        echo $isSupervisor ? ('<td class="name">' . $tutorName . '</td>') : '';
        echo '<td class="timesheetLink"><a href="'. $pathToTimesheet .'" target="_blank">View Timesheet</a></td>';
        echo "</tr>";
    }
    echo '</table>';
}
?>



<?php include('footer.php'); ?>
