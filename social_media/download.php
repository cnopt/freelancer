<?php
//Script that downloads file uploads in user feed
//Jas
if(!isset($_SESSION)) { 
    session_start(); 
} 

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

if (isset($_GET['work_id'])) {
    $work_id = $_GET['work_id'];

    // fetch file to download from database
    $sql = "SELECT * FROM posted_work WHERE id= $work_id";
    $result = mysqli_query($conn, $sql);

    $file = mysqli_fetch_assoc($result);
    $filepath = '../files/' . $file['file'];

    if (file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filepath));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('../files/' . $file['file']));
        readfile('../files/' . $file['file']);
        exit;
    }
    else {
        
        include '../functions.php';
        include "../components/Navbar.php";
        echo "<body>";
        echo "<br><br><br><h3>Sorry, File could not be found</h3>";
        header("Location: /social_media/userFeed.php");
        echo "</body>";
        include "../components/Footer.php";
        echo endHtml();
            
    }

}

?>