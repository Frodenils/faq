<?php
	session_start();	

	if(($_SESSION['pseudo'] == "" )or($_SESSION['pseudo'] == NULL)){
		header('Location: ../identification.php');     
	}	


	$nomFAQ = "";
	$ssFAQ = "";
	$visibleFAQ = "";
	$date = date("Y-m-d H:i:s");
	$erreur = 0;
	$supp = 0;
	$id_up = 0;
	$id_down = 0;
	$visible = 0;
	$modinomFAQ="";
	$modiVisibleFAQ="";
	$idAmodif = 0;


	if (isset($_POST['nomFAQ']))
		$nomFAQ=$_POST['nomFAQ'];
	if (isset($_POST['ssFAQ']))
		$ssFAQ=$_POST['ssFAQ'];
	if (isset($_POST['visibleFAQ']))
		$visibleFAQ=$_POST['visibleFAQ'];

	if (isset($_GET['supp']))
		$supp=$_GET['supp'];
	if (isset($_GET['visible']))
		$visible=$_GET['visible'];

	if (isset($_POST['id_up']))
		$id_up=$_POST['id_up'];
	if (isset($_POST['id_down']))
		$id_down=$_POST['id_down'];

	if (isset($_POST['modinomFAQ']))
		$modinomFAQ=$_POST['modinomFAQ'];
	if (isset($_POST['modiVisibleFAQ']))
		$modiVisibleFAQ=$_POST['modiVisibleFAQ'];
	if (isset($_POST['idAmodif']))
		$idAmodif=$_POST['idAmodif'];
 

//pour ajouet FAQ //////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// pour Super utilisateur 
//		peut ajouter une FAQ avec un nom different avec celle déja existante et donne les droits dessus
//   	sinon msg d'erreur
//	
// autre utilisateur
//		peut ajouter une sous-faq si on a les doits dessus et que le nom n'existe pas déja ds la basse de donnée
//		donne les droit de cette ss-faq a tout les utilisateur qui on les droits sur la faq a laquelle la ss-faq appartient (dont le Super utilisateur) 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if($nomFAQ!=""){

		include('../connection_PDO.php');

		$result = $bdd->query('SELECT nom FROM faq where nom = \''.$nomFAQ.'\'  ')->fetch();

		if ($nomFAQ!=$result['nom']){

			if($ssFAQ == ""){
				
				if($_SESSION['droit'] == '0' ){
					// Ajouter la FAQ
					echo "<script>alert('".$_SESSION['droit']."')</script>";

					$result = $bdd->query("SELECT ordre_faq FROM faq where id_sous_faq is NULL ");
					
					$ordreFAQ = $result->rowCount();
					$ordreFAQ = $ordreFAQ+1;

					$bdd->exec('INSERT into faq (nom,ordre_faq,date_faq,visible) values (\''.$nomFAQ.'\',\''.$ordreFAQ.'\',\''.$date.'\',\''.$visibleFAQ.'\') ');

					// Ajouter le droit au Super admin
					$recupIDfaq= $bdd->query('SELECT id_faq FROM faq where nom = \''.$nomFAQ.'\' ')->fetch();

					
					$bdd->exec('INSERT into droituti (id_uti,id_faq) values (1,'.$recupIDfaq['id_faq'].')');
				}
				else {
					$erreur = 2 ;
				}
			}
			else {

				$idssFAQ = $bdd->query('SELECT id_faq FROM faq where nom = \''.$ssFAQ.'\'')->fetch();

				$ledroit = $bdd->query('SELECT id_faq from droituti where id_faq = '.$idssFAQ['id_faq'].' and id_uti ='.$_SESSION['id_uti'])->fetch();

				if($ledroit['id_faq'] != Null ){

					$result = $bdd->query('SELECT ordre_faq FROM faq where id_sous_faq = '.$idssFAQ['id_faq'].' ');
					
					$ordreFAQ = $result->rowCount();
					$ordreFAQ = $ordreFAQ+1;

					$bdd->exec('INSERT into faq  (id_sous_faq,nom,ordre_faq,date_faq,visible) values ('.$idssFAQ['id_faq'].',\''.$nomFAQ.'\',\''.$ordreFAQ.'\', \''.$date.'\',\''.$visibleFAQ.'\') ');

					//echo $idssFAQ['id_faq'].'/'.$nomFAQ.'/'.$ordreFAQ.'/'.$date.'/'.$visibleFAQ;	

					/* Ajouter les droit sur les sous-faq au admin des faq en question */

					$id2lassfaq = $bdd->query('SELECT id_faq FROM faq where id_sous_faq = '.$idssFAQ['id_faq'].' and nom = \''.$nomFAQ.'\' and ordre_faq = \''.$ordreFAQ.'\' ')->fetch();
				

					$id2luti = $bdd->query('SELECT distinct(droituti.id_uti) FROM faq,droituti WHERE faq.id_sous_faq = droituti.id_faq and faq.id_sous_faq = '.$idssFAQ['id_faq'].' ');

					while ($donnees=$id2luti->fetch()){

						$bdd->exec('INSERT into droituti (id_uti,id_faq) values ('.$donnees['id_uti'].','.$id2lassfaq['id_faq'].')  ');
					}
				}
				else {
					$erreur = 2;
				}
			}
		} else {
			$erreur = 1;
		}	
	}


