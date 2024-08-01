<?
    // This page is summoned upon clicking the logout button. First, retrieve 
    // the current session and destroy it, then redirect the user to the home 
    // page.

    session_start();
    session_destroy();

    header("Location: index.php");
?>