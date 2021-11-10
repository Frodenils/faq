<?php
	
	$id_faq = 0 ;

	if(isset($_GET['id_faq']))
		$id_faq=$_GET['id_faq'];

	if ($id_faq == 0){

		header('Location: index.php');   
	}
	else {

		include('connection_PDO.php');

		$lafaq = $bdd->query('SELECT nom from faq where id_faq = '.$id_faq )->fetch();

		$lecontenufaq = $bdd->query('SELECT question,reponse from contenuefaq where visible=\'public\' and id_faq ='.$id_faq.' order by ordre_cont asc');
	}
?>

<html>
	<head>
		<title>FAQ</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/css.css" />


		<script src="script/jquery.js"></script>
		<script src="script/general.js"></script>
	</head>

	<body>
		<div id="header">
			<?php include('header.php'); ?>
		</div>

		<a class="mobile" href="#">MENU</a>

		<div id="conteiner">
			<div class="sidebar">
				<?php include('menu.php'); ?>
			</div>

	<!-- //////////////////////////////////////////////////// -->

			<div class="content">
				<h1><?php echo $lafaq['nom'] ?></h1>
				<p>Voici quelques réponses à vos questions</p>

	<!-- ///// contenue de la faq /////////////////////////////////////////// -->
				<?php

					foreach ($lecontenufaq as $data) {
						?>

							<div id="box">
								<div class="box-top">
									<img src="img/white_plus.gif">
									
									<?php echo $data['question']?>

								</div>
								<div class="box-panel">		

									<?php echo $data['reponse']?>

								</div>
							</div>
						<?php
					}
				?>
	<!-- ///// pour les sous-faqs si elles existe ///////////////////////////////////////// -->
				<?php

					$les_sousfaq = $bdd->query('SELECT nom,id_faq from faq where visible=\'public\' and id_sous_faq = '.$id_faq );

					foreach ($les_sousfaq as $data2) {
						
						$lecontenu_sousfaq = $bdd->query('SELECT question,reponse from contenuefaq where visible=\'public\' and id_faq ='.$data2['id_faq'].' order by ordre_cont asc');

						?>
							</br>
							<h2><?php echo '--'.$data2['nom']; ?></h2>

							<?php

								foreach ($lecontenu_sousfaq as $data3) {
									?>

										<div id="box">
											<div class="box-top">
												<img src="img/white_plus.gif">
												
												<?php echo $data3['question'] ;?>

											</div>
											<div class="box-panel">		

												<?php echo $data3['reponse']?>

											</div>
										</div>
									<?php
								}
							?>
						<?php
					}
				?>
			</div>
		</div>

		<div class="credit">
			<?php include('credit.php'); ?>
		</div>
	</body>
</html>