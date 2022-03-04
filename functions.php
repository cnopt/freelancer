<?php
//Script for functions within website
//Jas

//creates the head tag for the pages
function head() {
    echo "
            <!doctype html>
            <html lang=\'en\'>
            <!-- Required meta tags -->
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
        
            <!-- Bootstrap CSS -->
            <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css'>
            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js'></script>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'></script>
            
            <script src='http://code.jquery.com/jquery-1.9.1.js'></script>
        
            <title>Freelancer </title>
            <!-- the nav bar is only a slightly modified bootstrap navbar, this makes it very responsive. contains links to all other pages of the website-->
        
<nav class='navbar fixed-top navbar-expand-lg navbar-light bg-light'>

    <a class='navbar-brand .text-warning' href='../index.php'>Freelancer</a>

    <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
        <span class='navbar-toggler-icon'></span>
    </button>

    <div class='collapse navbar-collapse' id='navbarSupportedContent'>
        <ul class='navbar-nav mr-auto'>
            <li class='nav-item'>
                <a class='nav-link' href=''>Browse Jobs</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='/social_media/userList.php'>Discover People</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='/social_media/userFeed.php'>Social Feed</a>
            </li>
        </ul>";
        echo loginButton();
        echo logoutButton();
    echo "</div></nav>";
}

//User login code that adds a button to the page

function loginButton() {
    require_once "./user/config.php";
    
    $login_button = '';
	
	if (isset($_GET["code"]))
	{
		$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
		
		if(!isset($token['error']))
		{
			$google_client->setAccessToken($token['access_token']);
		}
	}
	
	if(!isset($token['access_token']))
	{
		$login_button = '<a href="'.$google_client->createAuthUrl().'"><img src="signinbutton.png"/></a>';
	}
	echo '<div allign="center">'.$login_button . ' </div>';
}
//User logout function that adds the logout button to the page
function logoutButton() {
		echo '<h1><a href="/../logout.php">Logout</a></h1>';
}
//Ends html for page
function endHtml() {
    echo "</html>";
}

//Gets most popular updates within feed for homepage
function getPopularUpdates() {
        
    $url = parse_url("mysql://b6a9b4a53e1530:fd235082@eu-cdbr-west-02.cleardb.net/heroku_893a1d0add172e6?reconnect=true");
    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $db = substr($url["path"], 1);

    $conn = new mysqli($server, $username, $password, $db);
                
        // Checks connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
    //Query to get feed
    $getPosts = "SELECT profile.name, profile.id as 'profile_id', profile.profile_picture, post.id as 'post_id', post.text, DATE_FORMAT(post.timestamp, '%d %b %h:%i %p') as 'timestamp', count(post_has_like.post_id) as 'likes', file, posted_work.id as 'work_id'
    FROM post
    LEFT JOIN 
    post_has_like ON post_has_like.post_id = post.id
    LEFT JOIN 
    posted_work ON posted_work.post_id = post.id
    JOIN 
    profile ON profile.id = post.profile_id
    GROUP BY post.id DESC
    LIMIT 3";
                $queryResult = $conn->query($getPosts);

                    //While formating database results 
                    while($row = $queryResult->fetch_assoc()) {
                        
                        $post_id = $row ['post_id'];
                        $work_id = $row ['work_id'];
                        $profile_picture = $row ['profile_picture'];
                        
                        //Gets all comments for post
                        $getPostComments = "SELECT comment.id as 'comment.id', profile.name as 'commentor', comment.text as 'comment', post.id, DATE_FORMAT(comment.timestamp, '%d %b %h:%i %p') as 'timestamp'
                        FROM comment
                        JOIN profile ON profile.id = comment.profile_id
                        JOIN post ON post.id = comment.post_id
                        LEFT JOIN 
                        comment_has_like ON comment_has_like.comment_id = comment.id
                        WHERE post.id = '$post_id'";
                        $queryResult2 = $conn->query($getPostComments);
                        
                        //prints post data
                        echo "<div class='card-body'>";
                         if (filter_var($profile_picture, FILTER_VALIDATE_URL)){
                                            echo "<img src='$profile_picture' alt='$post_id' width='70px' height='70px' hspace='20' align='left'>";
                                        }
                                            else {
                                                
                                                echo "<img src='/files/profile_image.png' alt='$post_id' width='70px' height='70px' hspace='20' align='left'>";
                                            }
                                echo "<h5 class='font-weight-bold'>". $row ['name']."</h5>
                                <p class='font-weight-light' style='color:grey;'>". $row ['timestamp']."</p>
                                <br><p class='card-text'>". $row ['text']."</p>";

                                    //if there is no file with the post dont show anything
                                    if (empty($row ['file'])){
                                        echo "<br>";
                                    }
                                        //Otherwise show file 
                                        else {
                                            echo "<embed src='/files/".$row ['file']."' width='30%' height='30%'>";
                                        }

                                    //link to download file and button to like post
                                    echo "<a href= 'download.php?work_id=$work_id'><p class='card-text'>". $row ['file']."</p></a>
                          
                                <input type='image' src='/imgs/thumbs-up.png' alt='likes' style='width:30px;' class='thumbnail'>
                                <p class='card-text'>". $row ['likes']."</p>";
            
                        //link to view all comments for post
                        echo "<p><button class='btn btn-link' style='padding: 10px;'type='button' data-toggle='collapse' data-target='#$post_id' aria-expanded='false' aria-controls='collapseExample'>View Comments</button>
                        </p>
                        <div class='collapse' id='$post_id'>";
                        
                            //while getting all comment likes for a comment
                            while($row2 = $queryResult2->fetch_assoc()) {

                                $comment_id = $row2 ['comment.id'];
                                
                                $getCommentLikes = "SELECT count(comment_has_like.comment_id) as 'comment_likes'
                                FROM comment
                                JOIN 
                                comment_has_like ON comment_has_like.comment_id = comment.id
                                WHERE comment.id = '$comment_id'";
                                $queryResult3 = $conn->query($getCommentLikes);
                                
                                while($row3 = $queryResult3->fetch_assoc()) {

                                    //Prints data from db
                                    echo "<p class='font-weight-bold'>". $row2 ['commentor']."</p><p class='font-weight-light'  style='color:grey;'>". $row2 ['timestamp']."</p><p class='card-text'>". $row2 ['comment']."</p>   
                                    <input type='image' src='/imgs/thumbs-up.png' alt='likes' style='width:30px;' class='thumbnail'>
                                    <p class='card-text'>". $row3 ['comment_likes']."</p><br>";
                                    
                                }

                            }

                            echo "</div></div>";
                        }

}

