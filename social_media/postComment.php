<?php
//This script runs when user posts a comment
//Jas
if(!isset($_SESSION)) { 
    session_start(); 
} 

include '../functions.php';

$comment = filter_has_var(INPUT_GET, 'cmt') ? $_GET['cmt'] : null;
$post_id = filter_has_var(INPUT_GET, 'id') ? $_GET['id'] : null;

$timestamp = date("Y-m-d H:i:s");

//Errors variable set to false unless erorrs are found in submitted form
$errors = false; 

if(!isset($_SESSION["user_id"])) return header("Location: /");
echo '<head>
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
</head>';

include "../components/Navbar.php";
echo "<body>";
                    //Checks post
                    if (empty($comment)) {
                        echo "<br><br><br><br><div class='alert alert-warning' role='alert'>You have not entered a comment</div>\n";
                        $errors = true;
                    }
                    //If there are any errors, redirect user to the admin page
                    if ($errors === true) {
                        echo "<br><br><br><br><div class='alert alert-warning' role='alert'>Please try again</div>";
                        header("Location: /social_media/userFeed.php");
                    }
//If there are no errors try and connect to the database and execute queries
else {
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
    
                    $user_id = $_SESSION['user_id'];

                    $querySQL = "SELECT user.id, profile.id as 'profile.id' FROM user
                    INNER JOIN 
                    profile on profile.id = user.profile_id
                    WHERE oauth_id = '$user_id'";
    
                    $result = mysqli_query($conn, $querySQL);

                    $row = mysqli_fetch_assoc($result);

                    $users_id = $row["profile.id"];

                    //Extracts data to edit the record into the database 
                    $addComment = "INSERT INTO comment (text, timestamp, profile_id, post_id)
                    VALUES ('$comment', '$timestamp', '$users_id', $post_id )";


                    if ($conn->query($addComment) === TRUE) {
                        
                        echo "<br><br><br><br><div class='alert alert-primary' role='alert'>Your comment has been posted</div>";
                        header("Location: /social_media/userFeed.php");
                        
                    } 
                    else {
                        
                        echo "<br><br><br><br><div class='alert alert-warning' role='alert'>Please try again</div>";
                        header("Location: /social_media/userFeed.php");
                    }

                    $conn->close();
                    
                    }
echo "</body>";
echo endHtml();
?>