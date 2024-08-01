<?
  // In order to work with the 'citpics' database when AJAX is used to call 
  // this PHP page, we must first retrieve the definitions stored in config.php:

  require_once("../includes/config.php");

  /*
  When the user tries to post a new comment, AJAX is used to send JSON to 
  this page. The JSON object includes the trimmed version of text from the 
  <input> tag (labelled as "c_Text") and the picture to which the comment 
  belongs (labelled as "p_ID"). Use $_POST to retrieve both of these values 
  and store them as PHP variables. Use this data to add a new comment in 
  the 'comment' table. 
  */

  $commentText = htmlspecialchars($_POST['c_Text']);
  $picID = htmlspecialchars(trim($_POST['p_ID']));

  $commentQuery =
  "INSERT INTO
  comment (c_Text, p_ID)

  VALUES
  (?, ?)";

  $stmt = $db->prepare($commentQuery);
  $stmt->bind_param("si", $commentText, $picID);
  $stmt->execute();
  $stmt->store_result();

  /*
  Like the registerQuery we did for the register form, only one record can be 
  added per commentQuery to the database. Therefore, if $stmt's affected_rows 
  returns one, we know the query was successful. Otherwise, we know something 
  went wrong. Taking advantage of this, declare a new PHP variable called 
  $message. The value of $message relays the status of commentQuery back to 
  AJAX.
  */

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