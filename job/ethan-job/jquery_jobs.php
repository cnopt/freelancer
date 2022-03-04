<?php
if(!isset($_SESSION)) {
    session_start();
}
include("apply-for-job.php");
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
<!-- these small cards direct users to key pages of the website, they display beside each other on wide screens, and on top of each other on narrow screens-->
<div class="sectionBack">
<br>
<br>
<br>
<br>
<br>
<br>    
    <h2 class style='text-align:center;'>Search for your dream job and interact with a community full of professionals</h2>
    <div class="row" style="width: 80%; margin: auto; padding-top: 20px;">
        <div class="col-sm-6" style="padding:1em;">
            <h3>Popular Updates</h3>
            <div class="card bg-white">
<?php         
    include '../../functions.php';   
    include './functions.php';   
    
        echo getPopularUpdates();
    
?>
            </div>
        </div>

        <div class='col-sm-6' style='padding:1em;'>
            <h3>jQuery Jobs</h3>
            <div class='card bg-white'>
                <?php 
                echo getJQueryJobs();
                ?>
            </div>      
        </div>
    </div>

<br>
<br>
<br> 
</div>
<br>
<br>
<br>


<?php
include "../../components/Footer.php";
?>
</body>
</html>