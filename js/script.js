// Ensure the DOM has been loaded:

$(function ()
{
    /*
    ————————————————————————————————————————————————————
    "View Comments" - Toggle Display of Comment Section:
    ————————————————————————————————————————————————————

    For the Gallery page, <span> tags with class="viewComments" are used to toggle whether 
    the comment section below each picture is shown or hidden. Event listeners are assigned 
    so that, when clicked, a function goes up two levels before finding the next <div> element 
    with class="commentSectionContainer". Also adjusts the bottom border for "image-card-footer" 
    depending on whether the comment section is visible or not.
    */

    $("span.viewComments").click(function ()
    {
        // For whichever <span> tag was clicked, find the corresponding commentSectionContainer 
        // <div> and toggle its visibility.

        const $targetCommentContainer = $(this).parent().parent().next("div.commentSectionContainer");
        $targetCommentContainer.toggle();

        if ($targetCommentContainer.is(":hidden")

            // If the target commentSectionContainer is currently being hidden, re-apply 
            // the bottom border for "image-card-footer":
        
            ? $targetCommentContainer.prev().css("border-bottom", "1px solid #72b6a2")
        
            // Otherwise, hide the bottom-border:
        
            : $targetCommentContainer.prev().css("border-bottom", "none")
        );
    })

    /*
    ————————————————————————————————————————
    Prevent Page Refresh Upon Hitting Enter:
    ————————————————————————————————————————

    Overrides the default behavior for <input> tags within a form when hitting 'Enter'. 
    This prevents accidental refreshes and forces the user to hit "Post!" in order to 
    submit a new comment.
    */

    $("input.commentTextBox").keypress(function (event)
    {
        if (event.which == '13')
        {
            event.preventDefault();
        }
    })

    /*
    ————————————————————————————
    "Post!" - Add a New Comment:
    ————————————————————————————

    "Post!" buttons are found within the comment section for each picture on the Gallery 
    page. Assign event listeners to all "Post!" buttons so that, when clicked, the value 
    that was entered into the textbox preceding the "Post!" button is retrieved and then 
    submitted to the database (if not an empty string). 
    */

    $("button.commentPostButton").click(function ()
    {
        // Let the trimmed version of the comment that the user is trying to post be stored
        // in a variable called newUserComment:

        const $newUserComment = $(this).prev().val().trim();

        // If newUserComment is not blank...

        if ($newUserComment)
        {
            // Determine the id of the picture object being commented on by walking up the 
            // DOM and retrieving the id of the image card, whose value was assigned using 
            // p_ID when the HTML was echoed via PHP.
            
            const $targetPicture = $(this).parent().parent().parent().attr('id');

            // Get the <div> with class = "commentSectionComments" and store this as a variable
            // called targetCommentSection. Declare another variable whose value contains the 
            // HTML code we will be injecting into this node as the node's first child once we 
            // have verified that the new comment has been added to the database.

            const $targetCommentSection = $(this).parent().prev("div.commentSectionComments");
            const $newCommentHTML = $("<div class='py-1 px-2'>" + $newUserComment + "</div>");

            // Invoke AJAX. Send JSON that includes the values of $newUserComment and $targetPicture 
            // via POST to URL '../ajax/addComment.php'. Refer to that page for more details.

            $.ajax(
            {
                url: "../ajax/addComment.php",
                data: {'c_Text' : $newUserComment, 'p_ID' : $targetPicture}, 
                datatype: "json",
                method: "post",
            
                success: function(data)
                {
                    // If the message returned from addComment.php == "Success", the query responsible 
                    // for inserting the new record has succeeded in adding a new comment to the database.

                    if ($.parseJSON(data).message == "Success!")
                    {                        
                        // Although we could the force page to reload for the user to see the new 
                        // comment, given that we can rest assured (if this branch executes) that 
                        // the comment has been added to the database, we'll go ahead and print 
                        // everything by prepending targetCommentSection with newCommentHTML (new 
                        // comments appear at the top of the list):
                        
                        $targetCommentSection.prepend($newCommentHTML);
                    }
                    
                    // Otherwise, the data returned from addComment.php is valid JSON, but something 
                    // went wrong with adding a new comment via commentQuery. Report as an alert.

                    else
                    {
                        alert("ERROR: AJAX entered success state, but the message received indicates a failure to add the new comment to the database.");
                    }
                },
            
                // If AJAX encounters an error in which no valid JSON is returned from 'addComment.php', 
                // print an alert:

                error: function (data)
                {
                    alert("ERROR: AJAX entered error state. No valid JSON was received from target URL.");
                }
            });
        }
    })

    /*
    ——————————————————————————————
    Like Button - Increment Likes:
    ——————————————————————————————
    */
    
    $("i.likeButton").click(function ()
    {
        /*
        Get the <span> tag located next to the <i> tag that was clicked and assign it to a 
        variable called spanTag. From its text, retrieve the current number of likes (derived 
        from the database) and store this as currLikes. Convert currLikes into an integer.
        */
        
        $spanTag = $(this).next();
        $currLikes = parseInt($spanTag.text());
        
        // Determine the id of the picture object whose likes we wish to increment in the database.
        
        const $targetPicture = $(this).parent().parent().parent().attr('id');

        // Use AJAX to update the database, passing along the value of p_id via targetPicture:
        
        $.ajax(
        {
            url: "../ajax/updateLikes.php",
            data: {"p_ID" : $targetPicture},
            datatype: "json",
            method: "post",

            /*
            'updateLikes.php' can return three types of messages. "Success!" / "Failure!" occur 
            when a user tries to like a picture for the first time; "Success!" is returned after 
            the database manages to update p_likes successfully, whereas "Failure!" appears when 
            something goes wrong with likeQuery.

            "Forbidden!" is returned when a user is trying to update a picture that they've already 
            liked.
            */

            success: function(data)
            {
                // If we're assured that p_Likes for the targetPicture has been updated in 
                // the database, increment the value encased within the <span> tag located 
                // next to the heart icon.

                if ($.parseJSON(data).message == "Success!")
                {
                    $spanTag.text($currLikes + 1);
                }

                // If something we went wrong with the update process, send an alert:

                else if ($.parseJSON(data).message == "Failure!")
                {
                    alert("ERROR: AJAX entered success state, but the message received indicates a failure to update likes in the database.");
                }

                // Let the user know if they have already liked a picture before:

                else
                {
                    alert("You have already liked this picture before.");
                }
            },

            // Alert when no valid JSON is received from 'updateLikes.php':

            error: function(data)
            {
                alert("ERROR: AJAX entered error state. No valid JSON was received from target URL.");
            }
            
        });
    })

    /*
    ——————————————————————————————————————————
    Admin Page - Generate a List of All Users:
    ——————————————————————————————————————————

    'Admin.php' contains a <div> tag with id attribute = "loadUsers", assigned below with 
    a "click" event listener. When the page is finished loading, a <script> tag triggers 
    this 'click' event, causing AJAX to open 'listUsers.php'. 'listUsers.php' queries the
    database for all users and returns them as an array formatted in JSON. For each user, 
    HTML code is concatenated to the value of outputHTML, which later becomes the HTML of 
    #userList table. Credit for this approach goes to Dr. Morgan!
    */

    $("#loadUsers").click(function()
    {
        $.ajax(
        {
            url: "../ajax/listUsers.php",
            datatype: "json",

            success: function(data)
            {
                var outputHTML = "";

                $.each($.parseJSON(data), function(index, user)
                {
                    outputHTML +=   "<tr>" +
                                        "<td>" + 
                                            "<a href='user.php?id=" + user.id + "' class='d-block'>" + user.firstName + "</a>" + 
                                        "</td>" + 
                                        "<td>" + 
                                            "<a href='user.php?id=" + user.id + "' class='d-block'>" + user.lastName + "</a>" + 
                                        "</td>" + 
                                        "<td>" + 
                                            "<a href='user.php?id=" + user.id + "'class='d-block'>" + user.email + "</a>" + 
                                        "</td>" + 
                                    "</tr>";
                });

                $("#userList").html(outputHTML);
            },

            error: function(data)
            {
                alert("ERROR: AJAX entered error state. No valid JSON was received from target URL.");
            }
        });
    })

    /*
    ——————————————————————————————
    Admin Page - Delete a Picture:
    ——————————————————————————————

    In addition to their id attribute being assigned with the value of p_ID, images in 
    'user.php' are assigned with a class = "deletable". Considering our modus operandi 
    for incrementing likes and adding a new comment, there're no surprises here; get the 
    id of the picture we want to target in the 'pic' table and send it via AJAX to a php 
    page ('deletePic.php') for processing.
    */

    $(".deletable").click(function ()
    {
        // Get id of target picture in database:

        const $targetPicture = $(this).attr('id');

        // Call AJAX to send the value of $targetPicture to deletePic.php, which executes 
        // a query to delete the aforementioned picture:

        $.ajax(
        {
            url: "../ajax/deletePic.php",
            data: {"p_ID" : $targetPicture},
            datatype: "json",
            method: "post",

            success: function(data)
            {
                // If the image was successfully deleted, reload the page:

                if ($.parseJSON(data).message == "Success!")
                {
                    location.reload();
                }

                // Otherwise, print the usual error message:

                else
                {
                    alert("ERROR: AJAX entered success state, but the message received indicates a failure to delete the picture from the database.");
                }
            },

            error: function(data)
            {
                alert("ERROR: AJAX entered error state. No valid JSON was received from target URL.");
            }
        })
    })
});