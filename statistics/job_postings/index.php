<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../_css/style.css"></link>
    <link rel="stylesheet" href="../_css/billboard.min.css"></link>
    <link rel="stylesheet" href="../../stylesheets/css/Navbar.css"/>
    <!-- Bootstrap CSS -->
    <!-- links the bootstrap library and assets it uses for layout -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <title>Job Postings</title>
</head>

<body>

    <?php include "../../components/Navbar.php" ?>

    <div id="content">
        <header id="main-page-header">
            <nav id="main-page-nav">
                <a href="javascript:void(0)" class="closebtn" onclick="hideNav()">&times;</a>
                <a href="../education_level_salary/">Education Level Salary</a>
                <a href="#">Job Postings</a>
                <a href="../popular_technologies/">Popular Technologies</a>
                <a href="../technologies_salary/">Technologies Salary</a>
                <a href="../users_by_country/">Users by Country</a>
                <a href="../users_experience_level/">User Experience Level</a>
            </nav>
            <svg onclick="showNav()" width="28px" height="28px" viewBox="0 0 48 48" style="cursor:pointer;"><path d="M6 36h36v-4H6v4zm0-10h36v-4H6v4zm0-14v4h36v-4H6z"></path></svg>
            <p id="main-page-header-text">Menu</p>
        </header>

        <div id="title-desc" style="margin-top: 3.75rem">
            <h1>Job Listings</h1>
            <p>A look at jobs that are posted by business or individuals wishing to work with our freelancers.</p>
        </div>


        <div id="job-listings-by-year-wrapper" class="wrapper">
            <div id="subsection-heading">
                <h3>Time of Year</h3>
                <p>The most popular times of the year for jobs to be posted by users. Hover over a month to display its specific value.</p>
            </div>
            <div id="tooltip" class="tooltip">
                <div class="tooltip-value">
                    <span id="count"></span> jobs posted
                </div>
                <div id="tooltip-shadow"></div>
            </div>
        </div>

        <div id="job-listings-by-day-wrapper" class="wrapper">
            <div id="subsection-heading">
                <h3>Day Of The Week</h3>
                <p>Which days of the week are most popular for jobs to be posted. Averaged across data from 2015-2019.</p>
            </div>
            <div id="tooltip" class="tooltip">
                <div class="tooltip-value">
                    <span id="count"></span> jobs posted
                </div>
                <div id="tooltip-shadow"></div>
            </div>
        </div>

        <div id="job-listings-popular-tech-wrapper" class="wrapper">
            <div id="subsection-heading">
                <h3>Popular technologies in job listings</h3>
                <p>Some of the most in-demand technologies, based on how many job postings list them as part of their requirements. Hover to reveal specific values.</p>
            </div>
            <div id="chart-job-listings-popular-tech"></div>
        </div>

        <div id="job-listings-revenue-wrapper" class="wrapper">
            <div id="subsection-heading">
                <h3>Job Posting Budgets</h3>
                <p>A look at all of the revenue brackets jobs are posted under.</p>
                <select id="revenue-selector">
                    <option value="All">All job postings</option>
                    <option value="JavaScript">JavaScript</option>
                    <option value="C++">C++</option>
                    <option value="ML">Machine Learning</option>
                    <option value="Ruby">Ruby</option>
                    <option value="PHP">PHP</option>
                </select>
            </div>
            <div id="chart-job-listings-revenue"></div>
        </div>
    </div>


    <script src="https://d3js.org/d3.v4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3-legend/2.24.0/d3-legend.js"></script>
    <script src="../_js/billboard.min.js"></script>
    <script src="./js/draw-table.js"></script>
    <script src="../_js/nav.js"></script>

    <?php include "../../components/Footer.php"; ?>
</body>

</html>