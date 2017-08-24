<div class="container-fluid backend paddingTopBackend">
	<div class="row-fluid">
		<div class="col-lg-11 centering maxWidth sousMenu">

			<form method="post" class="col-lg-6 centering well well-lg" action="/ForAdminOnly/Configurations/AjouterElementsConfigurations">
				<legend>Créer un nouveau paramètre de configuration</legend>
				<div class="form-group">
					<label>Entrer un nom pour la catégorie du paramètre</label>
					<input type="text" name="categorie" class="form-control input-sm" placeholder="Entrer un nom pour la catégorie"/>
				</div>
				<div class="form-group">
					<label>Entrer un nom pour la sous-catégorie du paramètre</label>
					<input type="text" name="sousCategorie" class="form-control input-sm" placeholder="Entrer un nom pour la sous-catégorie"/>
				</div>
				<div class="form-group">
					<label>Entrer un nom de variable</label>
					<input type="text" name="var" class="form-control input-sm" placeholder="Entrer un nom de variable"/>
				</div>
				<div class="form-group">
					<label>Entrer une valeur pour la variable</label>
					<input type="text" name="value" class="form-control input-sm" placeholder="Entrer une valeur pour la variable"/>
				</div>
				<button class="btn btn-primary" type="submit">Créer le paramètre</button>
			</form>

			<table class="table table-condensed">
				<caption><h2>Liste des paramètres de configuration</h2></caption>
				<thead>
			        <tr class="sousMenu">
			            <th>Catégorie</th>
			            <th>Sous-Catégorie</th>
			            <th>Variable</th>
			        </tr>
			    </thead>
			    <tbody>
			    	<?php foreach($vars as $var=>$value){?>
			    	<tr>
			    		<td>
			    			<form method="post" class="well well-sm" action="/ForAdminOnly/Configurations/ModifierCategorieConfigurations">
								<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
								<div class="form-group">
									<input type="text" class="form-control input-sm" name="nouveauNomCategorie" value="<?php echo $var; ?>"/>
								</div>
								<button type="submit" class="btn btn-primary">Modifier</button>
							</form>
							<form method="post" class="well well-sm" action="/ForAdminOnly/Configurations/SupprimerCategorieConfigurations">
								<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
								<button type="submit" class="btn btn-primary">Supprimer</button>
							</form>
			    		</td>
			    		
			    		<td>
			    			<table class="table table-condensed">
			    				<tbody>
			    					<?php foreach( $value as $varCat=>$valueCat){ ?>
			    					<tr>
			    						<td>
				    						<form method="post" class="well well-sm" action="/ForAdminOnly/Configurations/ModifierSousCategorieConfigurations">
												<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
												<input type="hidden" name="sousCategorie" value="<?php echo $varCat ?>"/>
												<div class="form-group">
													<input type="text" class="input-sm" name="nouveauNomSousCategorie" value="<?php echo $varCat; ?>"/>
												</div>
												<button type="submit" class="btn btn-primary">Modifier</button>
											</form>
											<form method="post" class="well well-sm" action="/ForAdminOnly/Configurations/SupprimerSousCategorieConfigurations">
												<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
												<input type="hidden" name="sousCategorie" value="<?php echo $varCat ?>"/>
												<button type="submit" class="btn btn-primary">Supprimer</button>
											</form>
										</td>
			    					</tr>
			    					<?php foreach($valueCat as $varSousCat=>$valueSousCat){?>
			    					<tr>
			    						<td style="height: 80px"></td>
			    					</tr>
			    					<?php } ?>
			    					<?php }?>
			    					<tr>
			    						<td>
				    						<form method="post" class="well well-sm" action="/ForAdminOnly/Configurations/AjouterSousCategorieConfigurations">
												<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
												<div class="form-group">
													<input type="text" class="form-control input-sm" name="nomSousCategorie" placeholder="New name of on-category"/>
												</div>
												<div class="form-group">
													<input type="text" class="form-control input-sm" name="var" placeholder="Variable"/>
												</div>
												<input type="text" class="form-control input-sm" name="value" placeholder="Value"/>
												<button type="submit" class="btn btn-primary">Ajouter une nouvelle catégorie</button>
											</form>
										</td>
			    					</tr>
			    				</tbody>
			    			</table>
			    		</td>
			    			
			    		<td>
			    			<table class="table table-condensed">
			    				<tbody>
			    					<?php foreach( $value as $varCat=>$valueCat){
			    							foreach($valueCat as $varSousCat=>$valueSousCat){?>
			    					<tr>
			    						<td>
				    						<form method="POST" class="well well-sm" action="/ForAdminOnly/Configurations/ModifierConfigurations">
												<?php echo $varSousCat ?> :
												<div class="form-group">
													<input type="text" class="form-control input-sm" name="value" value="<?php echo $valueSousCat ?>"/>
												</div>
												<input type="hidden" name="var" value="<?php echo $varSousCat ?>"/>
												<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
												<input type="hidden" name="sousCategorie" value="<?php echo $varCat ?>"/>
												<button type="submit" class="btn btn-primary">Modifier</button>
											</form>
											<form method="POST" class="well well-sm" action="/ForAdminOnly/Configurations/SupprimerConfigurations">
												<input type="hidden" name="var" value="<?php echo $varSousCat ?>"/>
												<input type="hidden" name="sousCategorie" value="<?php echo $varCat ?>"/>
												<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
												<button type="submit" class="btn btn-primary">Supprimer</button>
											</form>
										</td>
			    					</tr>
			    					<?php }?>
			    					<tr>
				    					<td>
					    					<form method="POST" class="well well-sm" action="/ForAdminOnly/Configurations/CreerConfigurations">
												<div class="form-group">
													<input type="text" placeholder="Variable" name="var"/>
												</div>
												<div class="form-group">
													<input type="text" placeholder="Valeur" name="value"/>
												</div>
												<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
												<input type="hidden" name="sousCategorie" value="<?php echo $varCat ?>"/>
												<button type="submit" class="btn btn-primary">Créer</button>
											</form>
										</td>
									</tr>
			    					<?php }?>

			    				</tbody>
			    			</table>
			    		</td>
			    	</tr>
			    	<?php }?>
			    </tbody>
			</table>
		</div>
	</div>		
</div>