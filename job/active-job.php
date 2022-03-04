<?php 
    if(!isset($_SESSION)) { 
        session_start(); 
    } 

    $userID;

    if(isset($_SESSION["user_id"])) $userID = $_SESSION["user_id"];

    include "../utils/database.php";
    
    if(!isset($userID)) return header("Location: /");

    $jobID = isset($_GET["jobID"]) ? filter_var($_GET["jobID"], FILTER_VALIDATE_INT) : null;

    if($jobID == null) return header("Location: /");

    $checkIfJobUpfrontPaid = 
    "SELECT job.upfront_paid, job_has_applicants.id
    FROM job
        JOIN job_has_applicants ON job_has_applicants.id = job.accepted_application
    WHERE job.id = ?;";

    $stmt = $conn->prepare($checkIfJobUpfrontPaid);
    $stmt->bind_param("i", $jobID);
    $stmt->execute();
    $result = $stmt->get_result();

    //job does not exist
    if($result->num_rows == 0) return header("Location: /");

    $row = $result->fetch_assoc();

    //if the job has NOT been paid, redirect user to payment page
    if((int)$row["upfront_paid"] === 0) {
        $stmt->close();
        $conn->close();
        return header("Location: /job/pay-upfront-cost.php?jobHasApplicantsID=" . $row["id"]);
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="TarantulaIcon.png">
        <link rel="stylesheet" href="../stylesheets/css/Job.css">
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
                //make sure the currently signed in user created this job
                $getJob = 
                "SELECT job.title, job.description, job.total_paid, job_has_applicants.total_cost, job_has_applicants.upfront_cost, user.email, profile.profile_picture
                FROM job
                    JOIN job_has_applicants ON job_has_applicants.id = job.accepted_application
                    JOIN user ON user.id = job_has_applicants.user_id
                    JOIN profile ON profile.id = user.profile_id
                WHERE job.id = ?;";

                $stmt = $conn->prepare($getJob);
                $stmt->bind_param("i", $jobID);
                $stmt->execute();
                $result = $stmt->get_result();

                if($result->num_rows == 0) {
                    echo "<h1>This job does not exist.</h1>";
                }
                else {
                    $row = $result->fetch_assoc();

                    if($row["total_paid"] == 1) {
                        echo
                        "<div>
                            <h1>".$row["title"]."</h1>
                            <p>".$row["description"]."</p>
                            <p>Upfront cost of job: ".$row["upfront_cost"]."</p>
                            <p>Total cost of job: ".$row["total_cost"]."</p>
                            <h2>Job fully paid for</h2>
                        </div>";     
                    }
                    else {
                        echo
                        "<div>
                            <h1>".$row["title"]."</h1>
                            <p>".$row["description"]."</p>
                            <p>Upfront cost of job: ".$row["upfront_cost"]."</p>
                            <p>Total cost of job: ".$row["total_cost"]."</p>
                            <h2>Remaining balance due: £".(($row["total_cost"] - $row["upfront_cost"]) / 100)."</h2>
                            <a href='/job/pay-total-cost.php?jobID=".$jobID."'>Pay remaining balance</a>
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