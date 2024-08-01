<?php
  require_once("includes/header.php");

  /*
  Like the login page, begin by checking the contents of $_POST. When the 
  form is submitted, the page reloads because of the action attribute 
  (=$_SERVER['PHP_SELF'];). However, all fields must be present when this 
  happens, enforced via the "required" attribute assigned to all HTML 
  <input> tags. Therefore, assuming that the user has filled out every 
  field, $_POST should not be empty. We can then "clean up" the user's 
  input and assign these values to PHP variables as we prepare to add a 
  new user to the database. 
  */

  if (!empty($_POST))
  {
    $firstName = htmlspecialchars(trim($_POST['fname']));
    $lastName = htmlspecialchars(trim($_POST['lname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = md5(htmlspecialchars(trim($_POST['password'])));
    $location = htmlspecialchars(trim($_POST['zipcode']));
    
    // Per the assignment's instructions, all new registrants will be 
    // standard users (0 for is_Admin):

    $admin = 0;

    /*
    Unlike the login form – which is focused on getting data BACK from 
    the 'user' table in the database – the register form is used to ADD
    a new record to it. For that, we need INSERT. Moreover, the values 
    for each field of the new record are pulled from the values of the 
    PHP variables we declared earlier (which, again, store the cleaned-up
    versions of the user's input).

    (Note that u_ID is skipped in the query below because it is 
    auto-incremented.)

    As a sidenote, INSERT IGNORE could have been used to prevent a new 
    account from being created with an existing email address, which 
    might cause problems with logins (What if a single email address has 
    multiple passwords?). However, this requires a UNIQUE constraint on 
    u_Email. For this reason, we'll stick to the standard INSERT method
    instead.
    */

    $registerQuery = 
    "INSERT INTO
    user (u_FName, u_LName, u_Email, u_Password, u_Location, u_isAdmin)

    VALUES
    (?, ?, ?, ?, ?, ?)";

    // Prepare the database for register query:

    $stmt = $db->prepare($registerQuery);

    // Bind parameters:

    $stmt->bind_param("sssssi", $firstName, $lastName, $email, $password, $location, $admin);

    // Execute the statement:

    $stmt->execute();

    // Store the result:

    $stmt->store_result();

    // One record can be added to the 'user' table at a time; if statement's 
    // affected_rows == 1, the operation was successful. Redirect the user 
    // to the login page so they can proceed to sign-in.

    if ($stmt->affected_rows == 1)
    {
      header("Location: login.php");
     
      // DEPRECATED:

      // $registerMessage =
      // "<div class='registerMessageSuccess mb-3 text-center'>
      //   <p class='mb-0'>Registration succeeded!<a href='login.php'> Click here </a>to sign-in.</p>
      // </div>";
    }

    // Otherwise, assign the value of $registerMessage with HTML code informing 
    // the user that something went wrong. 

    else
    {
      $registerMessage = 
      "<div class='registerMessageFail text-danger mb-3 text-center'>
        <p class='mb-0'>Registration failed! Please try again.</p>
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

        <!-- Heading: Register -->
        <div class="row mb-5">
          <div class="col-12">
            <h2 class="site-section-heading text-center">Register</h2>
          </div>
        </div>

        <!-- Begin Register Form (Div) -->
        <div class="register-form pt-5 px-5 pb-4 mb-4 border border-dark">

          <!-- Begin Register Form (Form) -->
          <form action='<?=$_SERVER['PHP_SELF'];?>' method='POST'>

            <!-- First and Last Name -->
            <div class="row form-group">
              <div class="col-md-6 mb-3 mb-md-0">
                <label class="text-black" for="fname">First Name</label>
                <input type="text" id="fname" name="fname" class="form-control" placeholder="John" required="required" maxlength="100">
              </div>
              <div class="col-md-6">
                <label class="text-black" for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" class="form-control" placeholder="Doe" required="required" maxlength="100">
              </div>
            </div>

            <!-- Email -->
            <div class="row form-group">
              <div class="col-md-12">
                <label class="text-black" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Example@email.com" required="required" maxlength="100">
              </div>
            </div>

            <!-- Password -->
            <div class="row form-group">
              <div class="col-md-12">
                <label class="text-black" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="abc123" required="required">
              </div>
            </div>

            <!-- Zip Code -->
            <div class="row form-group mb-4">
              <div class="col-md-12">
                <label class="text-black" for="zipcode">Zip Code</label>
                <input type="text" id="zipcode" name="zipcode" class="form-control" placeholder="88310" required="required" pattern="[0-9]*" minlength="5" maxlength="5">
              </div>
            </div>

            <!-- Register Button -->
            <div class="row form-group">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-lg btn-block">Register Now</button>
              </div>
            </div>

          <!-- End Register Form -->
          </form>

        <!-- End Register Form (Div) -->
        </div>

        <!-- Link to Login Page -->
        
        <?= 
          // If the value of register message is not empty, print the value of register message. Otherwise, 
          // print a link to the login page.

          (!empty($registerMessage) 
            ? $registerMessage 
            : "<div class='text-center'>Already have an account?<a href='login.php'> Sign-in here!</a></div>"
          ); 
        ?>

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