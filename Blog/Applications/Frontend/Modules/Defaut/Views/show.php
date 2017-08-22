		<?php if(isset($actualite)){?>
		<div class="actualite" style="background-image: url('<?php echo $actualite->getUrlImageActualite(); ?>')">
			<div class="container-fluid">
				<div class="row-fluid parent">
					<div class="rightArrow"><img src="/Images/rightArrow.png"/></div>
					<div class="leftArrow hidden"><img src="/Images/leftArrow.png"/></div>
					<div class="col-lg-12 maxWidth centering">
						<div class="col-lg-5 col-sm-8 fadeIn">
							<div class="caseActualite">
								<h2><?php echo nl2br($actualite->getTitreActualite());?></h2>
								<p><?php echo nl2br($actualite->getTexteActualite());?></p>
								<a href="<?php echo $actualite->getUrlLienActualite();?>" target="_blank"><button type="button" class="btn btn-default pull-right">Continuer</button></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }else{?>
		<div class="actualite" style="background-color: black;">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-12 maxWidth centering text-center">
						<div class="col-lg-5 col-sm-7 fadeIn">
							<div class="caseActualite">
								<h2>Sans actualité</h2>
								<p>Sans actualité</p>
								<button type="button" class="btn btn-primary pull-right">Continuer</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>

		<div class="container-fluid">
			<div class="row-fluid">
				<div class="col-lg-7 centering maxWidthEditorial fadeIn" id="beginEdito">
					<blockquote>
						<?php $date = new DateTime($edito->getDateEdito());?>
						<h3 class="editorial">Editorial du <?php echo $date->format('d-m-Y');?></h3>
						<p><?php echo nl2br($edito->getTexteEdito());?></p>
					</blockquote>

					<div class="col-lg-12 divArrowsEdito text-center centering">
						<img class="arrowsEdito leftEditoArrow infoBulleBottom hidden" title="Article précédent" src="/Images/leftArrowEdito.png"/>
						<span class="numberEdito" id="number">1</span>
						<img class="arrowsEdito rightEditoArrow infoBulleBottom" title="Article suivant" src="/Images/rightArrowEdito.png"/>
					</div>
				</div>
			</div>
		</div>

		<div class="aboutScienceAPart">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-12 centering text-center blurEffect">
						<div class="edito"><img src="\Images\arrowEditorial.png"/></div>
					</div>
					<div class="col-lg-12 maxWidth centering text-center">
						<div class="text-center centering col-lg-5">
							<h1>Aborder la science avec philosophie</h1>
						</div>
						<div class="col-sm-offset-1 col-sm-4">	
							<div class="mainInformation text-justify">
								<p>ScienceAPart est un blog qui vous invite à découvrir la science avec philosophie et pédagogie.</p>
								<p>A l'heure des Big Data, nous pensons qu'il apparaît indispensable de posséder les bons outils et les bons repères afin d'appréhender au mieux les informations qui nous parviennent.
								La science possède ses propres outils afin de démêler le vrai du faux. Encore faut-il savoir les utiliser à bon escient !</p>
								<p>Vous découvrirez sur ScienceAPart : 
									<ul>
										<li>des articles, afin de mieux interpréter les informations du quotidien.</li>
										<li>des cours en ligne, afin de vous offrir un regard critique sur le monde qui vous entoure.</li>
										<li>des services pour tous, pour les écoles, les laboratoires et pour les entreprises.</li>
									</ul>
							</div>
						</div>
						<div class="hidden-xs col-sm-3 statistics">
							<div class="row">
								<div id="statArticles" class="col-sm-12 circle text-center">
									<strong></strong>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 text-center"><h3>Articles</h3></div>
							</div>
						</div>
						<div class="hidden-xs col-sm-3">
							<div class="row">
								<div id="statCours" class="col-sm-12 circle text-center">
									<strong></strong>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 text-center"><h3>Cours</h3></div>
							</div>
						</div>
						<div class="hidden-xs col-sm-3">
							<div class="row">
								<div id="statUtilisateurs" class="col-sm-12 circle text-center">
									<strong></strong>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 text-center"><h3>Abonnés</h3></div>
							</div>
						</div>
						<div class="hidden-xs col-sm-3">
							<div class="row">
								<div id="statCommentaires" class="col-sm-12 circle text-center">
									<strong></strong>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 text-center"><h3>Commentaires</h3></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
