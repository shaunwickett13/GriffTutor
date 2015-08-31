<?php date_default_timezone_set('America/New_York');

    $action = isset($_POST['action'])? $_POST['action'] : '';

    if ($action == 'login') {
        require_once __DIR__.'/../oop/manager/UserManager.php';
        $ID = $_POST['userID'];
        $PIN = $_POST['PIN'];
        $userManager = new UserManager();
        $user = $userManager->login($ID,$PIN);

        // If we have correctly logged in
        if ($user != null) {
            if ($user instanceof Supervisor)
                echo '<script>location="listTutors.php";</script>';
            else
                echo '<script>location="updateSchedule.php";</script>';
            exit;
        }
        // If not, show error
        else {
            echo '<script>alert("The user or the password are incorrect");</script>';
        }
    }
?>
<?php include 'header.php'; ?>

<script>
    
    function isUserValid() {
        if (!/^[0-9]+$/.test($('#userID').val())) {
            showError('error-userID');
            return false;
        }
        hideError('error-userID');
        return true;
    }
    
    function isPINValid() {
        // Check password
         if (!/^[0-9]{4}$/.test($('#PIN').val())) {
             showError('error-PIN');
             return false;
        }
        hideError('error-PIN');
        return true;
    }
    
    $(function() {
        
        // Check form     
        $('#loginForm').submit(function(evt) {
            
            // Check if the fields are valid
            var error = false;
            if (!isUserValid())
                error = true;
            if (!isPINValid())
                error = true;
            
            // If there is a error, no submit
            if (error)
                evt.preventDefault();
            
        });
        
        // Check inputs on typing
        $('#userID').keyup(function(evt) {
            isUserValid();
        });
        $('#PIN').keyup(function(evt) {
            isPINValid();
        });                           
                            
    });
</script>


<h2>Admin Login</h2>

<form id="loginForm" method="post">
    <input type="hidden" name="action" value="login"/>
    <span id="error-userID" class="error-message">Enter a valid user ID</span>
    <input type="text" id="userID" name="userID" placeholder="User ID" />
    <span id="error-PIN" class="error-message">Enter a valid 4 digit PIN</span>
    <input type="password" id="PIN" name="PIN" placeholder="PIN" maxlength="4" size="5" />
    <input type="submit" value="Connect"/>
</form>

<?php include 'footer.php'; ?>

            
            
            
        