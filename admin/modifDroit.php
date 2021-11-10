<?php
	session_start();

	if(($_SESSION['pseudo'] == "" )or($_SESSION['pseudo'] == NULL)){
		header('Location: ../identification.php');     
	}	


	$id_uti = 0;
	$id_faq = 0;
	$alesdroit = "";

	if(isset($_GET['id_uti']))
		$id_uti = $_GET['id_uti'] ;
	if(isset($_GET['id_faq']))
		$id_faq = $_GET['id_faq'] ;
	if(isset($_GET['alesdroit']))
		$alesdroit = $_GET['alesdroit'] ;

	
	//echo '<script>alert(\''.$alesdroit.'\');</script>';

// pour modif les droit ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// Donne les droits sur une faq et ses sous-faqs à l'utilisateur si il ne les a pas (ajoute dans la table 'droituti')
// sinon les lui retire (supprime dans la table 'droituti')
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if($alesdroit!=""){
		
		include('../connection_PDO.php');

		if ($alesdroit == 'non' ){

			$les_sous_faq = $bdd->query('SELECT id_faq from faq where id_sous_faq = '.$id_faq );

			foreach ($les_sous_faq as $id2sousFAQ) {
				
				$bdd->exec('INSERT into droituti(id_uti,id_faq) values  ('.$id_uti.','.$id2sousFAQ['id_faq'].')' );
			}

			$bdd->exec('INSERT into droituti(id_uti,id_faq) values ('.$id_uti.','.$id_faq.')' );

		}
		else{

			$les_sous_faq = $bdd->query('SELECT id_faq from faq where id_sous_faq = '.$id_faq );

			foreach ($les_sous_faq as $id2sousFAQ) {
				
				$bdd->exec('DELETE from droituti where id_faq = '.$id2sousFAQ['id_faq'].' and id_uti = '.$id_uti );
			}

			$bdd->exec('DELETE from droituti where id_faq = '.$id_faq.' and id_uti = '.$id_uti );
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	include('admin_tete.php'); 
?>

<label id="modifDroitUti">Modifier les droits utilisateur</label>

<center>
	<div id="modifDroitUti">

	<?php 

		include('../connection_PDO.php');

		$lesfaqs = $bdd->query('SELECT id_faq,nom from faq where id_sous_faq is null');

		$lesuti = $bdd ->query('SELECT id_uti,pseudo from utilisateur where droit is null');
	?>
		<table>
			<!-- Premiere ligne du tableau -->
			<tr id="teteDroit">
				<td>
					<!-- ici c'est la 1ere case de la 1ere ligne -->
				</td>

				<?php
					foreach ($lesfaqs as $data1) {
						echo '<td>'.$data1['nom'].'</td>';
					}
				//	unset($lesfaqs);
				?>
			</tr> 
			<!--fin de la premiere ligne du tableau  -->

			<?php
				foreach ($lesuti as $data2) {
					echo '<tr>';
					echo '<td id="teteDroit"><p>'.$data2['pseudo'].'</p></td>';

					$lesfaqs2 = $bdd->query('SELECT id_faq,nom from faq where id_sous_faq is null'); // doit etre remit sinon la boucle en sesous ne met pas de donnée

					foreach ($lesfaqs2 as $data1) {
						
						$droitExiste = $bdd->query('SELECT * from droituti where id_uti = '.$data2['id_uti'].' and id_faq = '.$data1['id_faq'] )->fetch();

						if ($droitExiste['id_uti'] != NULL ){

							echo '<td class=\'public\'><a href=\'modifDroit.php?id_uti='.$data2['id_uti'].'&id_faq='.$data1['id_faq'].'&alesdroit=oui\'> oui </a></td>';
						} else {

							echo '<td class=\'privée\'><a href=\'modifDroit.php?id_uti='.$data2['id_uti'].'&id_faq='.$data1['id_faq'].'&alesdroit=non\'> non </a></td>';
						}
					}

					echo '</tr>';
				}
			?>
		</table>
	</div>
</center>

<?php
	include('admin_pied.php'); 
?>