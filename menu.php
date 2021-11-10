<ul id="nav">

	<?php 

		include('connection_PDO.php');

		$lesfaqvisible = $bdd->query('SELECT nom,id_faq from faq where visible = \'public\' and id_sous_faq is null');

		foreach ($lesfaqvisible as $data ) {
			echo '<li><a href="faq.php?id_faq='.$data['id_faq'].'">    <p> '.$data['nom'].' </p></a>';

			$les_ssfaqvisible = $bdd->query('SELECT nom,id_faq from faq where visible = \'public\' and id_sous_faq = '.$data['id_faq']);

			echo '<ul>';
			foreach ($les_ssfaqvisible as $data2) {
				echo '<li><a href="faq.php?id_faq='.$data2['id_faq'].'">    <p> '.$data2['nom'].' </p></a></li>';
			}
			echo '</ul>';
			
			echo '</li>';
		}
	?>

	<li><a href="rechercher.php">   <img id="iconemenu" src="img/search.png"/>   <p> Rechercher     </p></a></li>
	<li><a href="contact.php">      <img id="iconemenu" src="img/contact.jpg"/>  <p> Nous contacter </p></a></li>
</ul>