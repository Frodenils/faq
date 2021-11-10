<?php

	$pseudo = "";
	$password = "";
	$error = 0 ;
	
	if (isset($_POST['pseudo']))
		$pseudo=$_POST['pseudo'];
	if (isset($_POST['password']))
		$password=$_POST['password'];

	if ($pseudo!= ""){

		include('connection_PDO.php');

		$reponse = $bdd->query('SELECT `password` FROM `utilisateur` WHERE `pseudo` = \''.$pseudo.'\' ');
		$donnees = $reponse->fetch();

		if ($password==$donnees['password']) { 

			session_start();

			$_SESSION['pseudo'] = $pseudo;
			$_SESSION['password'] = $password;


			$reponse = $bdd->query('SELECT `droit`,`mail`,`id_uti` FROM `utilisateur` WHERE `pseudo` = \''.$pseudo.'\' ');
			$donnees = $reponse->fetch();

			$_SESSION['droit'] = $donnees['droit'];
			$_SESSION['mail'] = $donnees['mail'];
			$_SESSION['id_uti'] = $donnees['id_uti'];

			header('Location: admin/espace_admin.php');      
		} else {
			$error = 1 ;
		}
	} 
?>




<html>
<head>
	<title>Identification</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/Identification.css" />
</head>

<body>
	<div id="bando1"></div>

	<center>
		<?php
			if ($error == 1) {
				echo "<p>ERREUR : Pseudo ou Password incorect</p>";
			}
		?>

		<form action="#" method="post">
			<table>
				<tr>
					<td>
						<label>Pseudo</label>			
					</td>
					<td>
						<input type="text" name="pseudo" placeholder="Pseudo" />
					</td>	
				</tr>
				<tr>
					<td>
						<label>Password</label>
					</td>
					<td>
						<input type="Password" name="password" placeholder="Password" />
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="submit" name="Envoyer" value="Confirmer" >
					</td>
				</tr>
				<!--<tr>
					<a href="#">Password oublié</a>
				</tr>-->
			</table>
		</form>
	</center>

	<div id="bando2"></div>
</body>
</html>