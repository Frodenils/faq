<?php
	session_start();	

	//echo '<script>alert(\''.$_SESSION['id_uti'].'\');</script>';

	if(($_SESSION['pseudo'] == "" )or($_SESSION['pseudo'] == NULL)){
		header('Location: ../identification.php');     
	}

	$date = date("Y-m-d H:i:s");
	$id_seleced = 0 ;
	$visible = 0;
	$supp = 0;
	$id_down = 0;
	$id_up = 0;
	$Modif_gFAQ_question = "";
	$Modif_gFAQ_reponse = "";
	$id_modif = 0;
	$ajouter_question = "";
	$ajouter_reponse = "";
	$ajouter_visible = "";
	$ajouter_place = 0;
	$ajouter_dans_faq = 0;


	if (isset($_GET['id_seleced']))
		$id_seleced=$_GET['id_seleced'];
	if (isset($_POST['id_seleced']))
		$id_seleced=$_POST['id_seleced'];

	if (isset($_GET['visible']))
		$visible=$_GET['visible'];
	if (isset($_GET['supp']))
		$supp=$_GET['supp'];

	if (isset($_POST['id_down']))
		$id_down=$_POST['id_down'];
	if (isset($_POST['id_up']))
		$id_up=$_POST['id_up'];

	if (isset($_POST['Modif_gFAQ_question']))
		$Modif_gFAQ_question=$_POST['Modif_gFAQ_question'];
		$Modif_gFAQ_question=addslashes($Modif_gFAQ_question);

	if (isset($_POST['Modif_gFAQ_reponse']))
		$Modif_gFAQ_reponse=$_POST['Modif_gFAQ_reponse'];
		$Modif_gFAQ_reponse=addslashes($Modif_gFAQ_reponse);

	if (isset($_POST['id_modif']))
		$id_modif=$_POST['id_modif'];

	if (isset($_POST['ajouter_question']))
		$ajouter_question=$_POST['ajouter_question'];
		$ajouter_question=addslashes($ajouter_question); // code pour gérer les apotrophe
	
	if (isset($_POST['ajouter_reponse']))
		$ajouter_reponse=$_POST['ajouter_reponse'];
		$ajouter_reponse=addslashes($ajouter_reponse);

	if (isset($_POST['ajouter_visible']))
		$ajouter_visible=$_POST['ajouter_visible'];
	if (isset($_POST['ajouter_place']))
		$ajouter_place=$_POST['ajouter_place'];
	if (isset($_POST['ajouter_dans_faq']))
		$ajouter_dans_faq=$_POST['ajouter_dans_faq'];
	


//echo '<script>alert(\''.$id_seleced.'\')</script>';


// pour inverser la visibilité //////////////////////////////////////////////////////////////////////////////
//
// si on posède les droit sur la faq
// pour mettre la visibilité en "public" si elle est en "privée"
// sinon la mettre en "privée" si elle est en "public"
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if($visible != 0){
	
		include('../connection_PDO.php');

		//echo '<script>alert(\'test1\')</script>';

		$who = $bdd->query('SELECT visible,id_faq FROM contenuefaq WHERE id_cont =\''.$visible.'\'  ')->fetch();

		$ledroit = $bdd->query('SELECT id_faq from droituti where id_faq = '.$who['id_faq'].' and id_uti ='.$_SESSION['id_uti'] )->fetch();

		if($ledroit['id_faq'] != Null ){

			if ($who['visible']=='public'){
				
				$bdd->exec('UPDATE contenuefaq SET visible=\'privée\' WHERE id_cont =\''.$visible.'\' ');
			} else {
				$bdd->exec('UPDATE contenuefaq SET visible=\'public\' WHERE id_cont =\''.$visible.'\' ');
			}

		}
	}

// pour Supprimer F.A.Q de la base de donné /////////////////////////////////////////////////////////////
//
// Si on a les droits sur la faq (gérer en amont (l'utilisateur ne peut pas avec au contenue dont il n'a pas de droit))
// Supprimer le contenue desirer
// Et remet l'ordre des autre contenue correctement (avec n° qui se suivre)
//////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($supp != 0){
		include('../connection_PDO.php');

		//echo '<script>alert(\'test2\')</script>';

		$existe = $bdd->query('SELECT id_cont,id_faq FROM contenuefaq where id_cont ='.$supp )->fetch();

		if ($supp == $existe['id_cont']){


			$qui = $bdd->query('SELECT ordre_cont FROM contenuefaq where id_cont ='.$supp )->fetch();

			$nbr =$qui['ordre_cont'];
			//echo '<script>alert(\''.$nbr.'\')</script>';

			$who = $bdd->query('SELECT id_cont FROM contenuefaq where ordre_cont > '.$nbr.' and id_faq = \''.$existe['id_faq'].'\' ');

			while($donnees = $who->fetch()){

				$decreOrdre = $bdd->query('SELECT ordre_cont FROM contenuefaq where id_cont = \''.$donnees['id_cont'].'\'  ')->fetch();
	 			$var = $decreOrdre['ordre_cont'];
				$var = $var-1;

				$bdd->exec('UPDATE contenuefaq SET ordre_cont = '.$var .' WHERE id_cont =\''.$donnees['id_cont'].'\' ');
			}

			$suppretion = $bdd->exec('DELETE FROM contenuefaq WHERE id_cont ='.$supp );
		}
	}