// pour Supprimer F.A.Q de la base de donné //////////////////////////////////////////////////////////////////////////////////////
//
// Si on posède les droit
// supprime un faq et remet l'ordre correctement (sans trou ds les n°)
//  	si elle a des sous-faq
//			-supp les droits de tout le monde (même le super utilisateur) sur ces ss-faq
//			-puis supp les ss-faq en question
//		puis
//			-supp les droits de tout le monde (même le super utilisateur) sur la faq en question
//			-enfin supp la faq
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if ($supp != 0){
		include('../connection_PDO.php');

		$ledroit = $bdd->query('SELECT id_faq from droituti where id_faq = '.$supp.' and id_uti ='.$_SESSION['id_uti'])->fetch();

		if($ledroit['id_faq'] != Null ){

			$existe = $bdd->query('SELECT id_faq,id_sous_faq FROM faq WHERE id_faq = '.$supp )->fetch();

			if ($supp == $existe['id_faq'] ){

				$qui = $bdd->query('SELECT ordre_faq FROM faq where id_faq ='.$supp )->fetch();

				$nbr =$qui['ordre_faq'];

				if ($existe['id_sous_faq'] != null ){ //pour modifier que l'ordre des faqs ou des sous_faqs selon se qui est effacé

					$who = $bdd->query('SELECT id_faq FROM faq where ordre_faq > '.$nbr.' and id_sous_faq = \''.$existe['id_sous_faq'].'\' ');
				} else {
					$who = $bdd->query('SELECT id_faq FROM faq where ordre_faq > '.$nbr.' and id_sous_faq is null ');

					
					$suppretion1 = $bdd->query('SELECT id_faq FROM faq WHERE id_sous_faq ='.$supp ); 

					foreach ($suppretion1 as $data){

						$bdd->exec('DELETE from droituti where id_faq = '.$data['id_faq']); // Efface les droits lier au sous-faq

						$bdd->exec('DELETE from faq where  id_faq = '.$data['id_faq']); // Efface les sous_faq si elle existe
					}
				}

				while($donnees = $who->fetch()){ // pour remettre l'ordre correctement

					$decreOrdre = $bdd->query('SELECT ordre_faq FROM faq where id_faq = \''.$donnees['id_faq'].'\'  ')->fetch();
		 			$var = $decreOrdre['ordre_faq'];
					$var = $var-1;

					$bdd->exec('UPDATE faq SET ordre_faq = '.$var .' WHERE id_faq =\''.$donnees['id_faq'].'\' ');
				}

				$suppretion2 = $bdd->exec('DELETE FROM contenuefaq WHERE id_faq ='.$supp ); // Efface le contenue de la faq s'il existe

				$bdd ->exec('DELETE from droituti where id_faq = '.$supp ); // Efface les droits lier a la faq

				$suppretion0 = $bdd->exec('DELETE FROM faq WHERE id_faq ='.$supp );  // Efface la faq
			}
		}
		else {
			$erreur = 2;
		}
	}


