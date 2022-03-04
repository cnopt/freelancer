<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../_css/style.css"></link>
        <link rel="stylesheet" href="../../stylesheets/css/Navbar.css"/>
        <!-- Bootstrap CSS -->
        <!-- links the bootstrap library and assets it uses for layout -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <title>Technologies by Salary</title>
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
                    <a href="#">Technologies Salary</a>
                    <a href="../users_by_country/">Users by Country</a>
                    <a href="../users_experience_level/">User Experience Level</a>
                </nav>
                <svg onclick="showNav()" width="28px" height="28px" viewBox="0 0 48 48" style="cursor:pointer;"><path d="M6 36h36v-4H6v4zm0-10h36v-4H6v4zm0-14v4h36v-4H6z"></path></svg>
                <p id="main-page-header-text">Menu</p>
            </header>

            <div id="title-desc" style="margin-top: 3.75rem;">
                <h1>Salary brackets</h1>
                <p>A breakdown of popular technologies on the site, and the salary brackets users of them typically fall into.</p>
            </div>


            <div id="salary-languages-wrapper" style="margin-top: 1.5rem;">
                <div id="subsection-heading">
                    <h3>Programming Languages</h3>
                    <p>General overview of the most popular programming languages on the site.</p>
                </div>
                <svg id="chart-programming-languages" width="950" height="420"></svg>
                <div id="tooltip" class="tooltip">
                    <div class="tooltip-value">
                        <span id="count"></span> users
                    </div>
                </div>
                <table id="table-programming-languages"></table>
            </div>


            <div id="salary-js-wrapper" style="margin-top: 1.5rem;">
                <div id="subsection-heading">
                    <h3>Frontend JavaScript Frameworks</h3>
                    <p>The five most prominent JS frameworks users have listed as part of their skills.</p>
                </div>
                <svg id="chart-salary-js-frameworks" width="950" height="420"></svg>
                <div id="tooltip" class="tooltip">
                    <div class="tooltip-value">
                        <span id="count"></span> users
                    </div>
                </div>
                <table id="table-salary-js-frameworks"></table>
            </div>

            <div id="salary-backend-wrapper">
                <div id="subsection-heading">
                    <h3>Server Frameworks</h3>
                    <p>Popular backend (or server) frameworks users list in their skills.</p>
                </div>
                <svg id="chart-backend-frameworks" width="950" height="420"></svg>
                <div id="tooltip" class="tooltip">
                    <div class="tooltip-value">
                        <span id="count"></span> users
                    </div>
                </div>
                <table id="table-backend-frameworks"></table>
            </div>

            <div id="salary-machine-learning-wrapper">
                <div id="subsection-heading">
                    <h3>Machine Learning Frameworks</h3>
                    <p>While there are plenty more, these are some of the most popular Machine Learning frameworks users list as part of their skills.</p>
                </div>
                <svg id="chart-machine-learning-frameworks" width="950" height="420"></svg>
                <div id="tooltip" class="tooltip">
                    <div class="tooltip-value">
                        <span id="count"></span> users
                    </div>
                </div>
                <table id="table-machine-learning-frameworks"></table>
            </div>
        </div>

        <script src="https://d3js.org/d3.v5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/d3-legend/2.25.6/d3-legend.min.js"></script>
        <script src="./js/draw-table.js"></script>
        <script src="../_js/nav.js"></script>

        <?php include "../../components/Footer.php"; ?>
    </body>

</html>