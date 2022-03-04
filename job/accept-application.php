<?php 
    if(!isset($_SESSION)) { 
        session_start(); 
    } 
    
    $userID;

    if(isset($_SESSION["user_id"])) $userID = $_SESSION["user_id"];

    include "../utils/database.php";
    
    if(!isset($userID)) return header("Location: /");

    $jobHasApplicantsID = isset($_GET["jobHasApplicantsID"]) ? filter_var($_GET["jobHasApplicantsID"], FILTER_VALIDATE_INT) : null;

    if($jobHasApplicantsID == null) return header("Location: /");

    $checkIfJobAlreadyHasFreelancer = 
    "SELECT job.id, job.upfront_paid
    FROM job
    WHERE job.accepted_application = ?;";

    $stmt = $conn->prepare($checkIfJobAlreadyHasFreelancer);
    $stmt->bind_param("i", $jobHasApplicantsID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    //if the job has an accepted_application (it has a freelancer)
    if($result->num_rows > 0) {
        $jobID = (int)$row["id"];

        //if the job has NOT been paid, redirect user to payment page
        if((int)$row["upfront_paid"] === 0) {
            $stmt->close();
            $conn->close();
            return header("Location: /job/pay-upfront-cost.php?jobHasApplicantsID=" . $jobHasApplicantsID);
        }
        //the job has a freelancer and has been paid, so send them to the view active job page
        else {
            $stmt->close();
            $conn->close();
            return header("Location: /job/active-job.php?jobID=" . $jobID);
        }
    }

    $getJobID = 
    "SELECT job_id
    FROM job_has_applicants
    WHERE id = ?;";

    $stmt = $conn->prepare($getJobID);
    $stmt->bind_param("i", $jobHasApplicantsID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    //error, theres no job application with that id
    if($result->num_rows == 0) return header("Location: /");

    $jobID = (int)$row["job_id"];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="TarantulaIcon.png">
        <link rel="stylesheet" href="../stylesheets/css/Job.css">
        <link rel="stylesheet" href="../stylesheets/css/AcceptApplication.css">
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
                $getUserAndCost = 
                "SELECT user.oauth_id, upfront_cost, user.email
                FROM user
                    JOIN job ON job.employer_id = user.id
                    JOIN job_has_applicants ON job_has_applicants.job_id = job.id
                WHERE job_has_applicants.id = ?;";

                $stmt = $conn->prepare($getUserAndCost);
                $stmt->bind_param("i", $jobHasApplicantsID);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                $upfrontCost = (float)$row["upfront_cost"];

                //if the currently signed in user is NOT the user that created this job
                if($userID !== $row["oauth_id"]) {
                    $stmt->close();
                    $conn->close();
                    return header("Location: /");
                }

                //attach the freelancer to the job
                $attachFreelancerToJob = 
                "UPDATE job
                SET 
                    accepted_application = ?,
                    open = 0
                WHERE job.id = ?;";

                $stmt = $conn->prepare($attachFreelancerToJob);
                $stmt->bind_param("ii", $jobHasApplicantsID, $jobID);
                
                if(!$stmt->execute()) return header("Location: /job/view-applicants.php?jobID=" . $jobID);

                //freelancer does not require an upfront cost
                if($upfrontCost === 0) {
                    //update job (attach user and set upfront_cost to paid)
                    $markUpfrontPaid = 
                    "UPDATE job
                    SET 
                        upfront_paid = 1
                    WHERE job.id = ?;";

                    $stmt = $conn->prepare($markUpfrontPaid);
                    $stmt->bind_param("i",  $jobID);
                    
                    //job accepted, send user to view the active job
                    if($stmt->execute()) {
                        $stmt->close();
                        $conn->close();
                        return header("Location: /job/active-job.php?jobID=" . $jobID);
                    }
                    else echo "Something went wrong. Please try again.";
                }
                //let user be redirected to stripe for payment
                else {
                    $getFreelancerAndApplication = 
                    "SELECT user.email, job_has_applicants.upfront_cost, job_has_applicants.total_cost
                    FROM user
                        JOIN job_has_applicants ON job_has_applicants.user_id = user.id
                    WHERE job_has_applicants.id = ?;";

                    $stmt = $conn->prepare($getFreelancerAndApplication);
                    $stmt->bind_param("i", $jobHasApplicantsID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    $email = $row["email"];
                    $upfrontCost = $row["upfront_cost"];
                    $totalCost = $row["total_cost"];

                    $stmt->close();
                    $conn->close();
                    echo 
                    "<div class='job_acceptApplication'>
                        <h1>Application Accepted</h1>
                        <h4>Users Email: ".$email."</h4>
                        <h4>Upfront cost: ".$upfrontCost."</h4>
                        <h4>Total Cost: ".$totalCost."</h4>

                        <p>The freelancer has a required an upfront cost. For them to start work, pay this immediately.</p>
                        <a href='/job/pay-upfront-cost.php?jobHasApplicantsID=".$jobHasApplicantsID."'>Pay Upfront Cost</a>
                    </div>";
                }
            ?>
        </div>

        <?php include "../components/Footer.php" ?>
    </body>
</html>