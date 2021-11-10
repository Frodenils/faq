<html>
<head>
	<title>Envoi du formulaire</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/envoi.css" />
	<?php //header("refresh:5;url=Contact.php");?>
</head>
<body>
<?php

$DateMessage = date("Y")."-".date("m")."-".date("d")." ".date("H").":".date("i").":".date("s");

$CGU='off';
$Classe='none';
if(isset($_POST['nom']))
  {  $Nom     = $_POST['nom']  ; }

if(isset($_POST['prenom']))
  { $Prenom  = $_POST['prenom']; }

if(isset($_POST['email']))
  { $Email   = $_POST['email'] ; }

if(isset($_POST['classe']))
  {  $Classe = $_POST['classe']; }

if(isset($_POST['slider']))
  {	$CGU     = $_POST['slider']; }

if(isset($_POST['texte']))
  { $Texte   = $_POST['texte'] ; }


echo$DateMessage;

if (($Nom==NULL) OR ($Prenom==NULL) OR ($Email==NULL) OR ($Classe=='none') OR ($CGU=='off') OR ($Texte)==NULL)
{
	echo "<h1 align=center><font color=red size=50>ERREUR</font></h1>";
	echo "<p align=center>Veillez à spécifier l'ensemble des informations requises, à savoir votre nom, prénom et adresse e-mail, votre classe, à valider les conditions générales d'utilisation et à spécifier l'objet de votre message.</p>";
	echo "<form method=\"post\" action=\"Contact.php\">
		<input type=\"submit\" name=\"Wrong\" value=\"Retour\" >
		</form>";

}
elseif(($Classe!="3ème")AND($Classe!="Seconde")AND($Classe!="Première")AND($Classe!="Terminale")AND($Classe!="BTS1")AND($Classe!="BTS2"))
{
	echo "<h1 align=center><font color=red size=50>ERREUR</font></h1>";
	echo "<p align=center>ERREUR pour la classe. Veuillez ne pas modifier le code html pour envoyer des données erronées par rapport à votre classe.";
	echo "<form method=\"post\" action=\"Contact.php\">
		<input type=\"submit\" name=\"Wrong\" value=\"Retour\" >
		</form>";
}
else
{
	try 
	{
		include('connection_PDO.php');
	}
	catch (Exception $e) 
	{
	    die('Erreur : ' . $e->getMessage());
	}
	$EnvoiDB = $bdd ->prepare('INSERT INTO ContactFAQ(Nom,Prenom,Email,Classe,Texte,Date_Envoi, Traitement) VALUES(:Nom, :Prenom, :Email, :Classe, :Texte, :Date_Envoi, :Traitement)');
	$EnvoiDB  -> execute(array(
		'Nom'    => $Nom,
		'Prenom' => $Prenom,
		'Email'  => $Email,
		'Classe' => $Classe,
		'Texte'  => $Texte,
		'Date_Envoi' => $DateMessage,
		'Traitement' => 'En attente',
		));
	$EnvoiDB -> closeCursor();

	echo "<h1 align=center><font color=#5A639C size=50>MESSAGE ENVOYÉ</font></h1>";
	echo "<p align=center> Votre message a bien été envoyé. Il sera étudié dans les plus brefs délais.</p>";
	echo "<form method=\"post\" action=\"Contact.php\">
		<input type=\"submit\" name=\"Correct\" value=\"Retour\" >
		</form>";

}

?>
</body>
</html>