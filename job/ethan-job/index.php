<?php
session_start();
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

<!-- the nav bar is only a slightly modified bootstrap navbar, this makes it very responsive. contains links to all other pages of the website-->
<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">

    <a class="navbar-brand .text-warning" href="index.php">Freelancer</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item" id="browse-jobs-dropdown">
                <a class="nav-link">Browse Jobs</a>
                <ul class="dropdown-content">
                    <li class="skill"><a class="nav-item" href="#">By Skill</a>
                        <ul class="sub-dropdown-content">
                            <li><a name ='php-link'class="nav-link" href="php_jobs.php">PHP</a></li>
                            <li><a name='javascript'class="nav-link" href="javascript_jobs.php">JavaScript</a></li>
                            <li><a name='css' class="nav-link" href="css_jobs.php">CSS</a></li>
                            <li><a name='html' class="nav-link" href="html_job.php">HTML</a></li>
                            <li><a name='sql' class="nav-link" href="sql_jobs.php">SQL</a></li>
                            <li><a name='bootstrap' class="nav-link" href="bootstrap_jobs.php">Bootstrap</a></li>
                            <li><a name='js_react' class="nav-link" href="js_react_jobs.php">JS React</a></li>
                            <li><a name='graphic_design' class="nav-link" href="graphic_design_jobs.php">Graphic Design</a></li>
                            <li><a name='jquery' class="nav-link" href="jquery_jobs.php">jQuery</a></li>
                            <li><a name='gif' class="nav-link" href="git_jobs.php">GIT</a></li>
                            <li><a name='api' class="nav-link" href="api_jobs.php">API's</a></li>
                            <li><a name='chart_js' class="nav-link" href="chart_js_jobs.php">Chart JS</a></li>
                            <li><a name='java' class="nav-link" href="java_jobs.php">Java</a></li>
                            <li><a name='node.js' class="nav-link" href="node_js_jobs.php">Node.JS</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">Discover People</a>
            </li>
            <?php 
                if(isset($user_type) === 1) {
            ?>
                    <li class="nav-item">
                        <a class='nav-link' href="post_job.php">Post Job</a>
                    </li>
            <?php 
                }
            ?>
        </ul>
        <button class="btn btn-outline-warning my-2 my-sm-0" href="/user/google_login.php">Sign in with Google</button>
    </div>
</nav>
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
    /*echo getUserFeed();*/
?>
            </div>
        </div>

        <div class='col-sm-6' style='padding:1em;'>
            <h3>Jobs</h3>
            <div class='card bg-white'>
                <?php 
                echo getNotLoggedInJobs()
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


<!-- this footer contains a link to the admin form and some links to social media along with some info about the website -->
<!-- The footer displays items using a flex box to achieve responsiveness, this simple solution is adequate as the contents .  -->
<div class="footer" style="width: 100%; background-color: #868e96; margin-top: 50px;  padding-bottom: 50px; text-align: center ">
    <div class="arrow-down-dark"></div>
    <h1>Freelancer</h1> <br>
    <div class="social" style="width: 60%; margin: auto; display: flex;">
        <div class="social-icon" style="margin: auto;"><a href="https://www.facebook.com"><img src="" style="height:70px; width: 70px;"></a></div>
        <div class="social-icon" style="margin: auto;"><a href="https://www.instagram.com"><img src=""style="height:50px; width: 50px;"></a></div>
    </div>
    <form class="form" action="">
        <button class="btn btn-outline-warning my-2 my-sm-0" type="submit">Admin Features</button>
    </form>
    <br>
    <div class="FooterSeparator"></div>
    <h3>Group Name: </h3> <br>
    <h>Group members:</h> <br>
    <p> Jaskeiran Singh Deol, Charlie Biddiscombe, Ethan Roe, Alex Gibson, Matthew Randle</p><br>
    <p> Team Project and professionalism</p>
</div>
</body>
</html>