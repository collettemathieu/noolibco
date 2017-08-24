<div class="container-fluid backend paddingTopBackend">
	<div class="row-fluid">
		<div class="col-lg-12 centering maxWidth">
			<div class="col-lg-1 sousMenu">
				<form class="" method="post" action="/ForAdminOnly/Medias/SupprimerMedia">
					<button type="submit" class="infoBulle btn btn-margin btn-lg btn-danger" title="Supprimer le média"><i class="glyphicon glyphicon-remove"></i></button>
					<input type="hidden" name="idMedia" value="<?php echo $media->getIdMedia();?>"/>
				</form>
			</div>
			<div class="col-lg-11 sousMenu listeMedias">
				<img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode(file_get_contents($media->getUrlMedia())); ?>"/>
			</div>
		</div>
	</div>
</div>