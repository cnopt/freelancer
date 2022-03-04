<?php
//Review page for website
//Jas
if(!isset($_SESSION)) { 
    session_start(); 
} 

//Adds header function
include '../functions.php';
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="TarantulaIcon.png">
    <link rel="stylesheet" href="../stylesheets/css/Navbar.css" />

    <!-- Bootstrap CSS -->
    <!-- links the bootstrap library and assets it uses for layout -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
</head>

<body>
<?php include "../components/Navbar.php"; ?>
<!-- Starts section container of page-->
<div class="sectionBack">  
<br>
<br>
<br>
<br>
    <!-- Starts row container of page-->
    <div class="row" style="width: 80%; margin: auto; padding-top: 20px;">
        <div class="col-sm-6" style="padding:1em;">
            <!-- Link back to user feed-->
            <a class='btn btn-secondary' href='/social_media/userFeed.php' role='button'>Back to the feed</a><br><br>
<?php
            
//Gets profile_id of account that has been selected
$profile_id = filter_has_var(INPUT_GET, 'profile_id') ? $_GET['profile_id'] : null;

//Variables for db           
$url = parse_url("mysql://b6a9b4a53e1530:fd235082@eu-cdbr-west-02.cleardb.net/heroku_893a1d0add172e6?reconnect=true");
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);
            
//Starts connection to db 
$conn = new mysqli($server, $username, $password, $db);
                
            // Checks connection
            if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
            }         
            
            //Starts query for database to get profile name  
            $getProfileName = "SELECT name
            FROM profile
            WHERE id = '$profile_id'";
            
            $queryResult = $conn->query($getProfileName);
                while($row = $queryResult->fetch_assoc()) {
                    
                    //Return's profile name
                    echo "<h3 class='font-weight-bold'>". $row ['name']."'s Review's</h3><br>";
                }
            //Starts query for database to get current reviews for profile
            $getReviews = "SELECT profile.name as 'reviewer_name', review.rating, review.text
            FROM review
            JOIN profile ON profile.id = review.reviewer_id
            WHERE profile_id = '$profile_id'";
            
                $queryResult = $conn->query($getReviews);

                        //While formating database results 
                        while($row = $queryResult->fetch_assoc()) {
                         
                        //Sets stars based on rating    
                        if ($row ['rating'] == 1) $star = "<input type='image' src='../imgs/star.svg.png' alt='rating' style='width:30px;' class='thumbnail'>";
                            
                        if ($row ['rating'] == 2) $star = "<input type='image' src='../imgs/star.svg.png' alt='rating' style='width:30px;' class='thumbnail'>
                        
                        <input type='image' src='../imgs/star.svg.png' alt='rating' style='width:30px;' class='thumbnail'>";
                        if ($row ['rating'] == 3) $star = "<input type='image' src='../imgs/star.svg.png' alt='rating' style='width:30px;' class='thumbnail'>
                        
                        <input type='image' src='../imgs/star.svg.png' alt='rating' style='width:30px;' class='thumbnail'>
                        
                        <input type='image' src='../imgs/star.svg.png' alt='rating' style='width:30px;' class='thumbnail'>";
                        if ($row ['rating'] == 4) $star = "<input type='image' src='../imgs/star.svg.png' alt='rating' style='width:30px;' class='thumbnail'>
                        
                        <input type='image' src='../imgs/star.svg.png' alt='rating' style='width:30px;' class='thumbnail'>
                        
                        <input type='image' src='../imgs/star.svg.png' alt='rating' style='width:30px;' class='thumbnail'>
                        
                        <input type='image' src='../imgs/star.svg.png' alt='rating' style='width:30px;' class='thumbnail'>"; 
                        if ($row ['rating'] == 5) $star = "<input type='image' src='../imgs/star.svg.png' alt='rating' style='width:30px;' class='thumbnail'>
                        
                        <input type='image' src='../imgs/star.svg.png' alt='rating' style='width:30px;' class='thumbnail'>
                        
                        <input type='image' src='../imgs/star.svg.png' alt='rating' style='width:30px;' class='thumbnail'>
                        
                        <input type='image' src='../imgs/star.svg.png' alt='rating' style='width:30px;' class='thumbnail'>
                        
                        <input type='image' src='../imgs/star.svg.png' alt='rating' style='width:30px;' class='thumbnail'>";     
                            
                            //Returns review results
                            echo "<div class='card bg-white'>
                            <div class='card-body'>";
                                echo $star;
                                echo "<p class='card-text'>Reviewer: ". $row ['reviewer_name']."</p>
                                <p class='font-weight-light' style='color:grey;'>". $row ['text']."</p>
                                </div></div><br>";

                        }
            
