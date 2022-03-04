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
    <title>Popular Skills/Technologies</title>
</head>

<body>

    <?php include "../../components/Navbar.php" ?>

    <div id="content">
        <header id="main-page-header">
            <nav id="main-page-nav">
                <a href="javascript:void(0)" class="closebtn" onclick="hideNav()">&times;</a>
                <a href="../education_level_salary/">Education Level Salary</a>
                <a href="../job_postings/">Job Postings</a>
                <a href="#">Popular Technologies</a>
                <a href="../technologies_salary/">Technologies Salary</a>
                <a href="../users_by_country/">Users by Country</a>
                <a href="../users_experience_level/">User Experience Level</a>
            </nav>
            <svg onclick="showNav()" width="28px" height="28px" viewBox="0 0 48 48" style="cursor:pointer;"><path d="M6 36h36v-4H6v4zm0-10h36v-4H6v4zm0-14v4h36v-4H6z"></path></svg>
            <p id="main-page-header-text">Menu</p>
        </header>

        <div id="title-desc" style="margin-top:3.75rem">
            <h1>Most Popular Skills/Interests</h1>
            <p>Visualize the most common skills and interests users list in their profiles.</p>
        </div>


        <div id="general-wrapper" class="wrapper">
            <div id="subsection-heading" style="width:950px;margin:auto">
                <h3>General Overview</h3>
                <p>A general look at the most popular technologies/skills/frameworks/research areas users are interested in.</p>
            </div>
        </div>

        <table id="table"></table>

        <div id="grouped-wrapper" class="wrapper">
            <div id="subsection-heading" style="width:950px;margin:auto">
                <h3>Popular Technologies - Grouped</h3>
                <p>Number of users grouped by a select number of popular technologies. Each circle represents 10 users.
                    <br/> Click a button to change the groupings,
                    or drag the circles to move them around.
                </p>
            </div>
            <div id="grouped-bubble-toolbar">
                <button id="All" class="button">All</button>
                <button id="Technology" class="button">By Technology</button>
                <button id="Type" class="button">By Type</button>
            </div>
        </div>
    </div>

    <script src="https://d3js.org/d3.v4.min.js"></script>
    <script src="./js/draw-table.js"></script>
    <script src="../_js/nav.js"></script>

    <?php include "../../components/Footer.php"; ?>
</body>

</html>