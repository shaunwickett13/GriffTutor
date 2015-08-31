<?php
require_once('checkLogin.php');
require_once(__DIR__.'/../oop/PayPeriod.php');
require_once(__DIR__.'/../oop/SingleDay.php');
require_once(__DIR__.'/../oop/Timesheet.php');
require_once(__DIR__.'/../oop/manager/TimesheetManager.php');
?>

<?php include('header.php'); ?>

<script src="validators.js"></script>
<script>

    $(function () {

        $(".error-message-side").hide();


        $("#hoursForm").submit( function(e) {
            e.preventDefault();
            var error = false;

            $(".error-message-side").fadeOut();


            var count = 0;

            $('#hoursForm input[type="text"]').each(function () {
                var userInput = $(this).val();
                if (!(userInput == null || userInput == "" || userInput == "0")) {

                    var hours = $(this).val();

                    if (!validateHoursList(hours)) {
                        $("#error" + count).fadeIn();
                        error = true;
                    }
                }
                count++;
            });

            if (error == true) {
                showErrorMessage("Please format your hours worked according the specifications and try again.");
            }
            else {
                var hoursArray = [];
                var startDateText = $("#startDate").text();

                $('#hoursForm input[type="text"]').each(function () {
                    var userInput = $(this).val();
                    if (userInput == '' | userInput == '0' | userInput == null) {
                        hoursArray.push("0");
                    }
                    else {
                        hoursArray.push(userInput);
                    }
                });

                var stringHoursArray = JSON.stringify(hoursArray);

                var submitForm = $('#submitForm');
                $('#startDateSubmit').val(startDateText);
                $('#hoursSubmit').val(stringHoursArray);
                submitForm.submit();

                showMessage("Your timesheet has been created.");
		$('#hoursForm input[type="text"]').each( function () {
		    $(this).val('');
		});
            }
        });



        $("#backButton").click( function () {
            var startDateText = $("#startDate").text();

            $.post("../ajax/ajax_previousPayPeriod.php", {
                startDate: startDateText
            }).done( function(data) {
                dateArray = JSON.parse(data);
                $(".date").each( function (index) {
                    $(this).html(dateArray[index]);
                });
            });
        });

        $("#forwardButton").click( function () {
            var startDateText = $("#startDate").text();

            $.post("../ajax/ajax_nextPayPeriod.php", {
                startDate: startDateText
            }).done( function(data) {
                dateArray = JSON.parse(data);
                $(".date").each( function (index) {
                    $(this).html(dateArray[index]);
                });
            });
        });


    });

</script>

<form id="submitForm" method="post" target="_blank" action="generateTimesheet.php">
    <input type="hidden" id="startDateSubmit" name="startDate"/>
    <input type="hidden" id="hoursSubmit" name="hours"/>
</form>

<h2 id = "maintitle">Create a Timesheet</h2>

<div id="directions">
    <h3>Enter the hours worked for this pay period below formatted according to these specifications:</h3>
    <ul>
        <li>The hours worked for the day should be entered in the format "hmm-hhmm" or "hhmm-hhmm, hhmm-hhmm".</li>
        <li>Minutes can only be entered in 15 minute increments e.g. only 00, 15, 30, and 45 are valid minutes values</li>
        <li>The hours must be in 24 hour format. e.g 1:30pm needs to be entered as 1330.</li>
        <li>List the hours in chronological order. Do not list afternoon hours before morning hours.</li>
        <li>To convert 12 hour time to 24 hour time, add 12 to the hours when the time is PM.</li>
    </ul>
</div>

<div id="buttonArea">
    <span id="buttonLabel">Change Pay Period</span>
    <br/>
    <button class="button" id="backButton">Previous</button>
    <button class="button" id="forwardButton">Next</button>
</div>

<form method="post"  id="hoursForm">
    <input type="hidden" name="action" value="generatePDF">

    <table>
        <tr>
            <th>Date</th>
            <th>Hours Worked</th>
        </tr>
        <?php

            $firstPassInLoop = true;
            $payperiod = new PayPeriod();
            $datesInPeriod = $payperiod -> getDatesInPayPeriod();
            foreach ($datesInPeriod as $index => $theDate) {

                $idString = $firstPassInLoop ? ' id="startDate"' : '';

                echo "<tr>";
                echo '<td class="date"' . $idString . '>' . date("l n/j/y", $theDate) . '</td>';
                echo '<td><input type="text" class="hours" name ="hours' . $index . '"></td>';
                echo '<td><span class="error-message-side" id="error' . $index .'">Please format hours correctly</span></td>';
                echo "</tr>";

                $firstPassInLoop = false;
            }

        ?>

        <tr><td colspan="2"><input type = "submit" value="Create Timesheet"></td></tr>
    </table>
</form>

<?php include('footer.php'); ?>
