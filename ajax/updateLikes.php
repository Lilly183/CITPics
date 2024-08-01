<?
    // Include config.php for connecting to the database:

    require_once("../includes/config.php");

    // Retrieve the current session so that we can get the value of session 
    // variable 'userUploadID'

    session_start();

    /*
    ————————
    Cookies:
    ————————

    Before updating the number of likes for a picture, we must first determine 
    if this action is permissible based on whether or not the current user has 
    liked it before. This involves two pieces of information: the id of the 
    current user and the picture's id. As such, both will be used to create 
    cookies.
    
    If there exists a cookie with a name composed of the current user's uploadID 
    and the id of the image he/she is trying to like, the operation is forbidden; 
    this is because such a cookie – as seen below – is only created after the 
    button has already been clicked and a successful update to the database has 
    been made. 

    We ONLY proceed to update a picture's likes in the database if the cookie 
    is NOT set. Once updated, we create it to prevent future attempts at liking 
    the picture again.
    */
        
    $userID = md5($_SESSION['userUploadID']);
    $cookieString = $userID . $_POST['p_ID'];

    if (!isset($_COOKIE[$cookieString]))
    {
        /*
        As was the case with adding comments, the AJAX method sends data (in the form 
        of JSON) via POST. This JSON contains the id of the picture whose likes we need 
        to update. We got this value from using jQuery to get the id of the image card. 
        Hence, we can now use $_POST and p_ID to target the correct image in the database.
        */

        $picID = htmlspecialchars(trim($_POST['p_ID']));

        /*
        For this query, we're not adding new records nor checking against values. Instead, 
        we are updating an existing value in the 'pic' table. SET the value of p_Likes to 
        p_Likes + 1 WHERE p_ID is equal to the p_ID passed to us via AJAX (As before, use 
        bind_param() to substitute values for '?')
        */

        $likeQuery = 
        "UPDATE
        pic

        SET
        p_Likes = p_Likes + 1

        WHERE
        p_ID = ?";

        $stmt = $db->prepare($likeQuery);
        $stmt->bind_param("i", $picID);
        $stmt->execute();
        $stmt->store_result();

        // Again, only one record should be updated per query. Prepare a message to be returned 
        // to our AJAX which describes whether or not the operation was successful (Also, create 
        // the cookie if it was):

        if ($stmt->affected_rows == 1)
        {
            setcookie($cookieString, true);
            $message = array("message"=>"Success!");
        }

        else
        {
            $message = array("message"=>"Failure!");
        }
    }

    // If the cookie has already been set, the current user has already liked this 
    // picture. Assign the message to be returned to AJAX with a value indicating 
    // that the attempted operation was forbidden.

    else
    {
        $message = array("message"=>"Forbidden!");
    }

    // Return our message as JSON:

    echo json_encode($message);
?>