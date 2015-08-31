<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Griff Tutor - Administration</title>
    <link rel="stylesheet" href="../style.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    
    <script>
        function showMessage(message) {
            showMessage(message, false);
        }
        
        function showErrorMessage(message) {
            showMessage(message, true);
        }
        
        function showMessage(message, error) {
           var bar = $('#message-bar');
            if (error) {
                bar.addClass('error-color')
            }
            else 
                bar.removeClass('error-color')
            bar.html(message);
            bar.removeClass('slow-opacity-transition');
            bar.css('visibility','visible');
            bar.addClass('visible');
            setTimeout(function() {
                bar.addClass('slow-opacity-transition');
                bar.removeClass('visible');
                setTimeout(function() {
                    bar.css('visibility','hidden');
                },2000);
            },3000);
        }
        
        function showError(errorID) {
        $('#'+errorID).css({
            visibility: 'visible',
            opacity: 1
        });
    }
    
    function hideError(errorID) {
        $('#'+errorID).css('opacity', 0);
        setTimeout(function() {
                $('#'+errorID).css('visibility','hidden');
            },500);
    }
    </script>

</head>

<body>

   <?php if (isset($USER)  &&  $USER != null) {
       echo '
       <div id="admin-header">
            <span>Welcome, <span id="welcome-username">'.$USER->getName().'</span>! -
            <form method="post" id="logoutForm">
                <input type="hidden" name="action" value="logout"/>
                <a href="javascript:void(0)" onclick="document.getElementById(\'logoutForm\').submit()">Logout</a>
            </form>
            </span>
        </div>';
   } ?>
    
    <div id="message-bar"></div>

    <div class="wrapper">

        <header>
            <div class="header-wrapper">
                <img id="logo" src="../img/icon.png" alt="Griff Tutor Logo" />
                <h1><span class="first-letter">G</span>RIFF <span class="first-letter">T</span>UTOR</h1>
            </div>
            <?php
            if (isset($USER)  &&  $USER instanceof Tutor) {
                echo '
                <nav id="subheader">
                   <ul>
                       <li><a href="updateSchedule.php">Update schedule</a></li>
                       <li><a href="modifyProfile.php">Modify profile</a></li>
                       <li><a href="createTimesheet.php">Create new timesheet</a></li>
                       <li><a href="viewTimesheets.php">View old timesheets</a></li>
                       <li><a href="createReport.php">Create tutoring report</a></li>
                   </ul>
                </nav>';
            }
            else if (isset($USER)  &&  $USER instanceof Supervisor) {
               echo '
                <nav id = "subheader">
                   <ul>

                       <li><a href = "listTutors.php" > List of tutors </a></li>
                       <li><a href = "manageTutors.php" > Manage tutors </a></li> 
                       <li><a href = "viewTimesheets.php"> View timesheets </a></li>
                       <li><a href = "viewTutoringReports.php"> View tutoring reports </a></li>

                   </ul>
                </nav>';
            }
            ?>
        </header>
        <section id="content">
