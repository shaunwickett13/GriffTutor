<?php
require_once('checkLogin.php');
require_once(__DIR__ . '/../oop/TutoringReport.php');
require_once(__DIR__ . '/../oop/manager/ReportManager.php');
?>

<?php include('header.php'); ?>

<script src="validators.js"></script>
<script>
    $( function () {
        $("#reportForm").submit( function(e) {
	 		
            
            var studentName = $("#studentNameText").val();
	    var reportText = $("#reportText").val();

            if (!validateName(studentName)) {
                showErrorMessage("Please type a valid name.");
               	e.preventDefault();
            }
	    else if (reportText == '' || reportText == null) {
		showErrorMessage("The report field can not be blank.");
		e.preventDefault();
	    }
	   	else {
	    		var reportText = $("#reportText").val();

				$.post("../ajax/ajax_createReport.php", {
					name: studentName,
					report: reportText
				}).done( function(data) {
					if (data == 'success') {
						$("#studentNameText").val('');
						$("#reportText").val('');
						showMessage("The report was saved successfully!");
					}
				});
				e.preventDefault();
	    	}
        });
    });

</script>

<h2>Create Tutoring Report</h2>
<p>
	Please fill out the students name a brief description of the tutoring session and then submit the report.
</p>
</br>

<form method="post" id="reportForm">
	<input type="hidden" name="action" value="submitReport">
	<table>
		<tr>
			<td><span id="studentName">Student Name: </span></td>
			<td><input type="text" name="studentName" id="studentNameText"></td>
		</tr>
		<tr>
			<td>Report: </td>
			<td><textarea id="reportText" name="report" placeholder="Enter the report here"></textarea></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" id="submitButton" value="Submit Report"></td>
		</tr>
	</table>
	
	
</form>

<?php include('footer.php'); ?>
