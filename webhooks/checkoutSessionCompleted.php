<?php
    require_once("../vendor/stripe/stripe-php/init.php");
    \Stripe\Stripe::setApiKey('sk_test_j9HGU7ES5pMTEKZo99De1o5G00YfvaZI89');

    $endpoint_secret = "whsec_boJ7Bx6g67DSdu9eQcoPiz61BYZWxYkA";
    //$endpoint_secret = "whsec_5ppI6tvs9ACupOKyOSAhCwdp6CvY5Tu1";

    $payload = @file_get_contents('php://input');
    $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
    $event = null;

    try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
    } catch(\UnexpectedValueException $e) {
        // Invalid payload
        http_response_code(400);
        exit();
    } catch(\Stripe\Exception\SignatureVerificationException $e) {
        // Invalid signature
        http_response_code(400);
        exit();
    }

    // Handle the checkout.session.completed event
    if ($event->type == 'checkout.session.completed') {
        $session = $event->data->object;

        $sessionID = $session->id;
        $cost = $session->display_items[0]->amount;

        include "../utils/database.php";

        $getJobID = 
        "SELECT id
        FROM job
        WHERE stripe_session_id = ?;";

        $stmt = $conn->prepare($getJobID);
        $stmt->bind_param("s", $sessionID);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 0) return http_response_code(404);

        $row = $result->fetch_assoc();
        $jobID = $row["id"];

        $checkIfUpfrontOrTotalPayment =
        "SELECT upfront_paid
        FROM job
        WHERE stripe_session_id = ?;";

        $stmt = $conn->prepare($checkIfUpfrontOrTotalPayment);
        $stmt->bind_param("s", $sessionID);
        $stmt->execute();
        $result = $stmt->get_result();

        //there is no job with that sessionID
        if($result->num_rows == 0) return http_response_code(404);

        $row = $result->fetch_assoc();

        //this payment is for the upfront payment
        if((int)$row["upfront_paid"] == 0) {
            $updateUsersBalanceAndUpfrontPaid = 
            "UPDATE user
                JOIN job_has_applicants ON job_has_applicants.user_id = user.id
                JOIN job ON job.accepted_application = job_has_applicants.id
            SET 
                balance = balance + ?,
                upfront_paid = 1
            WHERE job.id = ?;";
        
            $stmt = $conn->prepare($updateUsersBalanceAndUpfrontPaid);
            $stmt->bind_param("di", $cost, $jobID);
            $stmt->execute();
        }
        //payment is for the final payment
        else {
            $updateUsersBalanceAndFinalPaid = 
            "UPDATE user
                JOIN job_has_applicants ON job_has_applicants.user_id = user.id
                JOIN job ON job.accepted_application = job_has_applicants.id
            SET 
                balance = balance + ?,
                total_paid = 1
            WHERE job.id = ?;";

            $stmt = $conn->prepare($updateUsersBalanceAndFinalPaid);
            $stmt->bind_param("di", $cost, $jobID);
            $stmt->execute();
        }

        $stmt->close();
        $conn->close();
    }

    http_response_code(200);
?>