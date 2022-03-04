<?php
	require_once "config.php";
	
	$google_client->revokeToken();
	
	session_destroy();//Ends the session
	
	header('location: /index.php');//Sends the user back to the index page, so they can no longer see the user feed since they are no longer logged in.

?>