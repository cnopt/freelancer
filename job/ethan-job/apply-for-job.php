<?php
    if(!isset($_SESSION)) {
        session_start();
    }
    $user_id;

    if(isset($_SESSION["user_id"])) $user_id = $_SESSION["user_id"];
    

    if(isset($_POST['apply-submit'])) {
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


        $job_id = $_POST['job_id'];
        $upfront_cost = isset($_POST["upfront_cost"]) ? filter_var($_POST["upfront_cost"], FILTER_VALIDATE_FLOAT) : null;
        $upfront_cost_pence = $upfront_cost * 100;
        $total_cost = isset($_POST["total_cost"]) ? filter_var($_POST["total_cost"], FILTER_VALIDATE_FLOAT) : null;
        $total_cost_pence = $total_cost * 100;
        $message = "Please fill in both the upfront cost and total cost";
        $message2 = "This job is not open for application";

        $sql = "SELECT open 
                FROM job
                WHERE id = ?;";
        $statement = $conn->prepare($sql);
        $statement->bind_param("i", $job_id);
        $statement->execute();
        $result = $statement->get_result(); // get the mysqli result
        $row1 = $result->fetch_assoc();
        $open = $row1['open']; // fetch data
        
        if(empty($upfront_cost) || empty($total_cost)) {
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
        else if($open === 0){
            echo "<script type='text/javascript'>alert('$message2');</script>";

        } 
        else { 
            try {
                $sqlQuery = 
                "INSERT INTO job_has_applicants(user_id, job_id, timestamp, upfront_cost, total_cost)
                 VALUES((
                    SELECT id
                    FROM user
                    WHERE oauth_id = ?
                 ), ?, ?, ?, ?);";
                    
                $timestamp = time();
                $statement2 = $conn->prepare($sqlQuery);
                $statement2->bind_param("sisdd", $user_id, $job_id, $timestamp, $upfront_cost_pence, $total_cost_pence);
                $statement2->execute();
                $url = 'https://freelancerrr.herokuapp.com/index.php';
                header("Location: /");
            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
        } 
    }   
?>