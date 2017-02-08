<div class="container">
	<div class="row">
		<div class="col-lg-11 centering maxWidth">
			<div class="col-sm-5 sousMenu">
				
				<table class="table table-hover">
					<caption><h2>Select a user</h2></caption>
					<tbody>
					<?php foreach($utilisateurs as $utilisateur){ ?>
					
						
						<tr>
							<td><a href="/PourAdminSeulement/DonneesUtilisateur/idUtilisateur=<?php echo $utilisateur->getIdUtilisateur();?>"><img class="imgTableDonneeUser" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode(file_get_contents($utilisateur->getUrlPhotoUtilisateur())) ?>"/></a></td>
							<td><?php echo $utilisateur->getPrenomUtilisateur()." ".$utilisateur->getNomUtilisateur();?></td>
							<td><?php echo $utilisateur->getStatut()->getNomStatut();?></td>
							<td><?php echo $utilisateur->getMailUtilisateur();?></td>
							
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
				
				
			<?php if($ShowDonneesUtilisateur){?>
			<div class="col-sm-6 sousMenu col-sm-offset-1">
				<h2>Data user</h2>
				
				<?php if(count($donneesUtilisateur) !=0){?>
				<div id="tailleDonneesUtilisateur" class="sousMenu">
						
					<div id="<?php switch($progressionPourcent){
							case ($progressionPourcent<80):
								echo "progress";break;
							case ($progressionPourcent >=80 && $progressionPourcent<90):
								echo "progressOrange";break;
							case ($progressionPourcent>=90):
								echo "progressRed";break;
						}
					?>">
					
						<p><?php echo $tailleMoDonneesUtilisateur.' Mo utilisés sur '.$tailleMaxDonneesUtilisateur.' Mo ('.$progressionPourcent.'% )';?></p>
						<progress value="<?php echo $tailleMoDonneesUtilisateur;?>" min="0" max="<?php echo $tailleMaxDonneesUtilisateur;?>" ></progress>
					</div>
				</div>
				
				<?php foreach($donneesUtilisateur as $donnees){ ?>
					
				<div id="blockDonneesUtilisateur" class="sousMenu">
					<table>
						
						<tr>
							<td>
								<p><b>Name:<b/></p> 
							</td>
							<td>
								<p><?php echo $donnees->getNomDonneeUtilisateur(); ?></p> 
							</td>
							<td>
								<form method="post" action="/PourAdminSeulement/DonneesUtilisateur/Supprimer/">
									<input type="hidden" name="idDonneeUtilisateur" value="<?php echo $donnees->getIdDonneeUtilisateur();?>" />
									<input type="hidden" name="idUtilisateur" value="<?php echo $utilisateurAAdministrer->getIdUtilisateur();?>" />
									<input type="submit" value="X"/>
								</form>
							</td>
						</tr>
						<tr>
							<td>
								<p><b>Date of publication:<b/></p> 
							</td>
							<td>
								<p><?php echo $donnees->getDatePublicationDonneeUtilisateur(); ?></p> 
							</td>
						</tr>
						<tr>
							<td>
								<p><b>Parameter:<b/></p> 
							</td>
							<td>
								<p><?php echo $donnees->getTypeDonneeUtilisateur()->getNomTypeDonneeUtilisateur()." (".$donnees->getTypeDonneeUtilisateur()->getExtensionTypeDonneeUtilisateur().")"?></p> 
							</td>
						</tr>
						<tr>
							<td>
								<p><b>Is in cache :<b/></p> 
							</td>
							<td>
								<p><?php  if($donnees->getIsInCache()){ 
											echo "Yes";
										  }else{
											echo "No";
										  } ?></p> 
							</td>
						</tr>
					</table>
				</div>
				
				
				<?php }}else{?>
			
				<table>
					<tr>
						<td>
							<p>Aucune donnée à afficher.</p> 
						</td>
						
					</tr>
				</table>
				<?php } ?>
			</div>
			<?php } ?>
		</div>		
	</div>				
</div>