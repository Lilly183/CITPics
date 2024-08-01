<?php
  require_once("includes/header.php");

  // Only admins should be able to access this page. If a user tries 
  // to circumvent this without signing into an admin account first, 
  // redirect them to the login page:

  if ($_SESSION['userAdminStatus'] != 1)
  {
    header("Location: login.php");
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

        <!-- Begin Row Mb-5 -->
        <div class="row mb-5">
          <div class="col-12 ">
            <h2 class="site-section-heading text-center">Admin</h2>
          </div>
        </div>

        <!-- Reserved for Future Use -->
        <div class="row">
          <table id="userList" class="w-100 text-center"></table>
          <div id="loadUsers"></div>
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

<!-- 
This is necessary for triggering the click event for <div> with id = "loadUsers", 
which proceeds to call AJAX, generating a link for every user in the database. 
Encapsulating the AJAX call within a click event ensures this is the only page 
that can activate it.
-->

<script>
  $(function ()
  {
    $("#loadUsers").trigger('click');
  });  
</script>