//Generates post update form for user feed page
function postUpdateForm() {
    
    //Form
    echo "
    <div class='card-body'>
    <div class='form'>    
    <form action='postUpdate.php' id='form1' method='post' enctype='multipart/form-data'>
    <textarea class='form-control id='exampleFormControlTextarea1' placeholder='Post something' name='post' required></textarea>
    
    <p><button class='btn btn-link' class= 'recentWork' style='padding: 10px;'type='button' data-toggle='collapse' data-target='#postwork' aria-expanded='false' aria-controls='collapseExample' onclick='demoShow();' >Post your work</button>
    </p>
    
    <div class='collapse' id='postwork'>
    
<div class='card text-center z-depth-2 light-version py-4 px-5'>

    <div class='file-field'>
        <h4>Choose file</h4><br>
        <p>Only JPG, PNG, PDF supported</p><br>
        <input type='file' name='file'>
      </div>

  
    <br><input type='submit' class='btn btn-primary' name='submit2' value='Post update and work'></div>
    </div>
    <input type='submit' class='btn btn-primary' id='submit' name='submit' value='Post'>
    </form></div></div>";
    
}

//Generates user feed that is interactive
function getUserFeed() {   
    
    $url = parse_url("mysql://b6a9b4a53e1530:fd235082@eu-cdbr-west-02.cleardb.net/heroku_893a1d0add172e6?reconnect=true");
    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $db = substr($url["path"], 1);

    $conn = new mysqli($server, $username, $password, $db);
                
        // Checks connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
    //Query to get feed
    $getPosts = "SELECT profile.name, profile.id as 'profile_id', profile.profile_picture, post.id as 'post_id', post.text, DATE_FORMAT(post.timestamp, '%d %b %h:%i %p') as 'timestamp', count(post_has_like.post_id) as 'likes', file, posted_work.id as 'work_id'
    FROM post
    LEFT JOIN 
    post_has_like ON post_has_like.post_id = post.id
    LEFT JOIN 
    posted_work ON posted_work.post_id = post.id
    LEFT JOIN 
    profile ON profile.id = post.profile_id
    GROUP BY post.id DESC";
                $queryResult = $conn->query($getPosts);

                    //While formating database results 
                    while($row = $queryResult->fetch_assoc()) {
                        
                        $post_id = $row ['post_id'];
                        $work_id = $row ['work_id'];
                        $profile_picture = $row ['profile_picture'];
                        
                        //Gets all comments for post
                        $getPostComments = "SELECT comment.id as 'comment.id', profile.name as 'commentor', comment.text as 'comment', post.id, DATE_FORMAT(comment.timestamp, '%d %b %h:%i %p') as 'timestamp'
                        FROM comment
                        JOIN profile ON profile.id = comment.profile_id
                        JOIN post ON post.id = comment.post_id
                        LEFT JOIN 
                        comment_has_like ON comment_has_like.comment_id = comment.id
                        WHERE post.id = '$post_id'";
                        $queryResult2 = $conn->query($getPostComments);
                        
                        //prints post data
                        echo "<div class='card bg-white'>
                        <div class='card-body'>";
                        if (filter_var($profile_picture, FILTER_VALIDATE_URL)){
                                            echo "<img src='$profile_picture' alt='$post_id' width='70px' height='70px' hspace='20' align='left'>";
                                        }
                                            else {
                                                
                                                echo "<img src='/files/profile_image.png' alt='$post_id' width='70px' height='70px' hspace='20' align='left'>";
                                            }
                        echo "<h5 class='font-weight-bold'>". $row ['name']."</h5>
                        <a href='reviewPage.php?profile_id=". $row ['profile_id']."'>View Reviews</a>
                        <p class='font-weight-light' style='color:grey;'>". $row ['timestamp']."</p>
                        <p class='card-text'>". $row ['text']."</p>";
                        
                            //if there is no file with the post dont show anything
                            if (empty($row ['file'])){
                                echo "<br>";
                            }
                                //Otherwise show file 
                                else {
                                    echo "<embed src='/files/".$row ['file']."' width='30%' height='30%'>";
                                }
                        
                        //link to download file and button to like post
                        echo "<a href= 'download.php?work_id=$work_id'><p class='card-text'>". $row ['file']."</p></a>
                         
                        <div class='form'>    
                        <form id='form' action='likePost.php' method='get'>
                        <input type='hidden' name='id' value='$post_id' readonly/><br>
                        <input type='image' src='/imgs/thumbs-up.png' alt='likes' style='width:30px;' class='thumbnail'>
                        <p class='card-text'>". $row ['likes']."</p>
                        </form></div>";
            
                        //link to view all comments for post
                        echo "<p><button class='btn btn-link' style='padding: 10px;'type='button' data-toggle='collapse' data-target='#$post_id' aria-expanded='false' aria-controls='collapseExample'>View Comments</button>
                        </p><div class='collapse' id='$post_id'><br>";
                        
                            //while getting all comment likes for a comment
                            while($row2 = $queryResult2->fetch_assoc()) {

                                $comment_id = $row2 ['comment.id'];
                                
                                $getCommentLikes = "SELECT count(comment_has_like.comment_id) as 'comment_likes'
                                FROM comment
                                JOIN 
                                comment_has_like ON comment_has_like.comment_id = comment.id
                                WHERE comment.id = '$comment_id'";
                                $queryResult3 = $conn->query($getCommentLikes);
                                
                                while($row3 = $queryResult3->fetch_assoc()) {

                            //Prints data from db
                            echo "<p class='font-weight-bold'>". $row2 ['commentor']."</p><p class='font-weight-light'  style='color:grey;'>". $row2 ['timestamp']."</p><p class='card-text'>". $row2 ['comment']."</p>
                            <div class='form'>    
                            <form id='form' action='likeComment.php' method='get'>
                            <input type='hidden' name='comment_id' value='$comment_id' readonly/><br>
                            <input type='image' src='../imgs/thumbs-up.png' alt='likes' style='width:30px;' class='thumbnail'>
                            <p class='card-text'>". $row3 ['comment_likes']."</p>
                            </form></div><br>";
                                    
                                }

                            }
                        
                            //link to add comment to post form
                            echo "<p><button class='btn btn-link' type='button' data-toggle='collapse' data-target='#$post_id 2' aria-expanded='false' aria-controls='collapseExample'>Add Comment</button></p>

                                <div class='collapse' id='$post_id 2' comment'>
                                <div class='card-body'>
                                <div class='form'>    
                                <form id='form' action='postComment.php?' method='get'>
                                <input type='hidden' name='id' value='$post_id' readonly/>
                                <textarea class='form-control id='exampleFormControlTextarea1' name='cmt' placeholder='Type Comment' required></textarea></div><br>
                                <button type='submit' class='btn btn-primary'>Post comment</button>
                                </form></div>";

                            echo "</div></div></div></div>";
                        }

}

?>