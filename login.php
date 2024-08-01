<?php
  require_once("includes/header.php");

  /*
  ————————————————————
  Login Form Overview:
  ————————————————————

  Upon submission of the login form below, process the credentials that the user 
  has entered by performing a query of the 'user' table using the values of both 
  the "username" and "password" <input> tags. If the credentials are found to be 
  valid, redirect the user to either 'admin.php' (if logging in with an admin 
  account) or 'gallery.php' (if logging in as a regular user). Otherwise, print 
  an error message.
  */

  if (!empty($_POST))
  {
    // Clean up the user's input with htmlspecialchars() and trim(). Use md5() 
    // to hash the password. Store using PHP variables:

    $username = htmlspecialchars(trim($_POST['username']));
    $password = md5(htmlspecialchars(trim($_POST['password'])));

    /*
    Query the 'user' table. SELECT the ID, first name, last name, and admin fields 
    WHERE the username (u_Email) AND password match. The exact values for the 
    latter are left unspecified (='?') until we use bind_param() to define them 
    with the values of the PHP variables we declared earlier for storing the 
    user's input.
    */

    $loginQuery = 
      "SELECT 
      u_ID, u_FName, u_LName, u_isAdmin
      
      FROM
      user
      
      WHERE
      u_Email=? AND u_Password=?";

    // Prepare statement for execution (Get ready to do a query of the database):

    $stmt = $db->prepare($loginQuery);

    // Bind parameters. Necessary for WHERE. Two strings ("ss") substituted via
    // the order in which they appear within the query ($username first, then 
    // $password) 

    $stmt->bind_param("ss", $username, $password);

    // Execute the statement:

    $stmt->execute();

    // Store the result:
    
    $stmt->store_result();

    /*
    ———————————————————————
    If Query is Successful:
    ———————————————————————
    
    Provided that the user has entered valid credentials, only one record can be 
    returned per query. Therefore, if num_rows() is equal to 1, the user's login 
    attempt is successful.
    */

    if ($stmt->num_rows() == 1)
    {
      // Bind the results to PHP variables called $uploadID, $firstName, $lastName, 
      // and $isAdmin:

      $stmt->bind_result($uploadID, $firstName, $lastName, $isAdmin);

      $stmt->fetch();

      // Assign the value of session variable 'userLoggedIn' by concatenating the 
      // values of $firstName and $lastName:

      $_SESSION['userLoggedIn'] = $firstName . " " . $lastName;

      // Declare a new session variable called 'userAdminStatus' to access the value 
      // of $isAdmin beyond the scope of this branch. This will be used to prevent 
      // unauthorized access to 'admin.php' and decide whether or not we need to 
      // display a link to it in the header.

      $_SESSION['userAdminStatus'] = $isAdmin;

      // Declare a new session variable called 'userUploadID' for same reason as 
      // userAdminStatus. This will be used to set the value of u_ID when a new 
      // picture is added through the upload form.

      $_SESSION['userUploadID'] = $uploadID;

      /*
      Given that the login is successful, we must determine the administrative status
      of the account that the user is signing into so we know where to redirect them. 
      If $isAdmin is FALSE, the user will be redirected to the photo viewing page. 
      Otherwise, the user will be redirected to the admin page.
      */

      if ($isAdmin == 0 ? header("Location: gallery.php") : header("Location: admin.php"));

    }

    /*
    —————————————————————————
    If Query is Unsuccessful:
    —————————————————————————

    Should the query return no rows (meaning the login attempt was unsuccessful), 
    declare a PHP variable whose value is the HTML code below, which will be used 
    to print an error message.
    */

    else
    {
      $errorMessage = "
        <div class='errorMessage text-danger mb-3 text-center'>
          <p class='mb-0'>Invalid username or password.</p>
          <p class='mb-0'>Please try again.</p>
        </div>";
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

        <!-- Heading: Login -->
        <div class="row mb-5">
          <div class="col-12 ">
            <h2 class="site-section-heading text-center">Login</h2>
          </div>
        </div>
      
        <!-- Begin Login Form (Div) -->
        <div class="login-form my-4 mx-auto">
          
          <!-- Begin Login Form -->
          <form class="pt-5 px-5 pb-4 mb-4 border border-dark" action='<?=$_SERVER['PHP_SELF'];?>' method='POST'>
            
            <!-- Username -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon text-center"><i class="fa fa-user text-white"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Username" required="required" maxlength="100" value="<?=$_POST['username']?>">				
                </div>
            </div>
            
            <!-- Password -->
            <div class="form-group mb-4">
              <div class="input-group">
                  <span class="input-group-addon text-center"><i class="fa fa-lock text-white"></i></span>
                  <input type="password" name="password" class="form-control" placeholder="Password" required="required" value="<?=$_POST['password']?>">				
              </div>
            </div>
            
            <!-- Error Message -->
            <?= (!empty($errorMessage) ? $errorMessage : ""); ?>

            <!-- Sign-In Button -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block">Sign-In</button>
            </div>

          <!-- End Login Form -->
          </form>

          <p class="text-center">Don't have an account?<a href="register.php"> Register here!</a></p>
        
        <!-- End Login Form (Div) -->
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