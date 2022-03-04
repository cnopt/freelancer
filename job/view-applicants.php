<?php 
    if(!isset($_SESSION)) { 
        session_start(); 
    } 
    
    $userID ;

    if(isset($_SESSION["user_id"])) $userID = $_SESSION["user_id"];

    if(!isset($userID)) return header("Location: /");

    $jobID = isset($_GET["jobID"]) ? filter_var($_GET["jobID"], FILTER_VALIDATE_INT) : null;

    if($jobID == null) return header("Location: /");

    include "../utils/database.php";

    $sqlQuery = 
    "SELECT user.oauth_id
    FROM user
        JOIN job ON job.employer_id = user.id
    WHERE job.id = ?;";

    $stmt = $conn->prepare($sqlQuery);
    $stmt->bind_param("i", $jobID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if($row["oauth_id"] !== $userID) return header("Location: /");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="TarantulaIcon.png">
        <link rel="stylesheet" href="../stylesheets/css/Job.css">
        <link rel="stylesheet" href="../stylesheets/css/ViewApplicants.css">
        <link rel="stylesheet" href="../stylesheets/css/Navbar.css" />

        <!-- Bootstrap CSS -->
        <!-- links the bootstrap library and assets it uses for layout -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    </head>

    <body style="display: flex; flex-direction: column; min-height: 100vh;">
        <?php include "../components/Navbar.php" ?>

        <div class="job_container" style="margin-top: 50px">
            <?php        
                $sqlQuery = 
                "SELECT user.id, user.email, user.profile_id, job.employer_id, job_has_applicants.*, profile.profile_picture
                FROM user
                    JOIN job_has_applicants ON job_has_applicants.user_id = user.id
                    JOIN job ON job.id = job_has_applicants.job_id
                    LEFT JOIN profile ON profile.id = user.profile_id
                WHERE job.id = ?;";

                $stmt = $conn->prepare($sqlQuery);
                $stmt->bind_param("i", $jobID);
                $stmt->execute();
                $result = $stmt->get_result();

                if($result->num_rows == 0) {
                    echo "<h1>This job has no applications yet.</h1>";
                }
                else {
                    while($row = $result->fetch_assoc()) {
                        echo 
                        "<div class='job_application'>
                            <div class='job_application_userSection'>
                                <div class='job_application_image'><img src='".$row["profile_picture"]."' /></div>
                                <a class='job_application_profileLink' href='/social_media/viewProfile.php?profile_id=".$row["profile_id"]."'>".$row ['email']."</a>
                                
                                <p>".gmdate("M d, Y", $row ['timestamp'])."</p>
                            </div>


                            <div class='job_application_costs'>
                                <p>Upfront Payment Required: £".((int)$row ['upfront_cost'] / 100)."</p>
                                <p>Total Payment Required: £".((int)$row ['total_cost'] / 100)."</p>
                            </div>

                            <div class='job_application_button'>
                                <a href='/job/accept-application.php?jobHasApplicantsID=".$row["id"]."'>Accept Proposal</a>
                            </div>
                        </div>";
                    }
                }                

                $stmt->close();
                $conn->close();
            ?>
        </div>

        <?php include "../components/Footer.php" ?>
    </body>
</html>