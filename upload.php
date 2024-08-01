<?php
    require_once("includes/header.php");

    // Users should not be able to access this page without having first logged into
    // an account. Thus, if the value of 'userLoggedIn' is empty, redirect the user 
    // to the login page:

    if (empty($_SESSION['userLoggedIn']))
    {
        header("Location: login.php");
    }

    /*
    —————————————————————————————
    Prepare Variables for Upload:
    —————————————————————————————

    Credit goes to W3 Schools! 
    (https://www.tutorialspoint.com/php/php_file_uploading.htm):

    First, declare $target_Dir to specify a destination; this is where we want our uploaded file to go.
    
    Next, access the name attribute of the file which is being uploaded through element id='uploadFile' 
    from the $_FILES super global array and call basename() to extract the original name and extension 
    ('example.png'). Store this as the value of PHP variable $oldFileName. 
    
    Use pathinfo() with second argument PATHINFO_EXTENSION to assign $oldFileName's extension as the value 
    of $extension.

    Because it can lead to non-unique filenames, we probably shouldn't be letting users dictate the file 
    name of images stored on our server. Instead, create a new file name by concatenating the rounded value 
    of microtime() with the value of $extension. Although we will still check for file conflicts, this 
    should reduce their likelihood. 
    
    $target_Full_Path is the concatenation of both $target_Dir and $newFileName.

    Lastly, $uploadOK is a boolean used to control whether or not the upload process should be allowed to 
    proceed (Is the file being uploaded indeed an image file? Has a file with its name already been uploaded 
    before? Etc.)
    */
        
    if (!empty($_POST))
    {        
        // Set desination:
        $target_Dir = "uploads/";

        // Extract the original filename + extension:
        $oldFileName = basename($_FILES['uploadFile']['name']);
        
        // Get extension only:
        $extension = strtolower(pathinfo($oldFileName, PATHINFO_EXTENSION));

        // Generate unique filename (Concatenate current Unix timestamp with the extension):
        $newFileName = round(microtime(true)) . '.' . $extension;
        
        // The full path is the combination of the target directory and the new file name:
        $target_Full_Path = $target_Dir . $newFileName;
        
        // Initialize uploadOK to true:
        $uploadOK = true;

        /*
        ———————————————————————————————————————
        Check for Upload-Preventing Conditions:
        ———————————————————————————————————————

        Certain rules should be enforced before allowing the user's file to be uploaded. First, we need 
        to ensure that the uploaded file has a size. Then, we need to verify that a file with the same 
        name doesn't already exist. Lastly, only image files should be allowed. If everything checks out 
        (i.e. the value of uploadOK remains true), call move_uploaded_file() and pass $target_Full_Path 
        as its second argument to upload the user's file to the server.
        */

        // Will be used to provide feedback about the upload:
        
        $errorReason = "";
        $uploadMessage = "";
        
        /*
        —————————————————————————————
        Does the Uploaded File Exist?
        —————————————————————————————
        */

        if (getimagesize($_FILES['uploadFile']['tmp_name']) !== false)
        {
            $uploadOK = true;
        }

        else
        {
            $errorReason .= "<p class='m-0'>Reason: Your file does not appear to exist.</p>";
            $uploadOK = false;
        }

        /*
        —————————————————————————————————————————————————————
        Is the Uploaded File Already in the Target Directory?
        —————————————————————————————————————————————————————
        */
        
        if (file_exists($target_Full_Path))
        {
            $errorReason .= "<p class='m-0'>Reason: Another file with that name already exists.</p>";
            $uploadOK = false;
        }

        /*
        —————————————————————————————————
        Allow Only Compatible Extensions:
        —————————————————————————————————
        */
    
        $allowedExtensions = array("jpg", "jpeg", "png", "gif");
    
        if (!(in_array($extension, $allowedExtensions)))
        {
            $errorReason .= "<p class='m-0'>Reason: Unsupported file type (Must be .jpg, .jpeg, .png, or .gif).</p>";
            $uploadOK = false;
        }
    
        // Check the value of uploadOK to decide whether or not to proceed:

        if ($uploadOK == false)
        {
            $uploadMessage = 
            "<div class='uploadMessageFail text-danger mb-3 text-center'>
                <p class='m-0'>Your image could not be uploaded!</p>" . 
                $errorReason . 
            "</div>";
        }

        // If everything checks out, try to upload the file:
    
        else
        {
            // If the upload succeeds...

            if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $target_Full_Path))
            {
                /*
                Perform a query to add a new record to the 'pic' table. This is just like
                what we did for 'register.php'. Get the values we need from $_POST, bind
                our parameters, and verify that $stmt's affected_rows == 1. Set the value 
                of uploadMessage accordingly.
                */

                $fileTitle = htmlspecialchars(trim($_POST['uploadTitle']));
                $fileSummary = htmlspecialchars(trim($_POST['uploadDescription']));

                $uploadQuery = 
                "INSERT INTO
                pic (p_Filename, p_Title, p_Summary, u_ID)

                VALUES
                (?, ?, ?, ?)";

                $stmt = $db->prepare($uploadQuery);
                $stmt->bind_param("sssi", htmlspecialchars(trim($newFileName)), $fileTitle, $fileSummary, $_SESSION['userUploadID']);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->affected_rows == 1)
                {
                    $uploadMessage = 
                    "<div class='uploadMessageSuccess mb-3 text-center'>
                        <p class='m-0'>File uploaded successfully!</p>
                    </div>";
                }

                else
                {
                    $uploadMessage = 
                    "<div class='uploadMessageFail text-danger mb-3 text-center'>
                        <p class='m-0'>File uploaded, but the database failed to update.</p>
                    </div>";
                }
            }

            // If the upload fails...

            else
            {
                $uploadMessage =
                "<div class='uploadMessageFail text-danger mb-3 text-center'>
                    <p class='m-0'>There was an error uploading your file.</p>
                </div>";
            }
        }
    }
