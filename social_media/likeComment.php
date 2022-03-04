<?php
//This script runs when user likes a comment
//Jas
if(!isset($_SESSION)) { 
    session_start(); 
} 

include '../functions.php';

$comment_id = filter_has_var(INPUT_GET, 'comment_id') ? $_GET['comment_id'] : null;

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

echo "<body>";
include "../components/Navbar.php";

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
            $addCommentLike = "INSERT INTO comment_has_like (comment_id, profile_id)
            VALUES ('$comment_id', '$users_id' )";


                    if ($conn->query($addCommentLike) === TRUE) {
                        
                        echo "<br><br><br><br><div class='alert alert-primary' role='alert'>You have liked the comment</div>";
                        header("Location: /social_media/userFeed.php");
                        
                    } 
                    else {
                        
                        echo "<br><br><br><br><div class='alert alert-warning' role='alert'>You have already liked the comment</div>";
                        header("Location: /social_media/userFeed.php");
                    }

                    $conn->close();
                    
echo "</body>";
echo endHtml();
?>