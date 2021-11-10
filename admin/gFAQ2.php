<?php

	$id_seleced = 0 ;


	if (isset($_GET['id_seleced']))
		$id_seleced=$_GET['id_seleced'];
	if (isset($_POST['id_seleced']))
		$id_seleced=$_POST['id_seleced'];

?>


		
	<div id="contenueFAQ" >

		<script type="text/javascript">

			function hideShow(class_element) {
				var sho = document.getElementById('modif'+class_element);

				//alert(id_element);

				if(sho.style.display == 'none'){
					sho.style.display = 'block';
				} else {
					sho.style.display = 'none';
				}
			}

			function hideShow2(class_element) {
				var sho = document.getElementById('ajouter'+class_element);

				//alert(id_element);

				if(sho.style.display == 'none'){
					sho.style.display = 'block';
				} else {
					sho.style.display = 'none';
				}
			}
		</script>

		<?php

			
			include('../connection_PDO.php');

			$taille = $bdd->query('SELECT id_cont,question,reponse,date_cont,visible,ordre_cont,id_faq FROM contenuefaq WHERE id_faq = \''.$id_seleced.'\' ORDER BY ordre_cont asc');

			?>
			<button onclick="hideShow2(0)">Ajouter un élement a cette place</button>
				<div class="Ajouter_gFAQ" id="ajouter0" >
					<form method="post" action="gFAQ.php#0">
						<br/>
						<label>Quelle est la question ?</label><br/>
						<input type="text" name="ajouter_question"><br/>

						<label>Quelle est la réponse ?</label><br/>
						<input type="text" name="ajouter_reponse"><br/>

						<select name="ajouter_visible">
							<option>privée</option>
							<option>public</option>
						</select><br/>

						<input type="hidden" name="ajouter_place"    value="0">
						<input type="hidden" name="id_seleced"       value="<?php echo $id_seleced ?>">
						<input type="hidden" name="ajouter_dans_faq" value="<?php echo $id_seleced ?>">
						<input type="submit"                         value="confirmer">
					</form>
				</div>
			<?php


			while ($donnees = $taille->fetch()){
				?>
					<div id="block">
					<div id="<?php echo $donnees['id_cont']; ?>">

						<table>
							<tr>
								<td>
									<form method="post" action="gFAQ.php#<?php echo $donnees['id_cont'];?>" >
		                   				<input type="hidden" name="id_up"      value= <?php echo $donnees['id_cont']; ?> >
		                   				<input type="hidden" name="id_seleced" value= <?php echo $id_seleced ?> >
		                    			<input type="submit" name="Submit"     value="↑">
		                			</form>

									<form method="post" action="gFAQ.php#<?php echo $donnees['id_cont'];?>" >
		                   				<input type="hidden" name="id_down"    value= <?php echo $donnees['id_cont']; ?> >
		                   				<input type="hidden" name="id_seleced" value= <?php echo $id_seleced ?> >
		                    			<input type="submit" name="Submit"     value="↓">
		                			</form>
								</td>

								<?php echo '<td class=\''.$donnees['visible'].'\'><a href=\'gFAQ.php?visible='.$donnees['id_cont'].'&id_seleced='.$id_seleced.'#'.$donnees['id_cont'].'\'>'.$donnees['visible']; ?></a>
								</td>

								<td> <?php echo $donnees['date_cont']; ?> </td>

								<td>
									<a href="gFAQ.php?supp=<?php echo $donnees['id_cont'];?>&id_seleced=<?php echo $id_seleced; ?>#<?php echo $donnees['id_cont'];?> " onClick="return confirm('Supprimer cet élément de la F.A.Q ?');" >Supp</a>
								</td>
								
								<td onclick="hideShow(<?php echo $donnees['id_cont'];?>)"> modifier </td>
							</tr>
						</table>

						<fieldset>
							<legend>Question</legend>
							<p><?php echo $donnees['question']; ?></p>
						</fieldset>

						<fieldset>
							<legend>Réponse</legend>
							<p><?php echo $donnees['reponse']; ?></p>
						</fieldset>

						<div class="Modif_gFAQ" id="modif<?php echo $donnees['id_cont']; ?>" >
							<form method="post" action="gFAQ.php#<?php echo $donnees['id_cont'];?>">
								<label>question :</label>
								<input type="text" name="Modif_gFAQ_question" value="<?php echo $donnees['question']; ?>">
								<br/>

								<label>reponse :</label>
								<input type="text" name="Modif_gFAQ_reponse" value="<?php echo $donnees['reponse']; ?>">

								<input type="hidden" name="id_seleced" value="<?php echo $id_seleced ?>">
								<input type="hidden" name="id_modif"   value="<?php echo $donnees['id_cont'];?>">
								<input type="submit"                   value="confirmer">
							</form>
						</div>
					</div>
					</div>

					<br/>
					<button onclick="hideShow2(<?php echo $donnees['id_cont'];?>)">Ajouter un élement a cette place</button>

					<div class="Ajouter_gFAQ" id="ajouter<?php echo $donnees['id_cont']; ?>" >
						<form method="post" action="gFAQ.php#<?php echo $donnees['id_cont'];?>">
							<br/>
							<label>Quelle est la question ?</label><br/>
							<input type="text" name="ajouter_question"><br/>

							<label>Quelle est la réponse ?</label><br/>
							<input type="text" name="ajouter_reponse"><br/>

							<select name="ajouter_visible">
								<option>privée</option>
								<option>public</option>
							</select><br/>

							<input type="hidden" name="ajouter_place"    value="<?php echo $donnees['ordre_cont'];?>">
							<input type="hidden" name="id_seleced"       value="<?php echo $id_seleced ?>">
							<input type="hidden" name="ajouter_dans_faq" value="<?php echo $id_seleced ?>">
							<input type="submit"                         value="confirmer">
						</form>
					</div>
					
				<?php
			}
		?>	

	</div>