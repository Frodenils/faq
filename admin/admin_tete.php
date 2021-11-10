<html>
<head>
	<title>FAQ espace admin</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/espace_admin.css" />

	<script src="../script/jquery.js"></script>
	<script src="../script/general.js"></script>
	<!-- <script src="../script/menu.js"></script> -->



</head>

<body>

	<div id="header">
		<?php include('admin_header.php'); ?>
	</div>


	<?php
		if ($_SESSION['mail'] == '' ) {
			echo "<div id='alert'>";
			echo	"<h2>/!\ Attention /!\</h2>";
			echo	"<p> adresse mail non enregisté </p>";
			echo "</div>";
		}
	?>

	<a class="mobile" href="#">MENU</a>

	<div id="conteiner">
		<div class="sidebar">
			<?php include('admin_menuS.php'); ?>
		</div>

		<div class="content">