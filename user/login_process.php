<?php 
	require_once "config.php";
	require_once "google_login.php";
	
	//Connects to the database
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
	
			$google_service = new Google_Service_Oauth2($google_client);
			
			$data = $google_service->userinfo->get();//Gets the users infor from google, to be used in sessions later on.
	
			$id = $data['id'];
			
			$_SESSION['user'] = $data;//This is used to access/display/store the users profile picture later on.
			
			//This query is used to select the id of the user trying to sign in now, and check
			//it against all the id's in the database to see if there are any matches to it.
			$querySQL = "SELECT oauth_id, type_id FROM user WHERE oauth_id = ?;";
            $stmt = $conn->prepare($querySQL);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            //If the id is a match for an id in the database then let the user sign in, without having to complete the form again.           
			if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
			
				if(!empty($data['name'])) 
				{
					$_SESSION['user_name'] = $data['name'];//Creates a session for the users name, as that can be accessed through their google account.
				}
			
				if(!empty($data['email']))
				{
					$_SESSION['user_email_address'] = $data['email'];//Creates a session for the users email, as that can be accessed through their google account.
				}
			
				if(!empty($data['picture']))
				{
					$_SESSION['user_image'] = $data['picture'];//Creates a session for the users profile picture, as that can be accessed through their google account.
				}
			
				if(!empty($data['id']))
				{
					$_SESSION['user_id'] = $data['id'];//Creates a session for the users id, as that can be accessed through their google account.
                }

                $_SESSION["type_id"] = $row["type_id"];
				
				header("Location: /social_media/userFeed.php"); //Change this to wherever the user should be taken after login.
				//exit();
       
			} 
    
			//Else, the user id was not found in the database meaning they are a new user and will be taken to the login form to create their account.
			else {
				if(!empty($data['name'])) 
				{
					$_SESSION['user_name'] = $data['name'];//Creates a session for the users name, as that can be accessed through their google account.
				}
			
				if(!empty($data['email']))
				{
					$_SESSION['user_email_address'] = $data['email'];//Creates a session for the users email, as that can be accessed through their google account.
				}
			
				if(!empty($data['picture']))
				{
					$_SESSION['user_image'] = $data['picture'];//Creates a session for the users profile picture, as that can be accessed through their google account.
				}
			
				if(!empty($data['id']))
				{
					$_SESSION['user_id'] = $data['id'];//Creates a session for the users id, as that can be accessed through their google account.
				}
				//Sends the user to the login form
				header("Location: login_form.php");
			}
?>