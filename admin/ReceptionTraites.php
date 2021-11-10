<?php 
	session_start();	
//	echo $_SESSION['pseudo'];
//	echo '</br>';

	if(($_SESSION['pseudo'] == "" )or($_SESSION['pseudo'] == NULL)){
		header('Location: ../identification.php');     
	}	


	include('admin_tete.php'); 

?>
<link rel="stylesheet" type="text/css" href="../css/ReceptionContact.css"/>
<h1 align=center id="tete_de_page"> Tous les formulaires envoyés depuis le formulaire de contact sont regroupés ici </h1><br/> 


<?php


	try 
	{
		include('../connection_PDO.php');
	}
	catch (Exception $e) 
	{
	    die('Erreur : ' . $e->getMessage());
	}

////////////////////////////////////// RECUPERATION DES DONNEES DE FORMULAIRE	
// RECUPERATION DU NOM
	$RecupFormN = $bdd ->query('SELECT Nom FROM Contactfaq WHERE Traitement=\'Traité\'');
    $donneesRecupFormN = $RecupFormN ->fetchAll(PDO::FETCH_COLUMN, 0);

// RECUPERATION DU PRENOM
    $RecupFormP = $bdd ->query('SELECT Prenom FROM Contactfaq WHERE Traitement=\'Traité\'');
    $donneesRecupFormP = $RecupFormP ->fetchAll(PDO::FETCH_COLUMN, 0); 

// RECUPERATION DE L'ADRESSE E-MAIL
    $RecupFormE = $bdd ->query('SELECT Email FROM Contactfaq WHERE Traitement=\'Traité\'');
    $donneesRecupFormE = $RecupFormE ->fetchAll(PDO::FETCH_COLUMN, 0); 

// RECUPERATION DE LA CLASSE
    $RecupFormC = $bdd ->query('SELECT Classe FROM Contactfaq WHERE Traitement=\'Traité\'');
    $donneesRecupFormC = $RecupFormC ->fetchAll(PDO::FETCH_COLUMN, 0); 

// RECUPERATION DU TEXTE
    $RecupFormT = $bdd ->query('SELECT Texte FROM Contactfaq WHERE Traitement=\'Traité\'');
    $donneesRecupFormT = $RecupFormT ->fetchAll(PDO::FETCH_COLUMN, 0); 

// RECUPERATION DE LA DATE D'ENVOI
    $RecupFormDE = $bdd ->query('SELECT Date_Envoi FROM Contactfaq WHERE Traitement=\'Traité\'');
    $donneesRecupFormDE = $RecupFormDE ->fetchAll(PDO::FETCH_COLUMN, 0);

// RECUPERATION DE L'ÉTAT DU TRAITEMENT
    $RecupFormEdT = $bdd ->query('SELECT Traitement FROM Contactfaq WHERE Traitement=\'Traité\'');
    $donneesRecupFormEdT = $RecupFormEdT ->fetchAll(PDO::FETCH_COLUMN, 0); 

////////////////////////////////////// FIN DE RECUPERATION DES DONNEES DE FORMULAIRE	



    $NbrForm = $RecupFormN ->rowCount();

	if($NbrForm==0) 
	{
	  echo "<p> Recherche terminée. Il n'y a pas de formulaire traité.";
	}
	else
	{
		echo "<h1 id=\"Non_Traités\"> Liste des messages déjà traités : </h1><br/>";
		for ($i=0;$i<$NbrForm;$i++)
		{
			$RecupID = $bdd ->prepare('SELECT Id_Formulaire FROM Contactfaq WHERE Nom=:Nom AND Prenom=:Prenom AND Email=:Email AND Texte=:Texte');
			$RecupID  -> execute(array(
			'Nom'   => $donneesRecupFormN[$i],
			'Prenom'  => $donneesRecupFormP[$i],
			'Email'  => $donneesRecupFormE[$i],
			'Texte' => $donneesRecupFormT[$i],
			));
   			$donneesRecupID = $RecupID ->fetchAll(PDO::FETCH_COLUMN, 0);
			echo "<div class=\"div_nouveau_formulaire\">";
			echo "<div class=\"Get_Nom_Form\">".$donneesRecupFormN[$i]."</div>";
			echo "<div class=\"Get_Prenom_Form\">".$donneesRecupFormP[$i]."</div>";
			echo "<div class=\"Get_Etat_Traité_Form\"><img src=\"../img/truemark.png\" class=\"Image_True\">".$donneesRecupFormEdT[$i]."</div><br/>";
			echo "<div class=\"Get_Classe_Form\">".$donneesRecupFormC[$i]."</div><br/>";
			
			echo "<div class=\"Get_Texte_Form\">".$donneesRecupFormT[$i]."</div><br/>";
			echo "<form method=post action=\"TraitementContact.php\"><input class=\"Changer_Traitement\" type=submit name=\"Changer_Traitement\" value=".$donneesRecupID[0]." id=".$donneesRecupID[0].">Marquer le message comme non-traité.</form>";
			echo "<div class=\"Get_Email_Form\">Adresse e-mail : ".$donneesRecupFormE[$i]."</div>";
			
			echo "<div class=\"Get_Date_Envoi_Form\"> Message envoyé le ".$donneesRecupFormDE[$i]."</div>";
			;
			echo "</div>";
			echo "<br/><br/><br/><br/>";
		}
	}


	include('admin_pied.php'); 
?>