<?php
if(!isset($_SESSION)) { 
    session_start(); 
} 
    require_once "../functions.php";
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="TarantulaIcon.png">
    <link rel="stylesheet" href="../stylesheets/css/Navbar.css" />

    <!-- Bootstrap CSS -->
    <!-- links the bootstrap library and assets it uses for layout -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
</head>

<body>
<?php include "../components/Navbar.php"; ?>

<!-- these small cards direct users to key pages of the website, they display beside each other on wide screens, and on top of each other on narrow screens-->
<div class="sectionBack">
<br>
<br>
<br>
<br>
<br>
<br>    
    <h2 class style='text-align:center;'>Explore Freelancers and Employees</h2>
    <div class="row" style="width: 80%; margin: auto; padding-top: 20px;">
        <div class="col-sm-10" style="padding:1em;">
            <div class="card bg-white">
                    <?php
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

                    //Query to get feed
                    $getPosts = "SELECT * from profile";
                                $queryResult = $conn->query($getPosts);

                                    //While formating database results 
                                    while($row = $queryResult->fetch_assoc()) {
                        
                                        $id = $row ['id'];
                                        $name = $row ['name'];
                                        $interests = $row ['interests'];
                                        
                                        echo "<div class='card-body'>";
                                        echo "<h2>$name</h2>";
                                        echo "<a href='/social_media/viewProfile.php?profile_id=$id'>View profile </a>";
                                        echo "<p class = lead>$interests</p>";
                                        echo "</div>";
                                    }
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

<?php include "../components/Footer.php"; ?>

<!-- home page style -->
    <style>
        /* background colour of dark grey */
        body {background-color: #ebebeb;}

        /* style for footer */
        .footer a {
            color: black;
        }

        .social-icon{
            filter: grayscale(100%);
            padding-bottom: 30px;
        }

        .social-icon:hover {
            filter: grayscale(0%);
        }

        /* style for section seperators */

        .outer{
            margin-left: auto;
            margin-right: auto;
            width: 60%;
            border: black;
        }

        .card{
            border: none!important;  /*important is used to override bootstrap settings*/
            margin: 10px;
        }

        /* cantainer is the conatiner for the dymnaic display element */
        .container{
            width: 70%;
        }

        /* styling for the layout shifter element at the bottom of the homepage.*/
        .responsiveDisplay{
            box-sizing:border-box;
            display: flex;
            margin: auto;
            width: 60%;
        }
        /* each display is the elements for the layout shifter.*/
        .Display1{
            background-color: #868e96;
            border-radius: 10px;
            text-align: center;
            margin: 20px;
            box-sizing:border-box;
            padding:20px;
            float:left;
            width:30%; /* The width is 20%, by default */
            transition: box-shadow 0.2s linear;
        }

        .Display2 {
            background-color: #868e96;
            border-radius: 10px;
            text-align: center;
            margin: 20px;
            box-sizing:border-box;
            padding:20px;
            float:left;
            width:40%; /* The width is 60%, by default */
            transition: box-shadow 0.2s linear;
        }

        .Display3 {
            background-color: #868e96;
            border-radius: 10px;
            text-align: center;
            margin: 20px;
            box-sizing:border-box;
            padding:20px;
            float:left;
            width:30%;
            transition: box-shadow 0.2s linear;
        }

        /* image icons within the layout shifter display.*/
        .ResImg {
            width: 80%;
            margin: 10px;
            max-height: 200px;
        }

        /* setting the breakpoints for devices 700px for phones, 1200 for tables and anything other gets the full horizontal display.*/
        /* using different flex display settings to specify diffirent layouts on screen sizes .*/
        @media screen and (max-width:1200px) {
            .responsiveDisplay{
                flex-wrap: wrap;
                width: 80%;
            }
            .Display1{
                width:40%;
                order: 2;
            }
            .Display2{
                width:40%;
                order: 1;
            }
            .Display3{
                width:85%;
                order: 3;
            }
            .ResImg{
                width: 30%;
            }
            .displayHead{
                font-size: small;
            }
        }

        @media screen and (max-width:700px) {
            .responsiveDisplay{
                flex-wrap: wrap;
                width: 90%;
            }
            .Display1{
                width:100%;
                order: 1;
            }
            .Display2{
                width:100%;
                order: 2;
            }
            .Display3{
                width:100%;
                order: 3;
            }

        }
    </style>
</body>