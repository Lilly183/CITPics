<?
    // Include session_start() to either begin a new session (if one has not been created already) 
    // or retrieve an existing session across every single page of the website:

    session_start();

    // Include the DB config file:
    
    require_once(__dir__ . "/config.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>CIT Pics</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/ico" href="images/icons/favicon.ico"/>
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300i,400,700" rel="stylesheet">

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/swiper.min.css">
    <link rel="stylesheet" href="css/aos.css">                              <!-- Animations (i.e., fade effect) -->
    <link rel="stylesheet" href="css/fancybox.min.css">

    <link rel="stylesheet" href="css/hero.css">                             <!-- Hero Carousel-->
    <link rel="stylesheet" href="css/photon.css">                           <!-- Template CSS (Formerly style.css, renamed to Photon.css) -->
    <link rel="stylesheet" href="css/style.css">                            <!-- Main CSS (Footer, Login Form, Register Form, Upload Form, etc.) -->
    <link rel="stylesheet" href="css/gallery.css">                          <!-- CSS for Gallery Page -->

    <link rel="stylesheet" href="fonts/icomoon/style.css">                  <!-- Heart Icon -->
    <link rel="stylesheet" href="fonts/font-awesome/css/font-awesome.css">  <!-- User & Padlock Icons on Login Page -->
    <link rel="stylesheet" href="fonts/icofont/icofont.min.css">            <!-- Arrow Icons for Hero Carousel -->
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">

</head>

<!-- Begin Body -->
<body>

    <!-- Begin Site Wrap   -->
    <div class="site-wrap">

        <!-- Mobile Menu -->
        <div class="site-mobile-menu">
            <div class="site-mobile-menu-header">
                <div class="site-mobile-menu-close mt-3">
                    <span class="icon-close2 js-menu-toggle"></span>
                </div>
            </div>
            <div class="site-mobile-menu-body"></div>
        </div>

        <!-- Begin Header -->
        <header class="site-navbar py-3" role="banner">

            <!-- Begin Container Fluid -->
            <div class="container-fluid h-100">

                <!-- Begin Row Align-Items-Center -->
                <div class="row align-items-center h-100">

                    <!-- Logo -->
                    <div class="col-6 col-xl-2" data-aos="fade-down">
                        <h1 class="mb-0"><a href="index.php" class="text-black h2 mb-0"><img id="citLogo" src="images/logo.png" alt="CIT Pics Logo"></a></h1>
                    </div>

                    <!-- NavBar-->
                    <div class="col-10 col-md-8 d-none d-xl-block" data-aos="fade-down">
                        <nav class="site-navigation position-relative text-right text-lg-center" role="navigation">
                            <ul class="site-menu js-clone-nav mx-auto d-none d-lg-block">
                                <? 
                                    // Links to 'gallery.php' and 'upload.php' will not appear unless a user has 
                                    // first signed-in.
                                    
                                    if (isset($_SESSION['userLoggedIn']))
                                    {
                                        echo "<li><a href='gallery.php'>Gallery</a></li>
                                            <li><a href='upload.php'>Upload</a></li>";

                                        // Link to 'admin.php' will only appear for administrative accounts:

                                        if ($_SESSION['userAdminStatus'] == 1)
                                        {
                                            echo "<li><a href='admin.php'>Admin</a></li>";
                                        }
                                    }
                                ?>
                            </ul>
                        </nav>
                    </div>

                    <!-- Account Management -->
                    <div class="col-6 col-xl-2 text-right" data-aos="fade-down">
                        <div class="d-none d-xl-inline-block">
                            <ul class="site-menu js-clone-nav ml-auto list-unstyled d-flex text-right mb-0" data-class="social">
                                <?
                                // If a user is signed-in (value of session variable 'userLoggedIn' IS SET), print the user's 
                                // name (the value that session variable 'userLoggedIn' stores). Otherwise, print links to the 
                                // login and register pages.
                                
                                echo (isset($_SESSION['userLoggedIn']) 
                                        ?   "
                                            <h6 class='my-auto px-2 text-center'>Welcome, " . $_SESSION['userLoggedIn'] . "!</h6>
                                            <div class='col px-0'>
                                                <li><a href='logout.php' id='logoutButton' class='d-flex h-100'><span class='mx-auto my-auto'>Logout</span></a></li>
                                            </div>"
                                        :   "
                                            <li><a href='register.php' class='px-3'><span>Register</span></a></li>
                                            <li><a href='login.php' class='px-3'><span>Login</span></a></li>"
                                    );
                                ?>
                            </ul>
                        </div>
                        <div class="d-inline-block d-xl-none ml-md-0 mr-auto py-3" style="position: relative; top: 3px;">
                            <a href="#" class="site-menu-toggle js-menu-toggle text-black"><span class="icon-menu h3"></span></a>
                        </div>
                    </div>

                <!-- End Row Align-Items-Center -->
                </div>

            <!-- End Container Fluid -->
            </div>

        <!-- End Header -->
        </header>