// pour monter/décendre l'ordre de 1 /////////////////////////////////////////////////////////////////////////////////////
//
// Si on posède les droits sur la ss-faq
// monte ou descend l'oredre de la ss-faq de 1
// et descend ou monte l'ordre de la ss-faq dessus ou dessous de 1
// l'ordre ne peut pas etre >1
//
// même chose pour les faq sauf que seul le super utilisateur en a le droit
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if ($id_up != 0) {
		include('../connection_PDO.php');

		$qui = $bdd->query('SELECT ordre_faq,id_sous_faq,id_faq FROM faq where id_faq = \''.$id_up.'\'  ')->fetch();

		$ledroit = $bdd->query('SELECT id_faq from droituti where id_faq = '.$qui['id_faq'].' and id_uti ='.$_SESSION['id_uti'])->fetch();

		if($ledroit['id_faq'] != Null ){

	 		$var = $qui['ordre_faq'];

	 		if ($var > 1){

		 		$var2 = $var-1;

		 		//echo '<script>alert(\''.$var.'\')</script>';
		 		//echo '<script>alert(\''.$var2.'\')</script>'; 

		 		if ($qui['id_sous_faq'] != null){

		 			$bdd->exec('UPDATE faq SET ordre_faq = '.$var.' where id_sous_faq = '.$qui['id_sous_faq'].' and ordre_faq ='.$var2.' and id_faq != \''.$id_up.'\'  ');

		 			$bdd->exec('UPDATE faq SET ordre_faq = '.$var2.' where id_sous_faq = '.$qui['id_sous_faq'].' and ordre_faq ='.$var.' and id_faq = \''.$id_up.'\'  ');
		 		} else {

		 			if($_SESSION['droit'] == '0' ){

		 				$bdd->exec('UPDATE faq SET ordre_faq = '.$var.' where id_sous_faq is null and ordre_faq ='.$var2.' and id_faq != \''.$id_up.'\'  ');

		 				$bdd->exec('UPDATE faq SET ordre_faq = '.$var2.' where id_sous_faq is null and ordre_faq ='.$var.' and id_faq = \''.$id_up.'\'  ');
		 			}
					else {
						$erreur = 2;
					}
		 		}
		 	}
		}
		else {
			$erreur = 2;
		}
	}

	if ($id_down != 0) {
		include('../connection_PDO.php');

		$qui = $bdd->query('SELECT ordre_faq,id_sous_faq,id_faq FROM faq where id_faq = \''.$id_down.'\'  ')->fetch();

		$ledroit = $bdd->query('SELECT id_faq from droituti where id_faq = '.$qui['id_faq'].' and id_uti ='.$_SESSION['id_uti'])->fetch();

		if($ledroit['id_faq'] != Null ){

	 		$var = $qui['ordre_faq'];
	 		$var2 = $var+1;

	 		if ($qui['id_sous_faq'] != null){

	 			$bdd->exec('UPDATE faq SET ordre_faq = '.$var.' where id_sous_faq = '.$qui['id_sous_faq'].' and ordre_faq ='.$var2.' and id_faq != \''.$id_down.'\'  ');

	 			$bdd->exec('UPDATE faq SET ordre_faq = '.$var2.' where id_sous_faq = '.$qui['id_sous_faq'].' and ordre_faq ='.$var.' and id_faq = \''.$id_down.'\'  ');
	 		} else {

	 			if($_SESSION['droit'] == '0' ){

	 				$bdd->exec('UPDATE faq SET ordre_faq = '.$var.' where id_sous_faq is null and ordre_faq ='.$var2.' and id_faq != \''.$id_down.'\'  ');

	 				$bdd->exec('UPDATE faq SET ordre_faq = '.$var2.' where id_sous_faq is null and ordre_faq ='.$var.' and id_faq = \''.$id_down.'\'  ');
	 			}
				else {
					$erreur = 2;
				}
	 		}
	 	}
	 	else {
			$erreur = 2;
		}
	}



