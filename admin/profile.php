<?php 
	session_start();	
//	echo $_SESSION['pseudo'];
//	echo '</br>';

	if(($_SESSION['pseudo'] == "" )or($_SESSION['pseudo'] == NULL)){
		header('Location: ../identification.php');     
	}	

//pour le profil //////////////////////////////////////////////////////////////////////////////////////
	$pseudo = "";
	$password = "";
	$password2 = "";
	$email = "";
	$erreur = 0;
	
	if (isset($_POST['pseudo']))
		$pseudo=$_POST['pseudo'];
	if (isset($_POST['password']))
		$password=$_POST['password'];
	if (isset($_POST['password2']))
		$password2=$_POST['password2'];
	if (isset($_POST['email']))
		$email=$_POST['email'];

	if($pseudo!=""){
		include('../connection_PDO.php');

		$reponse = $bdd->query('SELECT `pseudo` FROM `utilisateur` WHERE `pseudo` = \''.$pseudo.'\' ');
		$donnees = $reponse->fetch();

		if ($pseudo!=$donnees['pseudo']) { 
			$bdd->exec('UPDATE utilisateur SET pseudo =  \''.$pseudo.'\' WHERE pseudo = \''.$_SESSION['pseudo'].'\'');
			$_SESSION['pseudo']=$pseudo;
		}
		else {
			$erreur = $erreur+1;
		}

	}

	if($password!="") {
		if ($password==$password2) {
			include('../connection_PDO.php');
			$bdd->exec('UPDATE utilisateur SET password =  \''.$password.'\' WHERE pseudo = \''.$_SESSION['pseudo'].'\'');	
			$_SESSION['password']=$password;		
		} else {
			$erreur = $erreur+2;
		}
	}

	if($email!="") {
		include('../connection_PDO.php');
		$bdd->exec('UPDATE utilisateur SET mail =  \''.$email.'\' WHERE pseudo = \''.$_SESSION['pseudo'].'\'');	
		$_SESSION['mail']=$email;		
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


include('admin_tete.php'); 


	if ($erreur == 1 ) {
		echo "<div id='alert'>";
		echo	"<h2>/!\ Erreur /!\</h2>";
		echo	"<p> Pseudo déjà existant </p>";
		echo "</div>";
	}

	if ($erreur == 2 ) {
		echo "<div id='alert'>";
		echo	"<h2>/!\ Erreur /!\</h2>";
		echo	"<p> Erreur de password </p>";
		echo "</div>";
	}

	if ($erreur == 3 ) {
		echo "<div id='alert'>";
		echo	"<h2>/!\ Erreur /!\</h2>";
		echo	"<p> Pseudo déjà existant </p>";
		echo	"<p> Erreur de password </p>";
		echo "</div>";
	}

?>

<center>
	<div id="ModifProfile">
		<form method="post" action="#">	
			<table>
				<tr>
					<td align="center">
						<label>Pseudo</label> 
					</td>
					<td>
						<input type="text" name="pseudo" placeholder="Pseudo" />
					</td>	
				</tr>
				<tr>
					<td align="center">
						<label>Password</label>
					</td>
					<td>
						<input type="Password" name="password" placeholder="Password" />
					</td>
				</tr>
				<tr>
					<td align="center">
						<label>Confirmer</label>
					</td>
					<td>
						<input type="Password" name="password2" placeholder="Retaper votre Password" />
					</td>
				</tr>
				<tr>
					<td align="center">
						<label>Email</label>
					</td>
					<td>
						<input type="Email" name="email" placeholder="Email" />
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="submit" name="Envoyer" value="Envoyer" >
					</td>
				</tr>
			</table>
		</form>
		<!-- Fin Formulaire -->			
	</div>

	<div id="Profile">
		<table>
			<tr>
				<td align="center">
					<label>Pseudo</label> 
				</td>
				<td>
					<?php echo $_SESSION['pseudo']; ?>
				</td>	
			</tr>
			<tr>
				<td align="center">
					<label>Password</label>
				</td>
				<td>
					<?php echo $_SESSION['password']; ?>
				</td>
			</tr>
			<tr>
				<td align="center">
					<label>Email</label>
				</td>
				<td>
					<?php echo $_SESSION['mail']; ?>
				</td>
			</tr>
		</table>		
	</div>
</center>

<?php
	include('admin_pied.php'); 
?>