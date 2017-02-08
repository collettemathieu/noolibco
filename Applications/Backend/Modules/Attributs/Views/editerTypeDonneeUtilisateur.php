<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-11 centering maxWidth sousMenu">
			<table class="table table-hover">
				<caption><h2>List of data type</h2></caption>
				<thead>
			        <tr class="sousMenu">
			            <th>Name</th>
			            <th>Extension</th>
			            <th></th>
			            <th></th>
			        </tr>
			    </thead>

				<tbody>
				<?php foreach($allTypeDonneeUtilisateur as $typeDonneeUtilisateur) { ?>
					<tr>
						<form method="POST" action="/PourAdminSeulement/Attributs/EditerTypeDonneeUtilisateur/Modifier">
							<td><?php echo $typeDonneeUtilisateur->getNomTypeDonneeUtilisateur(); ?> :</td>
							<td><input type="text" name="extensionTypeDonneeUtilisateur" value="<?php echo $typeDonneeUtilisateur->getExtensionTypeDonneeUtilisateur(); ?>"/></td>
							<input type="hidden" name="idTypeDonneeUtilisateur" value="<?php echo $typeDonneeUtilisateur->getIdTypeDonneeUtilisateur(); ?>"/>
							<td><input type="submit" value="Modify"/></td>
						</form>
						<form method="POST" action="/PourAdminSeulement/Attributs/EditerTypeDonneeUtilisateur/Supprimer">
							<input type="hidden" name="idTypeDonneeUtilisateur" value="<?php echo $typeDonneeUtilisateur->getIdTypeDonneeUtilisateur(); ?>"/>
							<td><input type="submit" value="Delete"/></td>
						</form>
					</tr>
			
				<?php } ?>
				
					<tr>
						<form method="POST" action="/PourAdminSeulement/Attributs/EditerTypeDonneeUtilisateur/Ajouter">
							<td><input type="text" name="nomTypeDonneeUtilisateur"/></td>
							<td><input type="text" name="extensionTypeDonneeUtilisateur"/></td>
							<td><input type="submit" value="Create a new type"/></td>
						</form>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>