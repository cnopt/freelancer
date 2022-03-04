<?php
    require_once "functions.php";
    require_once "./job/ethan-job/functions.php";
?>

<!DOCTYPE html>
<html>
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
        <?php include "./components/Navbar.php" ?>

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
                <h3>Recent Updates</h3>
                <div class="card bg-white">
        <?php                 
            echo getPopularUpdates();
        ?>
                </div>
            </div>
            <div class="col-sm-6" style="padding:1em;">
            <?php
            if(!isset($_SESSION['user_id'])) {
            ?>
                <h3>New jobs</h3>
                <div class="card bg-white">
                    <div class="card-body">
                        <?php
                            echo getNotLoggedInJobs();
                        ?>
                    </div>
                </div>
            <?php
            } else {
            ?>
                <h3>Jobs Applied For</h3>
                <div class="card bg-white">
                    <div class="card-body">
                        <?php
                            echo getAppliedForJobs();
                        ?>
                    </div>
                </div>

                <h3>Recommended Jobs</h3>
                <div class="card bg-white">
                    <div class="card-body">
                        <?php
                            echo getRecommendedJobs();
                        ?>
                    </div>
                </div>

                <h3>All Jobs</h3>
                <div class="card bg-white">
                    <div class="card-body">
                        <?php
                            echo getAllJobs();
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>
            </div>
        </div>
    <br>
    <br>
    <br> 
    </div>
    <br>
    <br>
    <br>
    <script type='application/javascript'>
    $(document).ready(function() {
        $("div.apply-form-container").hide(); //hide application forms
        $(".open-apply-form").click(function() {
        // on click...
        $("div.apply-form-container").eq($(this).index(".open-apply-form")) //select correct form
            .toggle(); //and show/hide it
        })
    });
    </script>

    <?php include "./components/Footer.php"; ?>

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

            #open-apply-form {
                cursor: pointer;
            }

            #apply-form {
                display: flex;
                flex-wrap: wrap;
            }

            .apply-form-container {
                margin-top: 10px;
            }

            #apply-form input {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                display: block;
                margin-bottom: 10px;
                width: 75%;
            }

            #apply-form input{
                border: 1px solid gray;
                padding: 5px 0;
                padding-left: 5px;
            }

            .already-applied {
                border-bottom: 10px solid #ebebeb;
            }

            .not-applied {
                border-bottom: 10px solid #ebebeb;
            }
        </style>
    </body>
</html>