<?php include 'header.php'; ?>

<?php

/*
<form method="post">
    <input type="hidden" name="action" value="registerTutor"/>
    <input type="text" name="ID" placeholder="ID" size="10"/>
    <input type="password" name="PIN" placeholder="PIN" size="5" maxlength="4"/>
    <input type="email" name="email" placeholder="email"/>
    <input type="tel" name="phone" placeholder="phone" maxlength="10" size="11"/>
    <input type="submit" value="Register"/>
</form>*/

    

?>


<style>
    td {
        border:1px solid black;
    }
</style>

<!-- LIST OF TUTORS -->



<!-- ADD TUTOR -->
<?php echo $USER->getPIN(); ?>
<h2>View Tutor's Timesheets</h2>
<a href="viewTimesheets.php">View timesheets</a>

<h2>View Submitted Tutoring Session Reports</h2>
<a href="viewTutoringReports.php">View Reports</a>


<?php include 'footer.php'; ?>