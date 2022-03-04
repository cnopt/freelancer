<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../_css/style.css"></link>
        <link rel="stylesheet" href="../_css/c3.css"></link>
        <link rel="stylesheet" href="../../stylesheets/css/Navbar.css"/>
        <!-- Bootstrap CSS -->
        <!-- links the bootstrap library and assets it uses for layout -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <title>Users by Country</title>
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
                    <a href="#">Users by Country</a>
                    <a href="../users_experience_level/">User Experience Level</a>
                </nav>
                <svg onclick="showNav()" width="28px" height="28px" viewBox="0 0 48 48" style="cursor:pointer;"><path d="M6 36h36v-4H6v4zm0-10h36v-4H6v4zm0-14v4h36v-4H6z"></path></svg>
                <p id="main-page-header-text">Menu</p>
            </header>

            <div id="title-desc" style="margin-top: 3.75rem">
                <h1>Global Breakdown</h1>
                <p>A wide-scale look at the platform's users all around the globe.</p>
            </div>

            <div id="map-wrapper">
                <div id="subsection-heading">
                    <h3>Users By Country</h3>
                    <p>In which countries is Freelancer popular, based on how many users are based there.</p>
                </div>
                <svg id="map-svg" width="950" height="360"></svg>
                <table id="table-users-by-country"></table>
            </div>


            <div id="user-signup-by-country-wrapper" style="margin-top: 3rem;">
                <div id="subsection-heading">
                    <h3>User Signup By Country</h3>
                    <p>How many new registrations the site experienced, from each of the top 5 most popular countries on the platform.</p>
                </div>
                <select id="country-selector">
                    <option value="All">All Countries</option>
                    <option value="Australia">Australia</option>
                    <option value="Pakistan">Pakistan</option>
                    <option value="Spain">Spain</option>
                    <option value="Brazil">Brazil</option>
                    <option value="Belgium">Belgium</option>
                </select>
                <div id="chart-user-signup-by-country" width="950" height="400"></div>
            </div>
        </div>


        <script src="https://d3js.org/d3.v5.min.js"></script>
        <script src="../_js/c3.min.js"></script>
        <script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
        <script src="https://d3js.org/d3-geo-projection.v2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/d3-legend/2.24.0/d3-legend.js"></script>
        <script src="./js/draw-table.js"></script>
        <script src="../_js/nav.js"></script>

        <?php include "../../components/Footer.php"; ?>
    </body>

</html>