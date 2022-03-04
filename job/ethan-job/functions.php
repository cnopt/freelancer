<?php
function getAppliedForJobs() {
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
    $user_id = $_SESSION['user_id'];
    $querySQL = "SELECT user.id, profile.id as 'profile_id' 
                 FROM user
                 INNER JOIN profile on profile.id = user.profile_id
                 WHERE oauth_id = '$user_id'";

    $result = mysqli_query($conn, $querySQL);
    $row = mysqli_fetch_assoc($result);
    $users_id = $row['id'];

    $statement = 
    "SELECT title, description, job_has_applicants.job_id
    FROM user 
        JOIN job_has_applicants on job_has_applicants.user_id = user.id
        JOIN job on job_has_applicants.job_id = job.id
    WHERE user.id = ?
    GROUP BY job.id
    ORDER BY job_has_applicants.job_id DESC;";
    
    $sql = $conn->prepare($statement);
    $sql->bind_param("i", $users_id);
    $sql->execute();
    $result = $sql->get_result();

    //while formatting the results
    while($row = $result->fetch_assoc()) {        
        echo "
            <div class='already-applied'>
                <div class='card-body'>
                    <h5 class='card-title'>".$row ['title']."</h5>
                    <p class='card-text'>".$row ['description']."</p>
                    <a style='font-weight: bold'>Applied</a>
                </div>
            </div>
        ";
    }
}
    
function getAllJobs() {
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

    $user_id = $_SESSION['user_id'];
    $querySQL = "SELECT user.id, profile.id as 'profile_id' 
                 FROM user
                 INNER JOIN profile on profile.id = user.profile_id
                 WHERE oauth_id = '$user_id'";

    $result = mysqli_query($conn, $querySQL);
    $row = mysqli_fetch_assoc($result);
    $users_id = $row['id'];

    $statement = "SELECT title, description, job_has_applicants.job_id
                  FROM user 
                  JOIN job_has_applicants on job_has_applicants.user_id = user.id
                  JOIN job on job_has_applicants.job_id = job.id
                  WHERE user_id = ?
                  GROUP BY job.id
                  ORDER BY job_has_applicants.job_id DESC;";
    $sql = $conn->prepare($statement);
    $sql->bind_param("i", $users_id);
    $sql->execute();
    $result = $sql->get_result();

    $applied_for_jobs = array();
    while($row = $result->fetch_assoc()) {
        array_push($applied_for_jobs, $row['job_id']);
    }
    
    $sqlQuery = "SELECT * from job
                 GROUP BY id
                 ORDER BY id desc
                 LIMIT 50;";
    $all_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $all_jobs->fetch_assoc()) {
        $job_id = $row['id'];
        if(!in_array($job_id, $applied_for_jobs)) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
    } 
}

function getNotLoggedInJobs() {
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
    $sqlQuery = "SELECT * from job
    GROUP BY id
    ORDER BY id desc
    LIMIT 20;";
    $all_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $all_jobs->fetch_assoc()) {
    echo "
    <div class='not-applied'>
        <div class='card-body'>
            <h5 class='card-title'>".$row ['title']."</h5>
            <p class='card-text'>".$row ['description']."</p>
            <p>Login to apply</p>
        </div>
    </div>
    ";
    }
}    

function getRecommendedJobs() {
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
    $user_id = $_SESSION['user_id'];
    $querySQL = "SELECT user.id, profile.id as 'profile_id' 
                 FROM user
                 INNER JOIN profile on profile.id = user.profile_id
                 WHERE oauth_id = '$user_id'";

    $result = mysqli_query($conn, $querySQL);
    $row = mysqli_fetch_assoc($result);
    $users_id = $row['profile_id'];

    $statement = "SELECT profile.id, profile_has_skill.skill_id as profile_skill, skill, job.title, job.description, job.id
                  FROM profile 
                  JOIN profile_has_skill ON profile_has_skill.profile_id = profile.id
                  JOIN skill ON skill.id = profile_has_skill.skill_id
                  JOIN job_has_skill ON job_has_skill.skill_id = skill.id
                  JOIN job on job.id = job_has_skill.job_id
                  WHERE profile.id = ? AND job_has_skill.skill_id = profile_has_skill.skill_id
                  ORDER BY job.id ASC";
    $sql = $conn->prepare($statement);
    $sql->bind_param("i", $users_id);
    $sql->execute();
    $result = $sql->get_result();

    while($row = $result->fetch_assoc()) {
        $job_id = $row['id'];
        if(!isset($_SESSION['user_id'])) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row['title']."</h5>
                        <p class='card-text'>".$row['description']."</p>
                        <p>Log in to apply</p>
                    </div>
                </div>
            ";
        }
        else {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row['title']."</h5>
                        <p class='card-text'>".$row['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
        
    }    
}

