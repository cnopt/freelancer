<?php 
    if(!isset($_SESSION)) { 
        session_start(); 
    } 
    
    $oauthID;

    if(isset($_SESSION["user_id"])) $oauthID = $_SESSION["user_id"];
    if(!isset($oauthID)) return header("Location: /");

    include "../utils/database.php";

    $checkIfUserIsEmployer = 
    "SELECT type_id
    FROM user
    WHERE oauth_id = ?;";

    $stmt = $conn->prepare($checkIfUserIsEmployer);
    $stmt->bind_param("s", $oauthID);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0) return header("Location: /");

    $row = $result->fetch_assoc();

    if($row["type_id"] == 0) return header("Location: /");
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
            <div class="job_menu">
                <a class="job_menu--active" href="/job/view-open-jobs.php">Open Jobs</a>
                <a href="/job/view-active-jobs.php">Active Jobs</a>
                <a href="/job/view-finished-jobs.php">Finished Jobs</a>
            </div>

            <?php        
                $getJobs = 
                "SELECT job.id, job.title, job.description, count(job_has_applicants.id) as 'amountOfApplications'
                FROM job
                    LEFT JOIN job_has_applicants ON job_has_applicants.job_id = job.id
                    JOIN user ON user.id = job.employer_id
                WHERE user.oauth_id = ? AND job.open = 1
                GROUP BY job.id
                ORDER BY amountOfApplications DESC;";

                $stmt = $conn->prepare($getJobs);
                $stmt->bind_param("s", $oauthID);
                $stmt->execute();
                $result = $stmt->get_result();

                if($result->num_rows == 0) {
                    echo "<h1>You have no open jobs.</h1>";
                }
                else {
                    echo "<h1 style='margin-bottom: 30px;'>".$result->num_rows." open jobs</h1>"; 

                    while($row = $result->fetch_assoc()) {
                        if($row["amountOfApplications"] > 0) {
                            echo 
                            "<div class='job'>
                                <h2>".$row["title"]."</h2>
                                <p>".$row["description"]."</p>
                                <p>Amount of applications: ".$row["amountOfApplications"]."</p>
                                <a href='/job/view-applicants.php?jobID=".$row["id"]."'>View Applicants</a>
                            </div>";
                        }
                        else {
                            echo 
                            "<div class='job'>
                                <h2>".$row["title"]."</h2>
                                <p>".$row["description"]."</p>
                                <p>No applications yet.</p>
                            </div>";
                        }                          
                    }
                }                

                $stmt->close();
                $conn->close();
            ?>
        </div>

        <?php include "../components/Footer.php" ?>
    </body>
</html>