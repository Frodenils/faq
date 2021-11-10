<html>
	<head>
		<title>Recherche FAQ</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/recherche.css"/>
		<link rel="stylesheet" type="text/css" href="css/css.css" />

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
		<div class="div_recherche">
			<h1> Vous souhaitez effectuer une recherche sur la FAQ ? </h1>
			<h3> Entrez dans la barre de recherche un mot-clé de votre question et appuyez sur entrée. Si une des questions aborde ce thème, elle vous sera affichée. </h3>
			<form method=get action="rechercher.php">
		    <!-- 
		      Pour la recherche, envoyer vers une page alternative qui va rediriger après avoir effectué la recherche en renvoyant les box de questions
		      Requête SQL de la forme SELECT * from questions where question=%$Recherche%
			  Les résultats de la requête seront rangés dans un tableau associatif et un résultat donnera une box de question avec la réponse donnée.
			  Ryūjin no ken wo kūrae! / Ryūu ga waga teki wo kuraū
		    !-->
			  <input type=search name="recherche" placeholder="Recherche..."/>
			  <img class="recherche" src="img/search.png"> 
		 	</form>
		</div>
		
		<div class="div_resultats_recherche">
			<?php 
			if(isset($_GET['recherche']))
			{
				$resultat=$_GET['recherche'];
				echo "<br>Votre recherche est : ".$resultat;
				try 
				{
					include('connection_PDO.php');
				}
				catch (Exception $e) 
				{
				    die('Erreur : ' . $e->getMessage());
				}

				$rechercheQ = $bdd ->query('SELECT question FROM contenuefaq WHERE (question like \'%'.$resultat.'%\' OR reponse like \'%'.$resultat.'%\') and visible = \'public\'');
				$donneesRechercheQ = $rechercheQ ->fetchAll(PDO::FETCH_COLUMN, 0);
				//var_dump($donneesRechercheQ);

				$rechercheR = $bdd ->query('SELECT reponse FROM contenuefaq WHERE (question like \'%'.$resultat.'%\' OR reponse like \'%'.$resultat.'%\') and visible = \'public\'');
				$donneesRechercheR = $rechercheR ->fetchAll(PDO::FETCH_COLUMN, 0);
				//var_dump($donneesRechercheR);

				$NbrResults = $rechercheQ ->rowCount();
				if($NbrResults==0) 
				{
				  echo "<p> Recherche terminée. Il n'y a pas de résultats pour votre recherche.";
				}
				else 
				{  
				  echo "<p><font color=white> Recherche terminée. ".$NbrResults." résultats trouvés : </font></p>";
				}

				for ($i=0;$i<$NbrResults;$i++)
				{	
					echo "
					<div id=\"box\">
				 	<div class=\"box-top\">
					<img src=\"img/white_plus.gif\">".$donneesRechercheQ[intval($i)]."</div>
					<div class=\"box-panel\">".$donneesRechercheR[intval($i)]."</div></div>";
				}
				
			}

			?>
		</div>
<!-- 
	<?php
#	$rechercheQ = $bdd ->query('SELECT reponse FROM contenufaq WHERE question like \'%'.$resultat.'%\' OR reponse like '%'.$resultat.'%\'');
#	$rechercheQ = $bdd ->query('SELECT reponse FROM contenufaq WHERE question like \'%'.$resultat.'%\'');
	?>
!-->
		<div class="div_results">
		  <?php
		/*	try 
			{
				$bdd = new PDO('mysql:host=localhost;dbname=site_faq;charset=utf8', 'root', ''); 
			}
			catch (Exception $e) 
			{
			    die('Erreur : ' . $e->getMessage());
			}

			$question = $bdd ->query('SELECT question FROM contenuefaq where visible = \'public\'');
			$donneesQ = $question ->fetchAll(PDO::FETCH_COLUMN, 0);
			//var_dump($donneesQ);

			$reponse = $bdd ->query('SELECT reponse FROM contenuefaq where visible = \'public\'');
			$donneesR = $reponse ->fetchAll(PDO::FETCH_COLUMN, 0);
			//var_dump($donneesR);

			$NbrQuest = $question ->rowCount();
			for ($i=0;$i<$NbrQuest;$i++)
			{	
				echo "
				<div id=\"box\">
				 	<div class=\"box-top\">
					<img src=\"img/white_plus.gif\">".$donneesQ[intval($i)]."</div>
					<div class=\"box-panel\">".$donneesR[intval($i)]."</div></div>";
			}*/
		  ?>
		</div>
	  </div>
	<!-- //////////////////////////////////////////////////// -->
	  <div class="credit">
		<?php include('credit.php'); ?>
	  </div>
	</body>
</html>