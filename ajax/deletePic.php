<?
    require_once("../includes/config.php");

    $picID = htmlspecialchars(trim($_POST['p_ID']));

    // Delete the record from 'pic' table whose p_ID matches the value
    // of $picID, which is substituted in for '?' via bind_param(). 

    $deleteQuery = 
    "DELETE FROM
    pic

    WHERE
    p_ID = ?";

    $stmt = $db->prepare($deleteQuery);
    $stmt->bind_param("i", $picID);

    // Execute the statement and store the result:

    $stmt->execute();
    $stmt->store_result();

    // Only one picture can be deleted at a time. If $stmt's affected_rows() == 1,
    // the operation was successful. Otherwise, something went wrong. In either 
    // case, declare an array so we can report the outcome back to jQuery.

    if ($stmt->affected_rows == 1)
    {
        $message = array("message"=>"Success!");
    }

    else
    {
        $message = array("message"=>"Failure!");
    }

    // Return our message as JSON:

    echo json_encode($message);
?>