<?php
	//Change this to the appropriate directory
	require_once "../vendor/autoload.php";
	
	$google_client = new Google_Client();
	
	$google_client->setClientId('508479549853-jrn05dhso0cn2dn1kf2oaqh1ieag9ftp.apps.googleusercontent.com');//Google API Client ID
	
	$google_client->setClientSecret('oS6MS8_OR7kaeh96sbsU3Xxw');

	$google_client->setRedirectUri('https://freelancerrr.herokuapp.com/user/login_process.php');//After the user clicks the login button they are redirected to this url
	
	$google_client->addScope('email');//User email
	
	$google_client->addScope('profile');//User Profile Info such as name and picture
	
	$google_client->addScope('openid');//User ID
	
	session_start();//Starts the session
?>