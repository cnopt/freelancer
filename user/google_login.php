<?php
	require_once "config.php";
	
	$login_button = '';
	
	if (isset($_GET["code"]))
	{
		$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);//Fetches the access token
		
		if(!isset($token['error']))
		{
			$google_client->setAccessToken($token['access_token']);//Sets the access token from google to $token
		}
	}
	
	//Checks if the access token is set
	if(!isset($token['access_token']))
	{
        header("Location: " . $google_client->createAuthUrl());
	}
?>