//Collapse button that links to add review form            
echo "<p><button class='btn btn-outline-primary btn-sm' type='button' data-toggle='collapse' data-target='#$profile_id' aria-expanded='false' aria-controls='collapseExample'>Add Review</button>
</p>";

//Add review form            
echo "<div class='collapse' id='$profile_id' comment'>
<div class='form'>    
<form id='form' action='postReview.php?profile_id=$profile_id' method='get'>
                            
<br><label><p class='font-weight-bold'>Give a Rating</p></label><br>
<div class='form-check form-check-inline'>       
  <input class='form-check-input' type='radio' name='rating' id='inlineRadio1' value='1'>
  <label class='form-check-label' for='inlineRadio1'>1</label>
</div>
<div class='form-check form-check-inline'>
  <input class='form-check-input' type='radio' name='rating' id='inlineRadio2' value='2'>
  <label class='form-check-label' for='inlineRadio2'>2</label>
</div>
<div class='form-check form-check-inline'>
  <input class='form-check-input' type='radio' name='rating' id='inlineRadio3' value='3'>
  <label class='form-check-label' for='inlineRadio3'>3</label>
</div>
<div class='form-check form-check-inline'>
  <input class='form-check-input' type='radio' name='rating' id='inlineRadio4' value='4'>
  <label class='form-check-label' for='inlineRadio4'>4</label>
</div>
<div class='form-check form-check-inline'>
  <input class='form-check-input' type='radio' name='rating' id='inlineRadio5' value='5'>
  <label class='form-check-label' for='inlineRadio5'>5</label>
</div><br>

<br><label><p class='font-weight-bold'>Give a Review</p></label>
<textarea class='form-control id='exampleFormControlTextarea1' name='review' placeholder='Type review' required></textarea></div><br>
<input type='hidden' name='profile_id' value='$profile_id'/>
<button type='submit' class='btn btn-primary'>Post Review</button>
</form>
</div></div>";               
?>       
        </div>
    </div>
<br> 
</div>
<br>
<br>
<br>


<?php include "../components/Footer.php"; ?>
    
</body>
<?php
echo endHtml();
?>

<!-- home page style -->
    <style>
        /* background colour of dark grey */
        body {
            background-color: #ebebeb;
        }

        /* style for footer */
        .footer a {
            color: black;
        }

        .social-icon{
            filter: grayscale(100%);
            padding-bottom: 30px;
        }

        .social-icon:hover {
            filter: grayscale(0%);
        }

        /* style for section seperators */

        .outer{
            margin-left: auto;
            margin-right: auto;
            width: 60%;
            border: black;
        }

        .card{
            border: none!important;  /*important is used to override bootstrap settings*/
            margin: 10px;
        }

        /* cantainer is the conatiner for the dymnaic display element */
        .container{
            width: 70%;
        }

        /* styling for the layout shifter element at the bottom of the homepage.*/
        .responsiveDisplay{
            box-sizing:border-box;
            display: flex;
            margin: auto;
            width: 60%;
        }

        /* image icons within the layout shifter display.*/
        .ResImg {
            width: 80%;
            margin: 10px;
            max-height: 200px;
        }

        /* setting the breakpoints for devices 700px for phones, 1200 for tables and anything other gets the full horizontal display.*/
        /* using different flex display settings to specify diffirent layouts on screen sizes .*/
        @media screen and (max-width:1200px) {
            .responsiveDisplay{
                flex-wrap: wrap;
                width: 80%;
            }
            .ResImg{
                width: 30%;
            }
            .displayHead{
                font-size: small;
            }
        }

        @media screen and (max-width:700px) {
            .responsiveDisplay{
                flex-wrap: wrap;
                width: 90%;
            }

        }
    </style>