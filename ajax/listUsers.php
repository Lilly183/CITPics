<?
    require_once("../includes/config.php");

    // Query the 'user' table to get every user in the database:

    $adminQuery = 
    "SELECT 
    u_ID, u_FName, u_LName, u_Email
    
    FROM
    user";

    // Prepare $stmt for adminQuery, then execute and store the results:

    $stmt = $db->prepare($adminQuery);
    $stmt->execute();
    $stmt->store_result();

    // Bind the results to local variables $uID, $uFirstName, $uLastName, and $uEmail.
    
    $stmt->bind_result($uID, $uFirstName, $uLastName, $uEmail);

    // For each user, create an array with indices "id", "firstName," "lastName", and "email". 
    // Populate their values using the local variables we declared earlier. Whilst still fetching 
    // the results, append each array to the $users[] array.
    
    while ($stmt->fetch())
    {
        $users[] = array("id"=>$uID, "firstName"=>$uFirstName, "lastName"=>$uLastName, "email"=>$uEmail);
    }

    // Return the $users array as JSON:

    echo json_encode($users);
?>