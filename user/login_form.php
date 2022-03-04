<?php
	require_once "config.php";
?>
<html>
	<head>
		<title>Account Creation</title>
	</head>
	<body>
	<!-- This is the styling for the form -->
	<style>
	input[type=text], select, textarea {
	width: 100%;
	padding: 12px;
	border: 1px solid #ccc;
	border-radius: 4px;
	resize: vertical;
	}

	.container {
	border-radius: 5px;
	background-color: #f2f2f2;
	padding: 20px;
	}
	</style>
	<div class="container">
	<form method="post">
	
		<!-- User Type -->
		<br><h1>Type: </h1>
		<select name="type_id">
			<option value="0">Freelancer</option>
			<option value="1">Employer</option>
		</select>
		
		<!-- User Description -->
		<br><h1>Description: </h1>
		<textarea name="description" rows="8" cols="30" maxlength="1000" placeholder="Describe yourself..."></textarea>
		
		<!-- User Country -->
		<br><h1>Country: </h1>
		<textarea name="country" rows="1" cols="20" maxlength="100" placeholder="Where are you located?"></textarea>
			
		<!-- User Interests -->	
		<br><h1>Interests: </h1>
		<textarea name="interests" rows="8" cols="30" maxlength="1000" placeholder="What are your interests?"></textarea>
		
		<!-- User Education Level -->
		<br><h1>Education Level: </h1>
		<select name="education_level">
		<option value="Phd">Phd</option>
		<option value="Masters">Masters</option>
		<option value="Diploma">Diploma</option>
		<option value="Degree">Degree</option>
		</select>
			
		<!-- User Education History -->
		<br><h1>Education History: </h1>
		<textarea name="education_history" rows="4" cols="30" maxlength="500" placeholder="What is your education history?"></textarea>
		
		<!-- User Number of Past Employers -->
		<br><h1>Amount Of Employers: </h1>
		<input type="number" name="amount_of_employers" maxlength="11">
		
		<!-- User Years of Working Experience -->
		<br><h1>Years Of Experience: </h1>
		<input type="number" name="years_experience" maxlength="11">
		
		<!-- User Availability -->
		<br><h1>Availability: </h1>
		<select name="availability">
		<option value="Part-time">Part-time</option>	
		<option value="Full-time">Full-time</option>
		</select>
		
		<!-- User Salary Bracket -->
		<br><h1>Salary Bracket: </h1>
		<select name="salary_bracket">
		<option value="0-15,000">0-15,000</option>
		<option value="15,000-25,000">15,000-25,000</option>
		<option value="25,000-40,000">25,000-40,000</option>
		<option value="40,000-60,000">40,000-60,000</option>
		<option value="60,000-70,000">60,000-70,000</option>
		<option value="70,000-90,000">70,000-90,000</option>
		<option value="90,000+">90,000+</option>
		</select>
		
		<!-- Submits the Form -->		
		<br><br><button type="submit" name="submit">Submit</button>
		</form>
		</div>
	</body>
</html>
<?php	

	//Gets connection to the database
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
	//If the form is submitted then put the following data into the database, in the correct table and under the correct field					
	if(isset($_POST['submit']))
	{	
		$type_id=$_POST['type_id'];
		$description=$_POST['description'];
		$country=$_POST['country'];
		$interests=$_POST['interests'];
		$education_level=$_POST['education_level'];
		$education_history=$_POST['education_history'];
		$years_experience=$_POST['years_experience'];
		$availability=$_POST['availability'];
		$salary_bracket=$_POST['salary_bracket'];
		
		$email = $_SESSION['user_email_address'];
		$user_id = $_SESSION['user_id'];
		$name = $_SESSION['user_name'];
		$image = $_SESSION['user']['picture'];
		$user_type = $_SESSION['type_id'];
                    
        //Extracts data to add the user details in profile into the database 
        $addUserProfile = $conn->multi_query ("BEGIN; 
        INSERT INTO profile (name, profile_picture, education_level, availability, salary_bracket, description, country, interests, education_history, years_experience)
        VALUES ('$name', '$image', '$education_level', '$availability', '$salary_bracket', '$description', '$country', '$interests', '$education_history', '$years_experience');         
        INSERT INTO user (email, oauth_id, profile_id, type_id) 
        VALUES('$email', '$user_id', LAST_INSERT_ID() ,'$type_id'); 
        COMMIT");
		
		//If the user profile has been added then continue to the userFeed page, and if not then try the form again
        if ($conn->query($addUserProfile) === TRUE) {
            echo "<p>Try again</p>";
        }
        else {
            header("Location: /social_media/userFeed.php"); //Where the user is taken after login
            $conn->close(); 
        }
        $conn->close();//Close the database connection
    }

?>