function getPhpJobs() {
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
    
    $sqlQuery = "SELECT job.id, job.title, job.description, skill.skill
        FROM job_has_skill
        JOIN job on job.id = job_has_skill.job_id
        JOIN skill on job_has_skill.skill_id = skill.id
        WHERE skill like 'php'
        ORDER BY job_id ASC;";
    $php_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $php_jobs->fetch_assoc()) {
        $job_id = $row['id'];
        if(!isset($_SESSION['user_id'])) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <p>Log in to apply</p>
                    </div>
                </div>
            ";
        }
        else {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
        
    }    
}

function getJSJobs() {
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
    
    $sqlQuery = "SELECT job.title, job.description, skill.skill, job.id
        FROM job_has_skill
        JOIN job on job.id = job_has_skill.job_id
        JOIN skill on job_has_skill.skill_id = skill.id
        WHERE skill like 'javascript'
        ORDER BY job_id ASC;";
    $javascript_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $javascript_jobs->fetch_assoc()) {
        $job_id = $row['id'];
        if(!isset($_SESSION['user_id'])) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <p>Log in to apply</p>
                    </div>
                </div>
            ";
        }
        else {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
        
    }    
}

function getCSSJobs() {
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
    
    $sqlQuery = "SELECT job.title, job.description, skill.skill, job.id
        FROM job_has_skill
        JOIN job on job.id = job_has_skill.job_id
        JOIN skill on job_has_skill.skill_id = skill.id
        WHERE skill like 'CSS'
        ORDER BY job_id ASC;";
    $css_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $css_jobs->fetch_assoc()) {
        $job_id = $row['id'];
        if(!isset($_SESSION['user_id'])) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <p>Log in to apply</p>
                    </div>
                </div>
            ";
        }
        else {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
    }    
}

function getHTMLJobs() {
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
    
    $sqlQuery = "SELECT job.title, job.description, skill.skill, job.id
        FROM job_has_skill
        JOIN job on job.id = job_has_skill.job_id
        JOIN skill on job_has_skill.skill_id = skill.id
        WHERE skill like 'HTML'
        ORDER BY job_id ASC;";
    $html_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $html_jobs->fetch_assoc()) {
        $job_id = $row['id'];
        if(!isset($_SESSION['user_id'])) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <p>Log in to apply</p>
                    </div>
                </div>
            ";
        }
        else {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
    }    
}

function getSQLJobs() {
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
    
    $sqlQuery = "SELECT job.title, job.description, skill.skill, job.id
        FROM job_has_skill
        JOIN job on job.id = job_has_skill.job_id
        JOIN skill on job_has_skill.skill_id = skill.id
        WHERE skill like 'SQL'
        ORDER BY job_id ASC;";
    $sql_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $sql_jobs->fetch_assoc()) {
        $job_id = $row['id'];
        if(!isset($_SESSION['user_id'])) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <p>Log in to apply</p>
                    </div>
                </div>
            ";
        }
        else {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
    }    
}

function getBootstrapJobs() {
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
    
    $sqlQuery = "SELECT job.title, job.description, skill.skill, job.id
        FROM job_has_skill
        JOIN job on job.id = job_has_skill.job_id
        JOIN skill on job_has_skill.skill_id = skill.id
        WHERE skill like 'Bootstrap'
        ORDER BY job_id ASC;";
    $bootstrap_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $bootstrap_jobs->fetch_assoc()) {
        $job_id = $row['id'];
        if(!isset($_SESSION['user_id'])) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <p>Log in to apply</p>
                    </div>
                </div>
            ";
        }
        else {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
    }    
}

function getReactJobs() {
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
    
    $sqlQuery = "SELECT job.title, job.description, skill.skill, job.id
        FROM job_has_skill
        JOIN job on job.id = job_has_skill.job_id
        JOIN skill on job_has_skill.skill_id = skill.id
        WHERE skill like 'JS React'
        ORDER BY job_id ASC;";
    $js_React_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $js_react_jobs->fetch_assoc()) {
        $job_id = $row['id'];
        if(!isset($_SESSION['user_id'])) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <p>Log in to apply</p>
                    </div>
                </div>
            ";
        }
        else {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
    }    
}

function getGDJobs() {
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
    
    $sqlQuery = "SELECT job.title, job.description, skill.skill, job.id
        FROM job_has_skill
        JOIN job on job.id = job_has_skill.job_id
        JOIN skill on job_has_skill.skill_id = skill.id
        WHERE skill like 'Graphic Design'
        ORDER BY job_id ASC;";
    $graphic_design_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $graphic_design_jobs->fetch_assoc()) {
        $job_id = $row['id'];
        if(!isset($_SESSION['user_id'])) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <p>Log in to apply</p>
                    </div>
                </div>
            ";
        }
        else {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
    }    
}

