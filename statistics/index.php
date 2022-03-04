<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="./_css/style.css"></link>
        <link rel="stylesheet" href="./_css/billboard.min.css"></link>
        <link rel="stylesheet" href="../stylesheets/css/Navbar.css"/>
        <!-- Bootstrap CSS -->
        <!-- links the bootstrap library and assets it uses for layout -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <title>Freelancer Data Visualisation</title>
    </head>
    <body>

        <?php include "../components/Navbar.php" ?>

        <div id="content">
            <header id="main-page-header">
                <nav id="main-page-nav">
                    <a href="javascript:void(0)" class="closebtn" onclick="hideNav()">&times;</a>
                    <a href="education_level_salary/">Education Level Salary</a>
                    <a href="job_postings/">Job Postings</a>
                    <a href="popular_technologies/">Popular Technologies</a>
                    <a href="technologies_salary/">Technologies Salary</a>
                    <a href="users_by_country/">Users by Country</a>
                    <a href="users_experience_level/">User Experience Level</a>
                </nav>
                <svg onclick="showNav()" width="28px" height="28px" viewBox="0 0 48 48" style="cursor:pointer;"><path d="M6 36h36v-4H6v4zm0-10h36v-4H6v4zm0-14v4h36v-4H6z"></path></svg>
                <p id="main-page-header-text">Menu</p>
            </header>

            <div id="chart-header" style="margin-top:2.5rem;"></div>

            <div id="subsection-heading" style="margin-top:3rem;width:700px">
                <h3>Freelancer Data Visualisations</h3>
                <p>
                    This subsection of the project provides data visualisations built using the D3 data 
                    processing language. Each subfolder provides one or more visual breakdowns, built 
                    by hand utilising D3's processing ability. Some sections also take advantage of supplemental
                    D3 libraries <sup>1</sup> to provide additional functionality and helper methods.
                </p>
                <p>
                    Dummy data in both JSON and CSV format was used as the basis for the charts, created
                    from analysis of the group database developed as part of the project.
                </p>
                <p>
                    Each section can be visited by opening the navigational header with the button on the top left of the page. 
                </p>
                <br/>
                <p>
                    <sup>1. <a href="https://c3js.org">c3js.org</a>, <a href="https://naver.github.io/billboard.js/">billboard.js</a> </sup>
                </p>
            </div>
        </div>


        <script src="https://d3js.org/d3.v5.min.js"></script>
        <script src="./_js/billboard.min.js"></script>
        <script src="./_js/nav.js"></script>
        <script type="text/javascript">
         var chart = bb.generate({
             size: {
                 width:675,
                 height:120
             },
            data: {
                columns: [
                    ["data1", 30, 200, 100, 400, 150, 250]
                ]
            },
            color: {
                pattern: [
                    "#ccc"
                ]
            },
            axis: {
                x: {
                    show:false
                },
                y: {
                    show:false
                }
            },
            legend: {
                show:false
            },
            tooltip: {
                show:false
            },
            bindto: "#chart-header"
            });

            setTimeout(function() {
                chart.load({
                    columns: [
                        ["data1", 230, 190, 300, 500, 300, 400]
                    ]
                });
            }, 1500);

            setTimeout(function() {
                chart.load({
                    columns: [
                        ["data1", 130, 150, 200, 300, 200, 100]
                    ]
                });
            }, 3000);

            setTimeout(function() {
                chart.load({
                    columns: [
                        ["data1", 140, 20, 120, 200, 300, 260]
                    ]
                });
            }, 4500);

            setTimeout(function() {
                chart.load({
                    columns: [
                        ["data1", 350, 100, 10, 90, 10, 260]
                    ]
                });
            }, 6000);

            setTimeout(function() {
                chart.load({
                    columns: [        
                        ["data1", 30, 200, 100, 400, 150, 250]
                    ]
                });
            }, 7500);
        </script>

        <?php include "../../components/Footer.php"; ?>
    </body>
</html>