?>

<!-- Begin Site-Section -->
<div class="site-section" data-aos="fade">
    
    <!-- Begin Container-Fluid -->
    <div class="container-fluid">
    
        <!-- Begin Row Justify-Content-Center -->
        <div class="row justify-content-center">
            
            <!-- Begin Col-Md-7 -->
            <div class="col-md-7">
                
                <!-- Heading: Upload -->

                <?= // Print the value of $uploadMessage if it isn't empty (Also change heading's bottom margin)

                    (!empty($uploadMessage) 
                        ?   "<div class='row mb-2'>
                                <div class='col-12'>
                                <h2 class='site-section-heading text-center'>Upload</h2>
                                </div>
                            </div>" . 
                            $uploadMessage 
                        :   "<div class='row mb-5'>
                                <div class='col-12'>
                                <h2 class='site-section-heading text-center'>Upload</h2>
                                </div>
                            </div>"
                    ); 
                ?>

                <!-- Begin Upload Form (Div) -->
                <div class="upload-form pt-5 px-5 pb-4 mb-4 border border-dark">
                    
                    <!-- Begin Upload Form (NOTE: enctype="multipart/form-data" => Cannot upload files without it!) --> 
                    <form action='<?=$_SERVER['PHP_SELF'];?>' method='POST' enctype="multipart/form-data">
    
                        <!-- Upload File -->
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="uploadFile">Image File</label> 
                                <input type="file" id="uploadFile" name="uploadFile" class="form-control px-1" required="required" maxlength="100" accept="image/*">
                            </div>
                        </div>
                        
                        <!-- Upload Title -->
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="uploadTitle">Image Title</label>
                                <input type="text" id="uploadTitle" name="uploadTitle" class="form-control" placeholder="Image-001" required="required" maxlength="128">
                            </div>
                        </div>
    
                        <!-- Upload Description -->
                        <div class="row form-group mb-4">
                            <div class="col-md-12">
                                <label class="text-black" for="uploadDescription">Image Description</label> 
                                <textarea id="uploadDescription" name="uploadDescription" class="form-control" placeholder="Write a brief summary of your image..." required="required" cols="30" rows="4"></textarea>
                            </div>
                        </div>

                        <!-- Upload Button -->
                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Upload</button>
                            </div>
                        </div>
                    
                    <!-- End Upload Form -->
                    </form> 
                
                <!-- End Upload Form (Div) -->
                </div>

            <!-- End Col-Md-7 -->
            </div>

        <!-- End Row Justify-Content-Center -->
        </div>

    <!-- End Container-Fluid -->
    </div>
  
<!-- End Site-Section -->
</div>

<?php
  require_once("includes/footer.php");
?>