// pour monter/décendre l'ordre de 1 ///////////////////////////////////////////////////////////////////////////
	if ($id_up != 0) {
		include('../connection_PDO.php');

		$qui = $bdd->query('SELECT ordre_cont FROM contenuefaq where id_cont = \''.$id_up.'\'  ')->fetch();
 		$var = $qui['ordre_cont'];

 		if ($var > 1){

	 		$var2 = $var-1;

	 		//echo '<script>alert(\''.$var.'\')</script>';
	 		//echo '<script>alert(\''.$var2.'\')</script>'; 

	 		

	 		$bdd->exec('UPDATE contenuefaq SET ordre_cont = '.$var.' where  ordre_cont ='.$var2.' and id_cont != \''.$id_up.'\'  ');

	 		$bdd->exec('UPDATE contenuefaq SET ordre_cont = '.$var2.' where  ordre_cont ='.$var.' and id_cont = \''.$id_up.'\'  ');
	 		
	 	}
	}

	if ($id_down != 0) {
		include('../connection_PDO.php');

		$qui = $bdd->query('SELECT ordre_cont FROM contenuefaq where id_cont = \''.$id_down.'\'  ')->fetch();
 		$var = $qui['ordre_cont'];
 		$var2 = $var+1;

 		 

 		$bdd->exec('UPDATE contenuefaq SET ordre_cont = '.$var.' where  ordre_cont ='.$var2.' and id_cont != \''.$id_down.'\'  ');

 		$bdd->exec('UPDATE contenuefaq SET ordre_cont = '.$var2.' where ordre_cont ='.$var.' and id_cont = \''.$id_down.'\'  ');
	}

// pour modifier une question ou une reponse /////////////////////////////////////////////////////////////

	if ($id_modif != 0 ) {	
		if ($Modif_gFAQ_reponse != ""){
			include('../connection_PDO.php');

			$diff = $bdd->query('SELECT reponse FROM contenuefaq where id_cont = \''.$id_modif.'\'  ')->fetch();

			if ($Modif_gFAQ_reponse != $diff['reponse'] ){
				$bdd->exec('UPDATE contenuefaq SET reponse = \''.$Modif_gFAQ_reponse.'\' where id_cont = \''.$id_modif.'\'  ');
			}
		}

		if ($Modif_gFAQ_question != ""){
			include('../connection_PDO.php');

			$diff2 = $bdd->query('SELECT question FROM contenuefaq where id_cont = \''.$id_modif.'\'  ')->fetch();

			if ($Modif_gFAQ_question != $diff2['question'] ){
				$bdd->exec('UPDATE contenuefaq SET question = \''.$Modif_gFAQ_question.'\' where id_cont = \''.$id_modif.'\'  ');
			}
		}
	}

// pour ajouter question/reponse dans une faq ////////////////////////////////////////////////////////////

	if($ajouter_dans_faq != 0){

		include('../connection_PDO.php');

		$ajouter_place = $ajouter_place+1;
		//echo '<script>alert(\''.$ajouter_place.'\')</script>';

		$ordre = $bdd->query('SELECT ordre_cont,id_cont from contenuefaq where id_faq = '.$ajouter_dans_faq.' and ordre_cont >= '.$ajouter_place );

		while ($donnees = $ordre->fetch()){
			$upcreOrdre = $bdd->query('SELECT ordre_cont FROM contenuefaq where id_cont = \''.$donnees['id_cont'].'\'  ')->fetch();
 			$var = $upcreOrdre['ordre_cont'];
			$var = $var+1;

			$bdd->exec('UPDATE contenuefaq SET ordre_cont = '.$var .' WHERE id_cont =\''.$donnees['id_cont'].'\' ');
		}
		 

		$bdd->exec('insert into contenuefaq (id_faq,question,reponse,ordre_cont,date_cont,visible) values ('.$ajouter_dans_faq.',\''.$ajouter_question.'\',\''.$ajouter_reponse.'\',\''.$ajouter_place.'\',\''.$date.'\',\''.$ajouter_visible.'\') ');
	}


/////////////////////////////////////////////////////////////////////////////////////////////////////////




	include('admin_tete.php'); 
?>

<script type="text/javascript">
	$(document).ready(function(){
		$('#SelectfFAQs').change(function(){
			var selectdFAQ = $('#SelectfFAQs option:selected');
			$('#contenueFAQ').load('gFAQ2.php?id_seleced='+selectdFAQ.val());
		});
	});
</script>


<h2>Select une F.A.Q :</h2>

<select id="SelectfFAQs">
	<option></option>
	<?php
		include('../connection_PDO.php');

		$reponse = $bdd->query('SELECT faq.nom,faq.id_faq FROM faq,droituti,utilisateur where faq.id_faq=droituti.id_faq and droituti.id_uti=utilisateur.id_uti and utilisateur.pseudo = \''.$_SESSION['pseudo'].'\' ');

		while ($donnees = $reponse->fetch())
		{
			if ($id_seleced == $donnees['id_faq']){

				?>
   				<option value='<?php echo $donnees['id_faq']; ?>' selected> <?php echo $donnees['nom']; ?></option>
				<?php
			} else {

				?>
   				<option value='<?php echo $donnees['id_faq']; ?>' > <?php echo $donnees['nom']; ?></option>
				<?php
			}
		}

		$reponse->closeCursor(); 
	?>
</select>

 <div id="AfficheEle"> 
	
		
		<div id="contenueFAQ" >
			<?php

				if($id_seleced != 0){
					
					include('gFAQ2.php');
				}
			?>
		</div>
	
 	</div> 


<?php
	include('admin_pied.php'); 
?>