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
    <title>Users Education Level</title>
</head>

<body>

    <?php include "../../components/Navbar.php" ?>

    <div id="content">
        <header id="main-page-header">
            <nav id="main-page-nav">
                <a href="javascript:void(0)" class="closebtn" onclick="hideNav()">&times;</a>
                <a href="#">Education Level Salary</a>
                <a href="../job_postings/">Job Postings</a>
                <a href="../popular_technologies/">Popular Technologies</a>
                <a href="../technologies_salary/">Technologies Salary</a>
                <a href="../users_by_country/">Users by Country</a>
                <a href="../users_experience_level/">User Experience Level</a>
            </nav>
            <svg onclick="showNav()" width="28px" height="28px" viewBox="0 0 48 48" style="cursor:pointer;"><path d="M6 36h36v-4H6v4zm0-10h36v-4H6v4zm0-14v4h36v-4H6z"></path></svg>
            <p id="main-page-header-text">Menu</p>
        </header>

        <div id="title-desc" style="margin-top: 4rem">
            <h1>Users Education Level</h1>
            <p>Visualize the correlation between education level, and their resulting salary bracket. <br/><br/>
                'Users' are generated with their own set
                of attributes, including a 'probability' of ending up within a certain salary bracket. This probability array dictates
                their route through the sankey-diagram.
            </p>
        </div>


        <div id="wrapper" class="wrapper">
        </div>

        <table id="table"></table>
    </div>

    <script src="https://d3js.org/d3.v5.min.js"></script>
    <script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
    <script src="https://d3js.org/d3-geo-projection.v2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3-legend/2.24.0/d3-legend.js"></script>
    <script src="./js/draw-table.js"></script>
    <script src="../_js/nav.js"></script>

    <?php include "../../components/Footer.php"; ?>
</body>

</html>