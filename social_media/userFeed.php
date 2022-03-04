<?php
    session_start();
    include '../functions.php';
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="TarantulaIcon.png">
    <link rel="stylesheet" href="../stylesheets/css/Job.css">
    <link rel="stylesheet" href="../stylesheets/css/AcceptApplication.css">
    <link rel="stylesheet" href="../stylesheets/css/Navbar.css" />

    <!-- Bootstrap CSS -->
    <!-- links the bootstrap library and assets it uses for layout -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
</head>

<body>
    <?php include "../components/Navbar.php"; 
    ?>
<!-- these small cards direct users to key pages of the website, they display beside each other on wide screens, and on top of each other on narrow screens-->
<div class="sectionBack"> 
<br>
<br>
<br>
<br>
<?php 
if (empty($_SESSION['user_id'])){
        echo "<h2 id='please-login-msg' style='text-align:center;margin-top:5rem'>Please login to access the social feed.</h2>";
         header("refresh:2;url=https://freelancerrr.herokuapp.com/");
    }
    else {
?>
    <div class="row" style="width: 80%; margin: auto; padding-top: 20px;">
        <div class="col-sm-10" style="padding:1em;">
            <h3>Social Feed</h3>
            <p>Post and interact with other posts</p>
            <br>
<?php
    echo postUpdateForm();
    echo getUserFeed();       
?>
        </div>
    </div>
<br> 
</div>
<br>
<br>
<br>

<script type='application/javascript'>
            $('#inputGroupFile02').on('change',function(){
                //get the file name
                var fileName = $(this).val().replace('C:\\fakepath\\', " ");
                //replace the 'Choose a file' label
                $(this).next('.custom-file-label').html(fileName);
            });
                function demoShow() {           
                    var x = document.getElementById('submit'); 
                    if (x.style.visibility === 'hidden') {
                            x.style.visibility = 'visible';
                        }
                    else {
                            x.style.visibility = 'hidden';
                    }
                }
        </script>
        
<?php include "../components/Footer.php"; ?>

<!-- home page style -->
    <style>
        /* background colour of dark grey */
        body {
            background-color: #ebebeb;
        }
        
        .hide-me[aria-expanded="true"] {
            display: none;
        }

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
            .ResImg{
                width: 30%;
            }
            .displayHead{
                font-size: small;
            }
        }

        @media screen and (max-width:700px) {
            .responsiveDisplay {
                flex-wrap: wrap;
                width: 90%;
            }

        }
    </style>
</body>
<?php
}
echo endHtml();
?>