<?
    //———————————————————————————————
    // Database Connection Constants:
    //———————————————————————————————
    
    
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'root');
    define('DB_DB', 'citpics');

    // Declare 'db' variable and assign using the value returned from the function 
    // mysqli_connect(), whose arguments are the database connection constants that 
    // we declared above. In other words, we're connecting to the "citpics" database:

    @$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DB);

    // Check for connection errors:

    if (mysqli_connect_errno())
    {
        echo "ERROR: Unable to connect to database";
        echo "Debugging errno: " . mysqli_connect_errorno() . PHP_EOL;
        exit();
    }

    // If the home page ('index.php') is the current page AND a user is logged in, 
    // use PHP's header() function to redirect the user to the photo viewing page 
    // ('gallery.php'). This must be done BEFORE any HTML or text is loaded.

    if (stripos($_SERVER['REQUEST_URI'], 'index.php') && isset($_SESSION['userLoggedIn']))
    {
        header("Location: ../gallery.php");
        die();
    }
?>