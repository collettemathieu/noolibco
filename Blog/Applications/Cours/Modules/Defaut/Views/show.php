		<div class="container-fluid courss">
			<div class="row-fluid">
				<div class="col-lg-12 maxWidth centering fadeIn">
					<?php foreach($courss as $cours){
						if($cours instanceof \Library\Entities\Cours){
						$date = new DateTime($cours->getDateCreationCours());?>
					<a class="caseCours" href="/Cours/<?php echo $cours->getUrlTitreCours();?>"><!--
						--><img class="infoBulleBottom" title="<?php echo $cours->getTitreCours();?>" src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($cours->getUrlImageMiniatureCours())); ?>"/><!--
						--><div class="mainText">
							<h4><?php echo nl2br($cours->getTitreCours());?></h4>
							<div class="informationCours"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Publié le <?php echo $date->format('d-m-Y');?></div>
							<div class="informationCours"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Catégorie : <?php echo $cours->getCategorie()->getNomCategorie();?></div>
							<div class="informationCours"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Lu <?php echo $cours->getNbreVueCours();?> fois</div>
							<p class="cesure"><?php echo nl2br($cours->getDescriptionCours());?></p>
						</div>
					</a>
					<?php }else{ $date = new DateTime($cours->getDateCreationCoursGlobal());?>
					<a class="caseCours coursGlobal" href="#" idCoursGlobal="<?php echo $cours->getIdCoursGlobal();?>"><!--
						--><img class="infoBulleBottom" title="<?php echo $cours->getTitreCoursGlobal();?>" src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($cours->getUrlImageMiniatureCoursGlobal())); ?>"/><!--
						--><div class="mainText">
							<h4><?php echo nl2br($cours->getTitreCoursGlobal());?></h4>
							<div class="informationCours"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Publié le <?php echo $date->format('d-m-Y');?></div>
							<div class="informationCours"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Par <?php echo $cours->getAuteur()->getNomUtilisateur();?></div>
							<div class="informationCours"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Lu <?php echo $cours->getNbreVueCoursGlobal();?> fois</div>
							<p class="cesure"><?php echo nl2br($cours->getDescriptionCoursGlobal());?></p>
						</div>
					</a>
					<?php }}?>
				</div>
			</div>
		</div>

<div id="showAllCours" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
    	<div class="loader hidden"></div>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title"></h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-12 allCours"></div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>