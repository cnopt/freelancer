<?php
    if(!isset($_SESSION)) { 
        session_start(); 
    } 

    $user_type;
    $user_id;
    if(isset($_SESSION["type_id"])) $user_type = $_SESSION["type_id"];
    if(isset($_SESSION["user_id"])) $user_id = $_SESSION["user_id"];
?>

<!-- the nav bar is only a slightly modified bootstrap navbar, this makes it very responsive. contains links to all other pages of the website-->
<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand .text-warning" href="/index.php">Freelancer</a>

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
                            <li><a name ='php-link'class="nav-link" href="/job/ethan-job/php_jobs.php">PHP</a></li>
                            <li><a name='javascript'class="nav-link" href="/job/ethan-job/javascript_jobs.php">JavaScript</a></li>
                            <li><a name='css' class="nav-link" href="/job/ethan-job/css_jobs.php">CSS</a></li>
                            <li><a name='html' class="nav-link" href="/job/ethan-job/html_job.php">HTML</a></li>
                            <li><a name='sql' class="nav-link" href="/job/ethan-job/sql_jobs.php">SQL</a></li>
                            <li><a name='bootstrap' class="nav-link" href="/job/ethan-job/bootstrap_jobs.php">Bootstrap</a></li>
                            <li><a name='js_react' class="nav-link" href="/job/ethan-job/js_react_jobs.php">JS React</a></li>
                            <li><a name='graphic_design' class="nav-link" href="/job/ethan-job/graphic_design_jobs.php">Graphic Design</a></li>
                            <li><a name='jquery' class="nav-link" href="/job/ethan-job/jquery_jobs.php">jQuery</a></li>
                            <li><a name='gif' class="nav-link" href="/job/ethan-job/git_jobs.php">GIT</a></li>
                            <li><a name='api' class="nav-link" href="/job/ethan-job/api_jobs.php">API's</a></li>
                            <li><a name='chart_js' class="nav-link" href="/job/ethan-job/chart_js_jobs.php">Chart JS</a></li>
                            <li><a name='java' class="nav-link" href="/job/ethan-job/java_jobs.php">Java</a></li>
                            <li><a name='node.js' class="nav-link" href="/job/ethan-job/node_js_jobs.php">Node.JS</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/social_media/userList.php">Discover People</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/social_media/userFeed.php">Social Feed</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/statistics/">Statistics</a>
            </li>
            <?php 
                if(isset($user_type)) {
                    if($user_type == 1) {                
                    ?>
                        <li class="nav-item">
                            <a class='nav-link' href="../job/ethan-job/post_job.php">Post Job</a>
                        </li>

                        <li class="nav-item">
                            <a class='nav-link' href="../job/view-open-jobs.php">View Your Jobs</a>
                        </li>
                    <?php 
                    }
                }
            ?>
        </ul>
        
        <?php
            if(!isset($user_id)) {
                echo "<a class='btn btn-outline-warning my-2 my-sm-0' href='/user/login_process.php'>Sign in with Google</a>";
            }
            else {
                echo "<a class='btn btn-outline-warning my-2 my-sm-0' href='/user/logout.php'>Sign Out</a>";
            }
        ?>
    </div>
</nav>