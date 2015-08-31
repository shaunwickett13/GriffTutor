<?php date_default_timezone_set('America/New_York');

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once __DIR__.'/../oop/User.php';

/**
 * Restrict the access to supervisor
 * @param User $currentUser
 */
function restrictSupervisor(User $currentUser) {
    if (!$currentUser instanceof Supervisor) {
        echo '<script type="text/javascript">location.href="index.php";</script>';
        exit;
    }
}

// Inicio la sesión
@session_start();

// DEBUGGING
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load user manager
require_once __DIR__.'/../oop/manager/UserManager.php';
$userManager = new UserManager();

// Logout
if (isset($_POST['action']) && $_POST['action']=='logout') {
    $userManager->logout();
    echo '<script>location="login.php"</script>';
    exit();
}

// Load user
$USER = $userManager->loadSession();

// If we are not connected, exit
if ($USER == null) {
    echo '<script type="text/javascript">location="login.php";</script>';
    exit;
}

?>