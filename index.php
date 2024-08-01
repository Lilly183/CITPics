<?php
  require_once("includes/header.php");
?>  

<!-- Begin Hero Section -->
<section id="hero">
  
  <!-- Begin Hero Container -->
  <div class="hero-container">
    
    <!-- Begin Hero Carousel -->
    <div id="heroCarousel" class="carousel slide carousel-fade" data-ride="carousel">

      <!-- Carousel Indicators -->
      <ol class="carousel-indicators">
        <li data-slide-to="0" class="active"></li>
        <li data-slide-to="1"></li>
      </ol> 

      <!-- Begin Carousel Inner -->
      <div class="carousel-inner" role="listbox">

        <!-- Slide 1 -->
        <div class="carousel-item active">
          <div class="carousel-container">
            <div class="carousel-content container">
              <h2>Welcome to CITPics!</h2>
              <p>
              Your #1 image sharing platform. Explore thousands of pictures from all over the world. Post your thoughts, 
              like your favorites, and contribute with uploads of your own. There's so much to do, and it's never been 
              easier to get started. So, what are you waiting for? Sign-in or create your free account today!
              </p>
              <a href="register.php" class="btn btn-primary btn-lg">Register</a>
              <a href="login.php" class="btn btn-primary btn-lg">Login</a>
            </div>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
          <div class="carousel-container">
            <div class="carousel-content container">
              <h2>Our Mission</h2>
              <p>
              They say a picture is worth a thousand words — and we couldn't agree more! But what's even better is 
              the social experience that's created when they're shared with others. Our website is designed with 
              that goal in mind. If you have any questions or comments, please contact our development team. We would 
              be happy to assist you!
              </p>
            </div>
          </div>
        </div>

      <!-- End Carousel Inner -->
      </div>

      <!-- Previous Arrow -->
      <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon icofont-rounded-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>

      <!-- Next Arrow -->
      <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon icofont-rounded-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    
    <!-- End Hero Carousel -->
    </div>

  <!-- End Hero Container -->
  </div>

<!-- End Hero Section -->
</section>

<?php
  require_once("includes/footer.php");
?>