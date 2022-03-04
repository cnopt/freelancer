<?php
//This script runs when user posts an update
//Jas
if(!isset($_SESSION)) { 
    session_start(); 
} 


include '../functions.php';

//Gets variables needed
$post = filter_has_var(INPUT_POST, 'post') ? $_POST['post'] : null;

//Errors variable set to false unless erorrs are found in submitted form
$errors = false; 

$timestamp = date("Y-m-d H:i:s");

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

     //Checks post if empty
     if (empty($post)) {
          echo "<br><br><br><br><div class='alert alert-warning' role='alert'>You have not entered any text</div>\n";
          $errors = true;
     } 
     //Checks review length
     if((strlen($post)>300)) {
           echo "<br><br><br><div class='alert alert-warning' role='alert'>Your post cannot be more than 300 characters long</div>\n";
           $errors = true;
     }
     //If there are any errors, redirect user to user feed
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

    
    //Status message is set to nothing
    $statusMsg = " ";

    // File upload path
    $targetDir = "../files/";
    
    //Name of file
    $fileName = basename($_FILES["file"]["name"]);
    
    //Saves file into 'files/' on server
    $targetFilePath = $targetDir . $fileName;
    
    //Gets file extension
    $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
    
    //Array of file types accepted
    $allowTypes = array('jpg','png','jpeg','pdf');
    
    //If submit2 is clicked
    if(isset($_POST["submit2"])) {
        
        if (empty($_FILES["file"]["name"]) == false) {
            
            // Check file formats
            if(in_array($fileType, $allowTypes)) {
            
                // Upload file to server
                if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
                    
                    // Insert post and file name into database
                    $insert = $conn->multi_query("BEGIN; INSERT INTO post (text, timestamp, profile_id ) VALUES('$post', '$timestamp', '$users_id'); INSERT INTO posted_work (file, post_id) VALUES('".$fileName."', LAST_INSERT_ID() ); COMMIT");
                
                //If succesful return success message
                if ($insert) {
                    
                    $statusMsg = "<br><br><br><br><div class='alert alert-primary' role='alert'>Your post has been posted</div>";
                }
            //Otherwise return upload error 
            else {
                    $statusMsg = "<br><br><br><br><div class='alert alert-warning' role='alert'>File upload failed, please try again.</div>";
                } 
            }
            //Otherwise return error message
            else {
                $statusMsg = "<br><br><br><br><div class='alert alert-warning' role='alert'>Sorry, there was an error uploading your file.</div>";
            }
        }
        //Otherwise return unsuitable file message
        else {
            $statusMsg = "<br><br><br><br><div class='alert alert-warning' role='alert'>Please upload a suitable file.</div>"; 
        }
            
    }
    //Otherwise return unsuitable file message
        else {
            $statusMsg = "<br><br><br><br><div class='alert alert-warning' role='alert'>Please upload a suitable file.</div>"; 
        }    
}
    
    //If submit button is clicked
    if(isset($_POST["submit"])) {
        
              //Extracts data to edit the record into the database 
              $addPost = "INSERT INTO post (text, timestamp, profile_id ) VALUES('$post', '$timestamp', '$users_id')";

                    //If query is successful print success message
                    if ($conn->query($addPost) === TRUE) {
                        
                        echo "<br><br><br><br><div class='alert alert-primary' role='alert'>Your post has been posted</div>";
                        header("Location: /social_media/userFeed.php");
                        
                    } 
                    //Otherwise tell user to try again and go back to user feed
                    else {
                        
                        echo "<br><br><br><br><div class='alert alert-warning' role='alert'>Your post has not been posted</div>";
                        header("Location: /social_media/userFeed.php");
                    }

    }
    //Stop mysqli query
    $conn->close();
    
    // Display status message
    echo $statusMsg;
    header("Location: /social_media/userFeed.php");
                    
}
//End webpage
echo "</body>";
echo endHtml();
?>