// pour inverser la visibilité //////////////////////////////////////////////////////////////////////////////
//
//	si L'utilisateur a les droits sur la faq inverse la visibilité
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if($visible != 0){
	
		include('../connection_PDO.php');

		$who = $bdd->query('SELECT visible,id_faq FROM faq WHERE id_faq=\''.$visible.'\'  ')->fetch();

		$ledroit = $bdd->query('SELECT id_faq from droituti where id_faq = '.$who['id_faq'].' and id_uti ='.$_SESSION['id_uti'])->fetch();

		if($ledroit['id_faq'] != Null ){

			if ($who['visible']=='public'){
				$bdd->exec('UPDATE faq SET visible=\'privée\' WHERE id_faq=\''.$visible.'\' ');
			} else {
				$bdd->exec('UPDATE faq SET visible=\'public\' WHERE id_faq=\''.$visible.'\' ');
			}
		}
	}


// pour modier une F.A.Q ////////////////////////////////////////////////////////////////////////////////////

	if ($idAmodif!=0){

		include('../connection_PDO.php');

		$who = $bdd->query('SELECT nom,visible FROM faq where id_faq = \''.$idAmodif.'\'  ')->fetch();

		if (($modinomFAQ!=$who['nom'])&($modinomFAQ!="")){

			$qui = $bdd->query('SELECT nom FROM faq where nom = \''.$modinomFAQ.'\'  ')->fetch();

			if($modinomFAQ!=$qui['nom']){

				$bdd->exec('UPDATE faq SET nom=\''.$modinomFAQ.'\' WHERE id_faq=\''.$idAmodif.'\' ');
			} else {
				$erreur = $erreur +1;
			}
		}

		if ($modiVisibleFAQ!=""){

			if ($modiVisibleFAQ!='public'){
				$bdd->exec('UPDATE faq SET visible=\'privée\' WHERE id_faq=\''.$idAmodif.'\' ');
			} else {
				$bdd->exec('UPDATE faq SET visible=\'public\' WHERE id_faq=\''.$idAmodif.'\' ');
			}
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	include('admin_tete.php'); 

	if ($erreur == 1 ) {
		echo "<div id='alert'>";
		echo	"<h2>/!\ Erreur /!\</h2>";
		echo	"<p> nom de F.A.Q déjà existant </p>";
		echo "</div>";
	}

	if ($erreur == 2 ) {
		echo "<div id='alert'>";
		echo	"<h2>/!\ Erreur /!\</h2>";
		echo	"<p> Vous n'avez pas le droit de faire cela </p>";
		echo "</div>";
	}

?>


<script type="text/javascript">
	$(document).ready(function(){
		$('#SelectModifFAQs').change(function(){
			var selectdFAQ = $('#SelectModifFAQs option:selected');
			$('#FAQaModifier').html(
				//'FAQ = ' + selectdFAQ.text()
				'<form action="plusDEfaq.php" method="post">'+
 				 'Nom F.A.Q : <input type="text" name="modinomFAQ" value='+selectdFAQ.text()+'>'+
 			 	 '<select name="modiVisibleFAQ">'+
					'<option>privée</option>'+
					'<option>public</option>'+
				'</select>'+		
				'<input type="hidden" name="idAmodif" value='+selectdFAQ.val()+' />'+				
  				'<input type="submit">'+
			'</form>'	
			);
		});
	});
</script>

<center>
	<div id="plusFAQ">
		<form method="post" action="plusDEfaq.php">	
			<table>
				<tr>
					<td>
						nom F.A.Q
					</td>
					<td>
						F.A.Q mère
					</td>
					<td>
						Visiblilité
					</td>
				</tr>
				<tr>
					<td>
						<input type="text" name="nomFAQ" placeholder="nom" />
					</td>
					<td>
						<select name="ssFAQ">
							<option></option>	
							<?php
        						include('../connection_PDO.php');
 
								$reponse = $bdd->query('SELECT faq.nom,faq.id_faq FROM faq,droituti,utilisateur where faq.id_faq=droituti.id_faq and droituti.id_uti=utilisateur.id_uti and utilisateur.pseudo = \''.$_SESSION['pseudo'].'\' and id_sous_faq is NULL');
					 
								while ($donnees = $reponse->fetch())
								{
									?>
					       			<option> <?php echo $donnees['nom']; ?></option>
									<?php
								}

								$reponse->closeCursor(); 
							?>
						</select>
					</td>
					<td>
						<select name="visibleFAQ">
							<option>privée</option>
							<option>public</option>
						</select>
					</td>
					<td>
						<input type="submit" name="Envoyer" value="Ajouter" >
					</td>
				</tr>
			</table>
		</form>		
	</div>

	<div id="LesFAQs">
		<table>
			<tr id="teteFAQ">
				<td>F.A.Q</td>
				<td>sous-F.A.Q</td>
				<td>Visible</td>
				<td>date</td>
				<td>ordre</td>
				<td></td>
			</tr>
			<?php 
				include('../connection_PDO.php');

				$taille = $bdd->query('SELECT nom,id_faq,visible,date_faq FROM faq WHERE id_sous_faq IS NULL ORDER BY ordre_faq asc');

				while ($donnees = $taille->fetch()){
					?>
					<tr>
						<td><?php echo $donnees['nom']; ?></td>
						<td> -- </td>
						<?php echo '<td class=\''.$donnees['visible'].'\'><a href=\'plusDEfaq.php?visible='.$donnees['id_faq'].'\'>'.$donnees['visible']; ?></a></td>
						<td> <?php echo $donnees['date_faq']; ?> </td>
						<td>
							<form method="post" action="plusDEfaq.php" >
                   				<input type="hidden" name="id_up" value= <?php echo $donnees['id_faq']; ?> >
                    			<input type="submit" name="Submit" value="↑">
                			</form>
							<form method="post" action="plusDEfaq.php" >
                   				<input type="hidden" name="id_down" value= <?php echo $donnees['id_faq']; ?> >
                    			<input type="submit" name="Submit" value="↓">
                			</form>					
						</td>
						<td><a href="plusDEfaq.php?supp=<?php echo $donnees['id_faq'];?> " onClick="return confirm('Supprimer cet F.A.Q ?');" >Supp</a></td>
					</tr>
					<?php
						$taille2 = $bdd->query('SELECT nom,visible,date_faq,id_faq FROM faq WHERE id_sous_faq = '.$donnees['id_faq'].' ORDER BY ordre_faq asc');

						while ($donnees2 = $taille2->fetch()){
							?>
								<tr>
									<td> -- </td>
									<td> <?php echo $donnees2['nom']; ?> </td>
								 	<?php echo '<td class=\''.$donnees2['visible'].'\'><a href=\'plusDEfaq.php?visible='.$donnees2['id_faq'].'\'>'.$donnees2['visible']; ?></a></td>
								 	<td> <?php echo $donnees2['date_faq']; ?> </td>
									<td>
										<form method="post" action="plusDEfaq.php" >
			                   				<input type="hidden" name="id_up" value= <?php echo $donnees2['id_faq']; ?> >
			                    			<input type="submit" name="Submit" value="↑">
			                			</form>
										<form method="post" action="plusDEfaq.php" >
			                   				<input type="hidden" name="id_down" value= <?php echo $donnees2['id_faq']; ?> >
			                    			<input type="submit" name="Submit" value="↓">
			                			</form>	
									</td>
									<td><a href="plusDEfaq.php?supp=<?php echo $donnees2['id_faq'];?> " onClick="return confirm('Supprimer cet F.A.Q ?');" >Supp</a></td>
								</tr>
							<?php
						}

						$taille2->closeCursor();
					?>
					<?php
				}

				$taille->closeCursor();
			?>
		</table>
	</div>


	<div id="ModifFAQs">
		<label>Modifier une F.A.Q :</label>
		<select id="SelectModifFAQs">
			<option></option>
			<?php
				include('../connection_PDO.php');

				$reponse = $bdd->query('SELECT faq.nom,faq.id_faq FROM faq,droituti,utilisateur where faq.id_faq=droituti.id_faq and droituti.id_uti=utilisateur.id_uti and utilisateur.pseudo = \''.$_SESSION['pseudo'].'\' ');
	 
				while ($donnees = $reponse->fetch())
				{
					?>
	       			<option value='<?php echo $donnees['id_faq']; ?>' > <?php echo $donnees['nom']; ?></option>
					<?php
				}

				$reponse->closeCursor(); 
			?>
		</select>
		<div id="FAQaModifier" >

		</div>
	</div>

</center>

<?php
	include('admin_pied.php'); 
?>