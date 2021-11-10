<?php
	/*
		Page pour détruire la SESSION en se déconnectant
	*/

	session_start();
	$_session = array();
	session_destroy();

	header('location: index.php');
?>