function getJQueryJobs() {
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
    
    $sqlQuery = "SELECT job.title, job.description, skill.skill, job.id
        FROM job_has_skill
        JOIN job on job.id = job_has_skill.job_id
        JOIN skill on job_has_skill.skill_id = skill.id
        WHERE skill like 'jQuery'
        ORDER BY job_id ASC;";
    $jquery_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $jquery_jobs->fetch_assoc()) {
        $job_id = $row['id'];
        if(!isset($_SESSION['user_id'])) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <p>Log in to apply</p>
                    </div>
                </div>
            ";
        }
        else {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
    }    
}

function getGITJobs() {
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
    
    $sqlQuery = "SELECT job.title, job.description, skill.skill, job.id
        FROM job_has_skill
        JOIN job on job.id = job_has_skill.job_id
        JOIN skill on job_has_skill.skill_id = skill.id
        WHERE skill like 'GIT'
        ORDER BY job_id ASC;";
    $git_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $git_jobs->fetch_assoc()) {
        $job_id = $row['id'];
        if(!isset($_SESSION['user_id'])) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <p>Log in to apply</p>
                    </div>
                </div>
            ";
        }
        else {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
    }    
}

function getAPIJobs() {
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
    
    $sqlQuery = "SELECT job.title, job.description, skill.skill, job.id
        FROM job_has_skill
        JOIN job on job.id = job_has_skill.job_id
        JOIN skill on job_has_skill.skill_id = skill.id
        WHERE skill like 'APIs'
        ORDER BY job_id ASC;";
    $api_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $api_jobs->fetch_assoc()) {
        $job_id = $row['id'];
        if(!isset($_SESSION['user_id'])) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <p>Log in to apply</p>
                    </div>
                </div>
            ";
        }
        else {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
    }    
}

function getChartJobs() {
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
    
    $sqlQuery = "SELECT job.title, job.description, skill.skill, job.id
        FROM job_has_skill
        JOIN job on job.id = job_has_skill.job_id
        JOIN skill on job_has_skill.skill_id = skill.id
        WHERE skill like 'Chart JS'
        ORDER BY job_id ASC;";
    $chart_js_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $chart_js_jobs->fetch_assoc()) {
        $job_id = $row['id'];
        if(!isset($_SESSION['user_id'])) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <p>Log in to apply</p>
                    </div>
                </div>
            ";
        }
        else {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
    }    
}

function getJavaJobs() {
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
    
    $sqlQuery = "SELECT job.title, job.description, skill.skill, job.id
        FROM job_has_skill
        JOIN job on job.id = job_has_skill.job_id
        JOIN skill on job_has_skill.skill_id = skill.id
        WHERE skill like 'Java'
        ORDER BY job_id ASC;";
    $java_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $java_jobs->fetch_assoc()) {
        $job_id = $row['id'];
        if(!isset($_SESSION['user_id'])) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <p>Log in to apply</p>
                    </div>
                </div>
            ";
        }
        else {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
    }    
}

function getNodeJobs() {
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
    
    $sqlQuery = "SELECT job.title, job.description, skill.skill, job.id
        FROM job_has_skill
        JOIN job on job.id = job_has_skill.job_id
        JOIN skill on job_has_skill.skill_id = skill.id
        WHERE skill like 'Node.JS'
        ORDER BY job_id ASC;";
    $node_js_jobs = $conn->query($sqlQuery);

    //while formatting the results
    while($row = $node_js_jobs->fetch_assoc()) {
        $job_id = $row['id'];
        if(!isset($_SESSION['user_id'])) {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <p>Log in to apply</p>
                    </div>
                </div>
            ";
        }
        else {
            echo "
                <div class='not-applied'>
                    <div class='card-body'>
                        <h5 class='card-title'>".$row ['title']."</h5>
                        <p class='card-text'>".$row ['description']."</p>
                        <a name='open-apply-form' id='open-apply-form' class='open-apply-form'>Apply</a>
                        <div class='apply-form-container'>
                            <form method='post' action='/job/ethan-job/apply-for-job.php' id='apply-form'>
                                <input type='text' name='upfront_cost' placeholder='Upfront Cost in £'/>
                                <input type='text' name='total_cost' placeholder='Total Cost in £'/>
                                <input type='hidden' id='job_id' name='job_id' value=$job_id>
                                <input type='submit' class='apply-submit' name='apply-submit' id='apply-submit' value='Submit'>
                            </form>
                        </div>
                    </div>
                </div>
            ";
        }
    }    
}
?>