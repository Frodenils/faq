<ul id="nav">
	<li><a href="profile.php" >  <img id="iconemenu" src="../img/Profile-icon.png"/><p>  Profil  </p></a></li>
	<?php 
		if ($_SESSION['droit'] == '0') {
			echo '<li><a href="plusDEprofile.php">  <img id="iconemenu" src="../img/Profile-plus.png"/><p>  Ajouter un utilisateur  </p></a></li>';
			echo '<li><a href="modifDroit.php">  <img id="iconemenu" src="../img/Profile-droit.png"/><p>  Modif droit utilisateur </p></a></li>';
		}
	?>
	<li><a href="plusDEfaq.php">  <img id="iconemenu" src="../img/cat-icon.png"/><p>  Ajouter une F.A.Q </p></a></li>
	<li><a href="gFAQ.php">  <img id="iconemenu" src="../img/faq-icon.png"/><p>  Gérer les FAQs  </p></a></li>
	<li><a href="ReceptionContact.php">  <img id="iconemenu" src="../img/contactadmin.png"/><p>  Boite de réception des formulaires  </p></a></li>
	<li><a href="ReceptionTraites.php">  <img id="iconemenu" src="../img/contactadminopened.png"/><p>  Messages traités </p></a></li>
</ul>