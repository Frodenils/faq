<?php
	session_start();	

	if(($_SESSION['pseudo'] == "" )or($_SESSION['pseudo'] == NULL)){
		header('Location: ../identification.php');     
	}	
	
	include('admin_tete.php'); 



	$pseudo = "";
	$password = "";
	$password2 = "";
	$email = "";
	$error = 0 ;
	$id_uti_supp = 0;
	
	if (isset($_POST['pseudo']))
		$pseudo=$_POST['pseudo'];
	if (isset($_POST['password']))
		$password=$_POST['password'];
	if (isset($_POST['password2']))
		$password2=$_POST['password2'];
	if (isset($_POST['email']))
		$email=$_POST['email'];
	if (isset($_POST['id_uti_supp']))
		$id_uti_supp=$_POST['id_uti_supp'];

	

// Pour ajouter un utilisateur ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// ajoute un utilisateur qui n'existe pas (dont le pseudo n'est pas utiliser)
// et ajouter les droit à cette nouveau utilisateur sur les faq et sous-faq dont la faq est selectionner
// si avoir un mail ou des doits sur une faq n'ast pas obligatoire
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if ($pseudo != ""){
		
		include('../connection_PDO.php');

		$pseudo_existe = $bdd->query('SELECT pseudo from utilisateur where pseudo = \''.$pseudo.'\' ')->fetch(); // cherche dans la table si il existe un pseudo comme celui donné

		if ($pseudo != $pseudo_existe['pseudo']){ // test si le pseudo n'existe pas déja

			if ($password!= ""){ // test si il y a un password

				if ($password == $password2){ // test si la confirmation du password est corrct

					if(empty($_POST['SelectFaq'])){ // si pas de droit a ajouter a l'utilisateur

						$bdd->exec('INSERT into utilisateur(pseudo,mail,password) values (\''.$pseudo.'\',\''.$email.'\',\''.$password.'\')');

						echo '<script>alert(\'Vous avez créer un nouveau utilisateur SANS droit\')</script>';
					}else{ // avec des droits a ajouter a l'utilisateur

						$bdd->exec('INSERT into utilisateur(pseudo,mail,password) values (\''.$pseudo.'\',\''.$email.'\',\''.$password.'\')');

						$id2luti = $bdd->query('SELECT id_uti from utilisateur where pseudo = \''.$pseudo.'\'')->fetch();

						
						foreach ($_POST['SelectFaq'] as $id_faq_Selected){ /* pour ajouter les droits sur chaque element coché dans le form (chaque faq) a l'utilisateur */
							
							$bdd->exec('INSERT into droituti(id_uti,id_faq) values ('.$id2luti['id_uti'].','.$id_faq_Selected.')');

							$id2ssfaq = $bdd -> query('SELECT id_faq from faq where id_sous_faq = '.$id_faq_Selected );

							foreach ( $id2ssfaq as $data) { /* pour ajouter les droit des sous faq dont l'utilisateur est l'admin */ 

								$bdd->exec('INSERT into droituti(id_uti,id_faq) values ('.$id2luti['id_uti'].','.$data['id_faq'].')');

								echo '<script>alert(\'Vous avez créer un nouveau utilisateur\')</script>';
							}		
						}
					}
				}else{
					$error = 3;
				}
			}else{
				$error = 2 ;
			}
		}else{
			$error = 1;
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



// Pour Supprimer un utilisateur ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// va supprimer toutes les ligne avec l'utilisateur dans la table "droituti"
// puis va supprimer l'utilisateur de la table "utilisateur"
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if ($id_uti_supp != 0){

		include('../connection_PDO.php');

		$bdd->exec('DELETE from droituti where id_uti = '.$id_uti_supp); // supprimer les droits (les clefs externes)

		$bdd->exec('DELETE from utilisateur where id_uti = '.$id_uti_supp); //supprime l'utilisateur de la teble
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////










// les message d'erreur /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if ($error == 1 ) {
		echo "<div id='alert'>";
		echo	"<h2>/!\ Erreur /!\</h2>";
		echo	"<p> le psuedo déjà existant </p>";
		echo "</div>";
	}

	if ($error == 2 ) {
		echo "<div id='alert'>";
		echo	"<h2>/!\ Erreur /!\</h2>";
		echo	"<p> pas de password </p>";
		echo "</div>";
	}

	if ($error == 3 ) {
		echo "<div id='alert'>";
		echo	"<h2>/!\ Erreur /!\</h2>";
		echo	"<p> password et confimation de password différent </p>";
		echo "</div>";
	}
?>

<label id="labelPlusProfile"> Ajouter un profil : </label>

<center>
	<div id="Plus2Profile">
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
						<label>droit de l'utilisateur :</label>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<?php 
							
							include('../connection_PDO.php');

							$lesFAQs = $bdd->query('SELECT id_faq,nom From faq where id_sous_faq is null'); 

							while ($donnees = $lesFAQs->fetch()){
								?>
									<input type="checkbox" name="SelectFaq[]" value="<?php echo $donnees['id_faq'] ?>" /><?php echo $donnees['nom'] ?>
								<?php
							}
						?>
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
</center>


<label id="labelPlusProfile"> Supprimer un profil : </label>

<center>

	<div id="Moins2Profile">

		<form action="#" method="post">
			<label> Selectionner l'utilisateur :</label>

			<select name="id_uti_supp">
				<option></option>
				<?php
					include('../connection_PDO.php');

					$reponse = $bdd->query('SELECT id_uti,pseudo From utilisateur Where droit is null');

					foreach ($reponse as $donnees){
						
						?>
			   			<option value='<?php echo $donnees['id_uti']; ?>'> <?php echo $donnees['pseudo']; ?> </option>
						<?php	
					}

					$reponse->closeCursor(); 
				?>
			</select>
			
			<input type="submit" name="Envoyer" value="Supprimer" onClick="return confirm('Voulez-vous supprimer cette utilisateur ?');" >
		</form>

	</div>
</center>





<?php
	include('admin_pied.php'); 
?>