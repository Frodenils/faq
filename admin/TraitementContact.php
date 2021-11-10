<<?php 
	session_start();

	if(($_SESSION['pseudo'] == "" )or($_SESSION['pseudo'] == NULL)){
		header('Location: ../identification.php');     
	}	
 ?>


<html>
<head>
	<title>Changement du traitement</title>
	<meta charset="UTF-8">
</head>
<body>


<?php

		$Id_Form = $_POST['Changer_Traitement'];
		echo $Id_Form;
		


		try 
		{
			include('../connection_PDO.php'); 
		}
		catch (Exception $e) 
		{
		    die('Erreur : ' . $e->getMessage());
		}

		$Selection = $bdd ->query('SELECT Traitement FROM ContactFAQ where Id_Formulaire = '.$Id_Form.'');
		$donneesSelection = $Selection ->fetchAll(PDO::FETCH_COLUMN, 0);

		echo $donneesSelection[0];

		if ($donneesSelection[0]=='En attente') {
			$MessageTraite = $bdd ->query('UPDATE ContactFAQ SET Traitement=\'Traité\' WHERE Traitement=\'En attente\' AND Id_Formulaire='.$Id_Form.'');

			header('Location: ReceptionContact.php');
  			exit();
		}
		elseif  ($donneesSelection[0]=='Traité') {
			$MessageTraite = $bdd ->query('UPDATE ContactFAQ SET Traitement=\'En attente\' WHERE Traitement=\'Traité\' AND Id_Formulaire='.$Id_Form.'');

			header('Location: ReceptionTraites.php');
  			exit();
		}

		

	?>
</body>
</html>

 

