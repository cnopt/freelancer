<?php
    require_once("../vendor/stripe/stripe-php/init.php");
    include "../utils/database.php";
    
    if(!isset($_SESSION)) { 
        session_start(); 
    } 
    
    $userID;

    if(isset($_SESSION["user_id"])) $userID = $_SESSION["user_id"];

    \Stripe\Stripe::setApiKey('sk_test_j9HGU7ES5pMTEKZo99De1o5G00YfvaZI89');

    $jobHasApplicantsID = isset($_GET["jobHasApplicantsID"]) ? filter_var($_GET["jobHasApplicantsID"], FILTER_VALIDATE_INT) : null;
    if(!isset($jobHasApplicantsID)) return header("Location: /");

    $getEmployerId = 
    "SELECT user.oauth_id
    FROM job_has_applicants
        JOIN job ON job.id = job_has_applicants.job_id
        JOIN user ON user.id = job.employer_id
    WHERE job_has_applicants.id = ?;";

    $stmt = $conn->prepare($getEmployerId);
    $stmt->bind_param("i", $jobHasApplicantsID);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0) return header("Location: /");

    $row = $result->fetch_assoc();

    //if the currently signed in user is NOT the user that created the job
    if($userID !== $row["oauth_id"]) {
        $stmt->close();
        $conn->close();
        return header("Location: /");
    }

    $getCostAndEmail = 
    "SELECT user.email, job_has_applicants.upfront_cost
    FROM job_has_applicants
        JOIN user ON user.id = job_has_applicants.user_id
    WHERE job_has_applicants.id = ?;";

    $stmt = $conn->prepare($getCostAndEmail);
    $stmt->bind_param("i", $jobHasApplicantsID);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0) return header("Location: /");

    $row = $result->fetch_assoc();
    $cost = $row["upfront_cost"];
    $email = $row["email"];

    /* Check that the job has a user (freelancer attached to it), if not we don't want to be able to pay for nothing */
    $checkIfJobHasFreelancerAndNotPaid = 
    "SELECT job.id, job.upfront_paid
    FROM job
    WHERE job.accepted_application = ?;";

    $stmt = $conn->prepare($checkIfJobHasFreelancerAndNotPaid);
    $stmt->bind_param("i", $jobHasApplicantsID);
    $stmt->execute();
    $result = $stmt->get_result();

    //the job doesn't exist
    if($result->num_rows == 0) return header("Location: /");
        
    $row = $result->fetch_assoc();

    //this application does not belong to a job
    if($row["id"] == null) {
        $stmt->close();
        $conn->close();
        return header("Location: /job/accept-application.php?jobHasApplicantsID=" . $jobHasApplicantsID);
    }

    $jobID = $row["id"];

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'name' => 'Pay ' . $email,
            'description' => 'Freelance work',
            'amount' => $cost,
            'currency' => 'gbp',
            'quantity' => 1,
        ]],
        'success_url' => 'https://freelancerrr.herokuapp.com/job/payment-success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' =>  'https://freelancerrr.herokuapp.com/job/accept-application.php?jobHasApplicantsID=' . $jobHasApplicantsID,
    ]);

    /* Attach the session ID to the job, so that we can verify the payment */
    $updateJob = 
    "UPDATE job
    SET stripe_session_id = ?
    WHERE id = ?;";

    $stmt = $conn->prepare($updateJob);
    $stmt->bind_param("si", $session->id, $jobID);
    
    if(!$stmt->execute()) return header("Location: /job/accept-application.php?jobHasApplicantsID=" . $jobHasApplicantsID);

    $stmt->close();
    $conn->close();
?>

<!DOCTYPE html>
<html>
    <body>
        <h1 id="header">Redirecting to Stripe...</h1>

        <script src="https://js.stripe.com/v3/"></script>
        <script>
            var sessionId = "<?php echo $session->id; ?>";

            var stripe = Stripe('pk_test_qFTAshDqWvsxSKJGJQu1c6Uq00cl6p80Ql');

            stripe.redirectToCheckout({ sessionId })
            .then(function (result) {
                document.getElementById("header").innerText = result.error.message;
            });
        </script>
    </body>
</html>