<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-11 centering maxWidth sousMenu">

			<form method="post" class="col-lg-6 centering well well-lg" action="/PourAdminSeulement/Configurations/AjouterElementsConfigurations">
				<legend>Create a new variable</legend>
				<div class="form-group">
					<label>Enter a name for the category</label>
					<input type="text" name="categorie" class="form-control input-sm" placeholder="Enter a name for the category"/>
				</div>
				<div class="form-group">
					<label>Enter a name for the on-category</label>
					<input type="text" name="sousCategorie" class="form-control input-sm" placeholder="Enter a name for the on-category"/>
				</div>
				<div class="form-group">
					<label>Enter a name for the variable</label>
					<input type="text" name="var" class="form-control input-sm" placeholder="Enter a name for the variable"/>
				</div>
				<div class="form-group">
					<label>Enter a value for this variable</label>
					<input type="text" name="value" class="form-control input-sm" placeholder="Enter a value for this variable"/>
				</div>
				<button class="btn btn-primary" type="submit">Create a new variable</button>
			</form>

			<table class="table table-condensed">
				<caption><h2>List of variables of configuration</h2></caption>
				<thead>
			        <tr class="sousMenu">
			            <th>Category</th>
			            <th>On-category</th>
			            <th>Variables</th>
			        </tr>
			    </thead>
			    <tbody>
			    	<?php foreach($vars as $var=>$value){?>
			    	<tr>
			    		<td>
			    			<form method="post" class="well well-sm" action="/PourAdminSeulement/Configurations/ModifierCategorieConfigurations">
								<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
								<input type="text" class="input-sm" name="nouveauNomCategorie" value="<?php echo $var; ?>"/>
								<button type="submit" class="btn btn-primary">Modify</button>
							</form>
							<form method="post" class="well well-sm" action="/PourAdminSeulement/Configurations/SupprimerCategorieConfigurations">
								<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
								<button type="submit" class="btn btn-primary">Delete</button>
							</form>
			    		</td>
			    		
			    		<td>
			    			<table class="table table-condensed">
			    				<tbody>
			    					<?php foreach( $value as $varCat=>$valueCat){ ?>
			    					<tr>
			    						<td>
				    						<form method="post" class="well well-sm col-lg-6" action="/PourAdminSeulement/Configurations/ModifierSousCategorieConfigurations">
												<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
												<input type="hidden" name="sousCategorie" value="<?php echo $varCat ?>"/>
												<input type="text" class="input-sm col-lg-5" name="nouveauNomSousCategorie" value="<?php echo $varCat; ?>"/>
												<button type="submit" class="btn btn-primary">Modify</button>
											</form>
											<form method="post" class="well well-sm col-lg-3" action="/PourAdminSeulement/Configurations/SupprimerSousCategorieConfigurations">
												<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
												<input type="hidden" name="sousCategorie" value="<?php echo $varCat ?>"/>
												<button type="submit" class="btn btn-primary">Delete</button>
											</form>
										</td>
			    					</tr>
			    					<?php foreach($valueCat as $varSousCat=>$valueSousCat){?>
			    					<tr>
			    						<td style="height: 80px"></td>
			    					</tr>
			    					<?php } ?>
			    					<?php }?>
			    					<tr class="warning">
			    						<td>
				    						<form method="post" class="well well-sm" action="/PourAdminSeulement/Configurations/AjouterSousCategorieConfigurations">
												<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
												<input type="text" class="input-sm" name="nomSousCategorie" placeholder="New name of on-category"/>
												<input type="text" class="input-sm" name="var" placeholder="Variable"/>
												<input type="text" class="input-sm" name="value" placeholder="Value"/>
												<button type="submit" class="btn btn-primary">Add a new category</button>
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
				    						<form method="POST" class="well well-sm col-lg-9" action="/PourAdminSeulement/Configurations/ModifierConfigurations">
												<?php echo $varSousCat ?> :
												<input type="text" class="input-sm" name="value" value="<?php echo $valueSousCat ?>"/>
												<input type="hidden" name="var" value="<?php echo $varSousCat ?>"/>
												<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
												<input type="hidden" name="sousCategorie" value="<?php echo $varCat ?>"/>
												<button type="submit" class="btn btn-primary">Modify</button>
											</form>
											<form method="POST" class="well well-sm col-lg-3" action="/PourAdminSeulement/Configurations/SupprimerConfigurations">
												<input type="hidden" name="var" value="<?php echo $varSousCat ?>"/>
												<input type="hidden" name="sousCategorie" value="<?php echo $varCat ?>"/>
												<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
												<button type="submit" class="btn btn-primary">Delete</button>
											</form>
										</td>
			    					</tr>
			    					<?php }?>
			    					<tr class="danger">
				    					<td>
					    					<form method="POST" class="well well-sm" action="/PourAdminSeulement/Configurations/CreerConfigurations">
												<input type="text" placeholder="Variable" name="var"/>
												<input type="text" placeholder="Valeur" name="value"/>
												<input type="hidden" name="categorie" value="<?php echo $var ?>"/>
												<input type="hidden" name="sousCategorie" value="<?php echo $varCat ?>"/>
												<button type="submit" class="btn btn-primary">Create</button>
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