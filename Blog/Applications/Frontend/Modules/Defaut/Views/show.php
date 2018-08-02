		<?php if(isset($actualite)){?>
		<div class="actualite" style="background-image: url('<?php echo $actualite->getUrlImageActualite(); ?>')">
			<div class="container-fluid">
				<div class="row-fluid parent">
					<div class="rightArrow"><span></span></div>
					<div class="leftArrow hidden"><span></span></div>
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
						<span class="arrowsEdito leftEditoArrow infoBulleBottom hidden" title="Article précédent" src="/Images/leftArrowEdito.png"></span>
						<span class="numberEdito" id="number">1</span>
						<span class="arrowsEdito rightEditoArrow infoBulleBottom" title="Article suivant" src="/Images/rightArrowEdito.png"></span>
					</div>
				</div>
			</div>
		</div>

		<div class="aboutScienceAPart">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-12 centering text-center blurEffect">
						<div class="edito"><span></span></div>
					</div>
					<div class="col-lg-12 maxWidth centering text-center">
						<div class="text-center centering col-lg-7">
							<h1>NooLib, des applications mais pas que...</h1>
						</div>
						<div class="col-sm-offset-1 col-sm-5">	
							<div class="mainInformation text-justify">
								<p>NooLib permet d'exécuter des algorithmes en ligne. 
									C'est bien mais nous pensons qu'il fallait accompagner la plateforme d'un véritable lieu d'échange.</p>
								<p>A l'heure des Big Data, il apparaît indispensable de posséder les bons outils et les bons repères afin d'appréhender au mieux les informations qui nous parviennent.
								La science possède ses propres outils afin de démêler le vrai du faux. Encore faut-il savoir les utiliser à bon escient !</p>
								<p>Vous découvrirez sur NooLib The Blog : 
									<ul>
										<li>des articles, afin de mieux comprendre les applications disponibles sur la plateforme.</li>
										<li>des articles encore, sur des sujets scientifiques, afin d'acquérir un regard critique sur le monde qui vous entoure.</li>
										<li>des cours, afin de vous offrir un panorama des recherches actuellement menées dans les laboratoires et qui utilisent NooLib.</li>
										<li>des services pour tous, pour les écoles, les laboratoires et pour les entreprises.</li>
									</ul>
							</div>
						</div>
						<div class="hidden-xs col-sm-3 statistics">
							<div class="row">
								<div id="statVues" class="col-sm-12 circle text-center">
									<strong></strong>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 text-center"><h3>Nombre de vues</h3></div>
							</div>
						</div>
						<div class="hidden-xs col-sm-3">
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
