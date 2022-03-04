<?php
if(!isset($_SESSION)) {
    session_start();
}
$user_id;
if(isset($_SESSION["user_id"])) $user_id = $_SESSION["user_id"];

if(isset($_SESSION['type_id'])) {
    $user_type = $_SESSION['type_id'];
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

//get skills for dropdown
$sql = "SELECT * FROM skill";
$dropdown = $conn->prepare($sql);
$dropdown->execute();
$skills = $dropdown->get_result();
    if(isset($_REQUEST['submit'])) {
        $open = '1';
        $upfront_paid = '0';
        $total_paid = '0';
        $title = $_REQUEST['title'];
        $description = $_REQUEST['description'];
        $message = "Please enter a job title and description and choose a skill";
        $skill_id = $_REQUEST['skills'];

        if(empty($title) || empty($description) || empty($skill_id)) {
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
        else { 
            try {
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
                $sql = "INSERT INTO job(title, description, open, employer_id, upfront_paid, total_paid)
                        VALUES(
                            ?, ?, ?, 
                            (
                                SELECT id
                                FROM user
                                WHERE oauth_id = ?
                            ), ?, ?)";
                    
                $stmt = $conn->prepare($sql);
                if (false===$stmt) {
                    die('prepare() failed: ' . htmlspecialchars($conn->error));
                }
                $rc = $stmt->bind_param("ssisii", $title, $description, $open, $user_id, $upfront_paid, $total_paid);
                if(false===$rc) {
                    die('bind_param() failed: ' . htmlspecialchars($stmt->error));
                }
                $rc = $stmt->execute();
                if(false===$rc) {
                    die('execute() failed: ' . htmlspecialchars($stmt->error));
                }  

                $sql = "SELECT job.id from job ORDER BY job.id DESC LIMIT 1";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $inserted_job_id = $row['id'];

                $sql1 = "INSERT INTO job_has_skill(job_id, skill_id)
                         VALUES(?, ?)";
                $stmt2 = $conn->prepare($sql1);
                if (false===$stmt2) {
                    die('prepare() failed: ' . htmlspecialchars($conn->error));
                }
                $rc1 = $stmt2->bind_param("ii", $inserted_job_id, $skill_id);  
                if(false===$rc1) {
                    die('bind_param() failed: ' . htmlspecialchars($stmt2->error));
                }
                $rc1 = $stmt2->execute();       
                if(false===$rc1) {
                    die('execute() failed: ' . htmlspecialchars($stmt2->error));
                } 
                echo "<script type='text/javascript'>alert('Successfully uploaded job!');</script>";
            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
        } 
    }

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="TarantulaIcon.png">

    <!-- Bootstrap CSS -->
    <!-- links the bootstrap library and assets it uses for layout -->
    <link rel="stylesheet" href="../../stylesheets/css/Navbar.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="index.js"></script>

</head>
<body>
<?php
include "../../components/Navbar.php";
?>
<div class="sectionBack">
<br>
<br>
<br>
<br>
<br>
<div class='post-job-form-container'>
<h3>Add a job into our database so that potential employees can apply!</h3>
    <form method="post" action="post_job.php" class="post-job-form">
        <label>Job Title</label>
        <input type="text" name="title"/>
        <label>Job Description</label>
        <input type="text" name="description"/>
        <label>Skill required</label>
        <select name="skills" id="skills" class="skills">
            <option disabled selected value>select an option</option>
                <?php foreach ($skills as $skill): ?>
                    <option value="<?php echo $skill['id'] ?>"><?php echo $skill['skill']?></option>
                <?php endforeach ?> 
        </select>
        <input type="submit" name="submit" value="Post Job"/>
    </form>
</div>
<br>
<br>
<br> 
</div>
<br>
<br>
<br>
<br>    



<?php
include "../../components/Footer.php";
?>
</body>
</html>