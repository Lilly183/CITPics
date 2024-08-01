<?php
  require_once("includes/header.php");

  // Users should not be able to access this page without having first logged into
  // an account. Thus, if the value of 'userLoggedIn' is empty, redirect the user 
  // to the login page:

  if (empty($_SESSION['userLoggedIn']))
  {
    header("Location: login.php");
  }
?>

<!-- Begin Site-Section -->
<div class="site-section" data-aos="fade">
    
  <!-- Begin Container-Fluid -->
  <div class="container-fluid">

  	<!-- Begin Gallery Section -->
  	<section class="row align-items-stretch images" id="section-images">
		
		<!-- Begin Col-12 -->
		<div class="col-12">

			<!-- Begin Row Align-Items-Stretch -->
			<div class="row align-items-stretch">

  				<?
				/*
				—————————————————————————————————————
				Create Associative Array of Pictures:
				—————————————————————————————————————

				Get all of the pictures from the database using a query. Order the results 
				according to who uploaded the picture, followed by when it was uploaded (in 
				descending order). Return only the fields whose values will be relevant for 
				echoing HTML code via PHP (p_ID, p_Filename, p_Title, p_Summary, and p_Likes).

				Call mysqli_fetch_all(). Pass second argument 'MYSQLI_ASSOC.' This will 
				return each row fetched from the query's result as an associative array of 
				strings; the keys of these arrays are equivalent to the names used for the 
				columns in the database.
				
				To better illustrate how our results are being formatted, the raw output of 
				print_r() is shown below:

				Array
				(
					[0] => Array
					(
						[p_ID] => "1"
						[p_Filename] => "Sample1.jpg"
						[p_Title] => "Sample Title 1"
						[p_Summary] => "Sample description for image 1."
						[p_Likes] => "0"
					)

					[1] => Array
					(
						[p_ID] => "2"
						[p_Filename] => "Sample2.jpg"
						[p_Title] => "Sample Title 2"
						[p_Summary] => "Sample description for image 2."
						[p_Likes] => "0"
					)

					...
				)
				*/

				$pictureQuery = 
				"SELECT  
				p_ID, p_Filename, p_Title, p_Summary, p_Likes

				FROM
				pic

				ORDER BY
				u_ID, p_Upload DESC";

				$result = mysqli_query($db, $pictureQuery);
				$pictureArr = mysqli_fetch_all($result, MYSQLI_ASSOC);

				/*
				—————————————————————————————————————
				Create Associative Array of Comments:
				—————————————————————————————————————

				Repeat this same process for comments. All comments will be pulled from 
				the 'comment' table. They are sorted first by the picture to which they 
				belong (p_ID), then by the date they were posted (c_Date, in descending 
				order). Return only p_ID and c_Text. Store everything as a PHP variable
				called $commentArr.
				*/

				$commentQuery = 
				"SELECT
				p_ID, c_Text

				FROM
				comment

				ORDER BY
				p_ID, c_Date DESC";

				$result = mysqli_query($db, $commentQuery);
				$commentArr = mysqli_fetch_all($result, MYSQLI_ASSOC);

				// Specifies the folder path used for finding images when paired with the 
				// src attribute. Everything is relative to where the PHP is running from.
				
				$relativeFolderPath = "../../uploads/";
				
				// Iterate through each "object" in pictureArr. Most HTML code is common to 
				// all pictures. The values specific to each one are pulled from pictureArr,
				// whose data was populated from the database. 

				foreach ($pictureArr as $picture)
				{
					echo
					"
					<div id='" . $picture["p_ID"] . "' class='col-sm-6 col-md-4 col-lg-3 col-xl-3' data-aos='fade-up'>" .
						
						// ————————————————————————
						// Image Filename Goes Here
						// ————————————————————————

						"<a href='" . $relativeFolderPath . $picture["p_Filename"] . "' class='d-block image-item' data-fancybox='gallery'>
							<img src='" . $relativeFolderPath . $picture["p_Filename"] . "' alt='" . $picture["p_Title"] . "' class='img-fluid'>
							<div class='image-text-more'>
								<span class='icon icon-search'></span>
							</div>
						</a>
						<div class='image-card-body'>" .

							// —————————————————————
							// Image Title Goes Here
							// —————————————————————

							"<h5 class='image-card-title text-center text-white mb-0'>" . $picture["p_Title"] . "</h5>" .

							// ———————————————————————
							// Image Summary Goes Here 
							// ———————————————————————

							"<p class='image-card-summary m-0 pt-2 px-3 pb-3'>" . $picture["p_Summary"] . "</p>
						</div>
						<div class='image-card-footer'>
							<div class='image-card-likes'>" .

								// ———————————————————
								// Image Likes Go Here
								// ———————————————————
								
								"<i class='likeButton icon-heart' aria-hidden='true'></i>" . " <span>" . $picture["p_Likes"] . "</span>
							</div>
							<div class='image-card-show-comments text-right'>
								<span class='viewComments'>View Comments</span>
							</div>    
						</div>" .

						// ——————————————————————
						// Image Comments Go Here
						// ——————————————————————

						"<div class='commentSectionContainer'>
							<div class='commentSectionComments'>";
							
							/*
							For each picture, we must iterate through each comment in $commentArr, checking 
							whether the comment's p_ID is the same as the current picture's p_ID. If it is, 
							the two objects go together; echo HTML code including the comment's c_Text.
							*/

							foreach ($commentArr as $comment)	
							{
								if ($comment["p_ID"] == $picture["p_ID"])
								{
									echo 
									"
									<div class='py-1 px-2'>" .
										$comment["c_Text"] . 
									"</div>
									";
								}
							}
							
							echo 
							"</div>
							<form class='postCommentForm form-group'>
								<input type='text' class='commentTextBox' placeholder='Type your comment here...'>
								<button type='button' class='commentPostButton btn btn-primary'>Post!</button>
							</form>
						</div>
					</div>
					";
				}
				?>

			<!-- End Row Align-Items-Stretch -->
			</div>

		<!-- End Col-12 -->
		</div>
	
	<!-- End Gallery Section -->
	</section>

  <!-- End Container-Fluid -->
  </div>

<!-- End Site-Section -->
</div>

<?php
  require_once("includes/footer.php");
?>