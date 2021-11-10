<?php
	session_start();

	if(($_SESSION['pseudo'] == "" )or($_SESSION['pseudo'] == NULL)){
		header('Location: ../identification.php');     
	}	
		
	//echo '<script alter(\''.$_SESSION['id_uti'].'\')</script>';
	$lecontenue = "";

	include('admin_tete.php'); 
	include('admin_pied.php'); 
?>