<div class="container-fluid backend paddingTopBackend">
	<div class="row-fluid">
		<div class="col-lg-12 centering maxWidth">
			<div class="col-lg-1 sousMenu">
				<button type="button" class="infoBulle btn btn-danger btn-margin btn-lg" data-toggle="modal" href="#supprimerEdito" title="Supprimer cet édito"><i class="glyphicon glyphicon-trash"></i></button>	
			</div>
			<div class="col-lg-11 sousMenu">
				<form action="/ForAdminOnly/Editos/ModifierTexteEdito" method="post" class="well well-lg">
					<div class="form-group">
						<textarea rows="10" name="nouveauTexte" class="form-control"><?php echo $edito->getTexteEdito();?></textarea>							
					</div>
					<input type="hidden" name="idEdito" value="<?php echo $edito->getIdEdito();?>"/>
					<button class="btn btn-primary" type="submit">Modifier</button>	
				</form>
			</div>
		</div>
	</div>
</div>


<div id="supprimerEdito" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Suppression de l'édito</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form action="/ForAdminOnly/Editos/SupprimerEdito" method="post" class="well well-lg">
							<legend>Etes-vous sûr de vouloir supprimer cet édito ?</legend>
							<input type="hidden" name="idEdito" value="<?php echo $edito->getIdEdito();?>"/>
							<button class="btn btn-primary" type="submit">Supprimer</button>	
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>