<html>
	<head>
		<title>Nous contacter</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/css.css" />
		<link rel="stylesheet" type="text/css" href="css/contact.css" />

		<script src="script/jquery.js"></script>
		<script src="script/general.js"></script>
	</head>

	<body>
		<div id="header">
			<?php include('header.php'); ?>
		</div>

		<a class="mobile" href="#">MENU</a>

		<div id="container">
			<div class="sidebar">
				<?php include('menu.php'); ?>
			</div>
	<!-- //////////////////////////////////////////////////// -->
			<div class="content" >
				<h1> Formulaire à remplir pour nous contacter </h1>

	<!-- DEBUT DU FORMULAIRE -->
				<form method='post' action="envoi.php"> 
					<fieldset>
						<legend name="contact">Informations personnelles</legend>
						<ul class='liste_form'>
						<li><label for="Nom">Nom</label></li>
						<div class="input_texte" align=center>
							<input type=text name='nom' placeholder='Votre Nom...'>
						</div>

						<li><label for="Prenom">Prénom</label></li>
						<div class="input_texte" align=center>
							<input type=text name='prenom' placeholder='Votre Prénom...'><br>
						</div>
						<li><label for="email">Email</label></li>
						<div class="input_texte" align=center>
							<input type=text name='email' placeholder='Votre adresse e-mail...'><br>
						</div>
						</ul>
					</fieldset><br/>


					<fieldset>				
						<legend name="contact"> Votre classe </legend>

						<div class="classe">
							<label>
								<input type="radio" class="option-input radio" name="classe" id="3ème" value="3ème"/>3ème
							</label>
							<label>
								<input type="radio" class="option-input radio" name="classe" id="Seconde" value="Seconde"/>Seconde
							</label>
							<label>
								<input type="radio" class="option-input radio" name="classe" id="Première" value="Première"/>Première
							</label>
							<label>
								<input type="radio" class="option-input radio" name="classe" id="Terminale" value="Terminale"/>Terminale
							</label>
							<label>
								<input type="radio" class="option-input radio" name="classe" id="BTS1" value="BTS1"/>BTS1
							</label>
							<label>
								<input type="radio" class="option-input radio" name="classe" id="BTS2" value="BTS2"/>BTS2
							</label>
						</div> <br/>
					</fieldset><br/>

					<fieldset>
						<legend name="contact"> Conditions générales d'utilisation </legend>
						<div class="CGU">
							<p name="CGU"> Vous acceptez les conditions générales d'utilisation : </p>
							<div class="slider">	
								<input type="checkbox" id="slider" name="slider"/>
								<label for="slider"></label>
							</div>
						</div>
					</fieldset><br/>
					<fieldset>
						<legend name="contact"> Que souhaitez vous dire ? </legend>
						<textarea name="texte" placeholder="Votre texte..."></textarea>
					</fieldset><br/>

					<input type="submit" Value="Valider">
				</form>
	<!-- FIN DU FORMULAIRE -->
			</div>
		</div>

		<div class="credit">
			<?php include('credit.php'); ?>
		</div>
	</body>
</html>