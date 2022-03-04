<?php
    require_once("../vendor/stripe/stripe-php/init.php");
    include "../utils/database.php";

    if(!isset($_SESSION)) { 
        session_start(); 
    } 
    
    $userID;

    if(isset($_SESSION["user_id"])) $userID = $_SESSION["user_id"];

    \Stripe\Stripe::setApiKey('sk_test_j9HGU7ES5pMTEKZo99De1o5G00YfvaZI89');

    $jobID = isset($_GET["jobID"]) ? filter_var($_GET["jobID"], FILTER_VALIDATE_INT) : null;
    if(!isset($jobID)) return header("Location: /");

    $getEmployerId = 
    "SELECT user.oauth_id
    FROM job_has_applicants
        JOIN job ON job.accepted_application = job_has_applicants.id
        JOIN user ON user.id = job.employer_id
    WHERE job.id = ?;";

    $stmt = $conn->prepare($getEmployerId);
    $stmt->bind_param("i", $jobID);
    $stmt->execute();
    $result = $stmt->get_result();

    //job doesn't exist, or doesn't have accepted application
    if($result->num_rows == 0) return header("Location: /");

    $row = $result->fetch_assoc();

    //if the currently signed in user is NOT the user that created the job
    if($userID !== $row["oauth_id"]) {
        $stmt->close();
        $conn->close();
        return header("Location: /");
    }

    $getCostAndEmail = 
    "SELECT user.email, job_has_applicants.upfront_cost, job_has_applicants.total_cost, job.upfront_paid, job_has_applicants.id, job.total_paid
    FROM job_has_applicants
        JOIN user ON user.id = job_has_applicants.user_id
        JOIN job ON job.accepted_application = job_has_applicants.id
    WHERE job.id = ?;";

    $stmt = $conn->prepare($getCostAndEmail);
    $stmt->bind_param("i", $jobID);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0) return header("Location: /");

    $row = $result->fetch_assoc();
    $cost = $row["total_cost"] - $row["upfront_cost"];
    $email = $row["email"];

    //upfront cost hasn't been paid yet
    if($row["upfront_paid"] == 0) return header("Location: /job/pay-upfront-cost.php?jobHasApplicantsID=" . $row["id"]);
    if($row["total_paid"] == 1) return header("Location: /job/active-job.php?jobID=" . $jobID);

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
        'cancel_url' =>  'https://freelancerrr.herokuapp.com/job/active-job.php?jobID=' . $jobID,
    ]);

    /* Attach the session ID to the job, so that we can verify the payment */
    $updateJob = 
    "UPDATE job
    SET stripe_session_id = ?
    WHERE id = ?;";

    $stmt = $conn->prepare($updateJob);
    $stmt->bind_param("si", $session->id, $jobID);
    
    if(!$stmt->execute()) return header("Location: /job/active-job.php?jobID=" . $jobID);

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