<?php require_once('checkLogin.php'); ?>
<?php
if ($USER instanceof Supervisor)
    //include('indexSupervisor.php');
    echo '<script>location="listTutors.php";</script>';
else
    echo '<script>location="updateSchedule.php";</script>';

?>