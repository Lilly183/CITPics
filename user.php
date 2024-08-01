<?php
  require_once("includes/header.php");

  // This page is accessed when an administrator follows the link in 'Admin.php' for 
  // a given user's name and allows for the deletion of pictures from the database; 
  // needless to say, this page should only be accessible to administrators!

  if ($_SESSION['userAdminStatus'] != 1)
  {
    header("Location: login.php");
  }

  // When this page loads, query the 'picture' table so that it displays all of the 
  // pictures associated with the user that the admin has clicked. Pull the id from 
  // the query string and use its value to bind our sole parameter. After execution,
  // bind each result to PHP variables $pictureID, $pictureFileName, and $pictureTitle 
  // so that we can use these to echo out HTML code.

  $id = htmlspecialchars(trim($_GET['id']));

  $userQuery = 
  "SELECT 
  p_ID, p_Filename, p_Title

  FROM
  pic
  
  WHERE
  u_ID=?";

  // Prepare statement and bind parameters:

  $stmt = $db->prepare($userQuery);
  $stmt->bind_param("s", $id);
  
  // Execute:

  $stmt->execute();

  // Store the result and bind it to PHP variables $pictureID, $pictureFileName, and $pictureTitle:
  
  $stmt->store_result();
  $stmt->bind_result($pictureID, $pictureFileName, $pictureTitle);
  
  // Build an associative array called $picArr which contains all pictures that the clicked 
  // user has uploaded:

  while ($stmt->fetch())
  {
    $picArr[] = array("id"=>$pictureID, "filename"=>$pictureFileName, "title"=>$pictureTitle);
  }
?>

<div class="site-section" data-aos="fade">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-7">
        
        <div class="row">
          <div class="col-12 ">
            <h2 id="adminDirectionsHeading" class="text-center pb-3 mb-0">Select an image below to remove it from the database:</h2>
          </div>
        </div>

        <div class="row pb-3 justify-content-center">
          <div id="deleteContainer" class="align-items-stretch d-flex">
              <div class="row align-items-stretch my-auto">
                
                <?
                  $relativeFolderPath = "../../uploads/";

                  /*
                  With the associative array now populated, all that's left to do is display its contents. Follow 
                  the same approach used for 'Gallery.php' — For each picture, echo out HTML which includes the 
                  'object's' id, filename, and title. Note the inclusion of the class="deletable". All elements 
                  of this type are assigned with click event listeners that, when triggered, call AJAX, which 
                  proceeds to send the value of p_id (attained via the 'id' attribute) to 'deletePic.php', query 
                  the 'pic' table, and remove it from the database.
                  */

                  // If picArr is empty, display a message indicating that the user hasn't uploaded anything yet:

                  if (empty($picArr))
                  {
                    echo "<h4>This user has not uploaded any images yet.</h4>";
                  }

                  // Otherwise, print each picture that the selected user has uploaded:

                  else
                  {
                    foreach ($picArr as $picture)
                    {
                      echo 
                      "
                      <div id='" . $picture["id"] . "' class='col-4 item py-3 deletable' data-aos='fade-up'>
                        <img src='" . $relativeFolderPath . $picture["filename"] . "' alt='" . $picture["title"] . "' class='img-fluid'>
                      </div>
                      ";
                    }
                  }
                ?>

              </div>
          </div>
        </div>

        <div class="row">
          <a href="admin.php" class="btn btn-primary btn-lg btn-block">Return to Admin</a>
        </div>

      </div>
    </div>
  </div>
</div>

<?php
  require_once("includes/footer.php");
?>