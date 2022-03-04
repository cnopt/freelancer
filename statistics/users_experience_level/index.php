<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../_css/style.css"></link>
        <link href="../_css/c3.css" rel="stylesheet">
        <link rel="stylesheet" href="../../stylesheets/css/Navbar.css"/>
        <!-- Bootstrap CSS -->
        <!-- links the bootstrap library and assets it uses for layout -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <title>User Experience Level</title>
    </head>
    <body>

        <?php include "../../components/Navbar.php" ?>

        <div id="content">
            <header id="main-page-header">
                <nav id="main-page-nav">
                    <a href="javascript:void(0)" class="closebtn" onclick="hideNav()">&times;</a>
                    <a href="../education_level_salary/">Education Level Salary</a>
                    <a href="../job_postings/">Job Postings</a>
                    <a href="../popular_technologies/">Popular Technologies</a>
                    <a href="../technologies_salary/">Technologies Salary</a>
                    <a href="../users_by_country/">Users by Country</a>
                    <a href="#">User Experience Level</a>
                </nav>
                <svg onclick="showNav()" width="28px" height="28px" viewBox="0 0 48 48" style="cursor:pointer;"><path d="M6 36h36v-4H6v4zm0-10h36v-4H6v4zm0-14v4h36v-4H6z"></path></svg>
                <p id="main-page-header-text">Menu</p>
            </header>

            <div id="title-desc" style="margin-top: 3.75rem;">
                <h1>User Experience Level</h1>
                <p>Visualisation of the experience level of the freelancers on the platform.</p>
            </div>

            <div id="years-wrapper" style="margin-top: 1.5rem;">
                <div id="subsection-heading">
                    <h3>Years of Experience</h3>
                    <p>How many years of being an active freelancer.</p>
                </div>
                <div id="tooltip" class="tooltip">
                    <div class="tooltip-value">
                        <span id="name"></span>,
                        <span id="count"></span> users
                    </div>
                    <div id="tooltip-shadow"</div>
                </div>
                </div>
            </div>

            <div id="skill-level-wrapper" style="margin-top: 1.5rem;">
                <div id="subsection-heading">
                    <h3>Skill Level</h3>
                    <p>How each freelancer would rate their skills.</p>
                </div>
                <div id="tooltip" class="tooltip">
                    <div class="tooltip-value">
                        <span id="name"></span>,
                        <span id="count"></span> users
                    </div>
                    <div id="tooltip-shadow"</div>
                </div>
                </div>
            </div>

            <div id="avbl-wrapper" style="margin-top: 1.5rem;">
                <div id="subsection-heading">
                    <h3>Average Completion Time</h3>
                    <p>How many days users spend on each job, grouped by year.</p>
                </div>
                <div id="c3-chart"></div>
            </div>
        </div>



        <script src="https://d3js.org/d3.v5.min.js"></script>
        <script src="../_js/c3.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/d3-legend/2.24.0/d3-legend.js"></script>
        <script src="./js/draw-table.js"></script>
        <script src="../_js/nav.js"></script>

        <?php include "../../components/Footer.php"; ?>
    </body>

</html>