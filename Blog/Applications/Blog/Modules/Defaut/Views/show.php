		<div class="container-fluid articles">
				<div class="row-fluid">
					<div class="col-lg-12 maxWidth centering fadeIn">
						<?php foreach($articles as $article){
							if($article->getEnLigneArticle() || $admin){
							$date = new DateTime($article->getDateCreationArticle());?>
						<a class="caseArticle" href="/Blog/<?php echo $article->getUrlTitreArticle();?>"><!--
							--><img class="infoBulleBottom" title="<?php echo $article->getTitreArticle();?>" src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($article->getUrlImageMiniatureArticle())); ?>"/><!--
							--><div class="mainText">
								<h4><?php echo nl2br($article->getTitreArticle());?></h4>
								<div class="informationArticle"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Publié le <?php echo $date->format('d-m-Y');?></div>
								<div class="informationArticle"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Par <?php echo $article->getAuteur()->getNomUtilisateur();?></div>
								<div class="informationArticle"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Lu <?php echo $article->getNbreVueArticle();?> fois</div>
								<p class="cesure"><?php echo nl2br($article->getDescriptionArticle());?></p>
							</div>
						</a>
						<?php }} ?>
					</div>
				</div>
			</div>