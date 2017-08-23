		<div class="container-fluid mainBackgroundArticle">
			<div class="row-fluid">
				<div class="col-sm-12 maxWidth centering">
					<div id="article" class="col-sm-8 backgroundArticle article centering fadeIn">
						<?php if(isset($article)){
							$date = new DateTime($article->getDateCreationArticle());?>
						<div class="row">
							<h1><?php echo $article->getTitreArticle();?></h1>
							<div class="col-sm-12 headerArticle centering text-center">
								<h5>NooLib The Blog | le <?php echo $date->format('d-m-Y');?></h5>
								<h5>Par <?php echo $article->getAuteur()->getNomUtilisateur();?></h5>
							</div>
							<div class="col-sm-12 imageArticle">
								<img class="img-responsive centering" title="<?php echo nl2br($article->getTitreArticle());?>" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode(file_get_contents($article->getUrlImageArticle())); ?>"/>
							</div>
							<div class="contentArticle cesure"><?php 
							$texte = $article->getTexteArticle();
							$texte = preg_replace('#{L}(.+){/L}#', '<a href="$1" target="_blank">Lien</a>', $texte);
							$texte = preg_replace('#{C}(.+){/C}#', '<div class="text-center">$1</div>', $texte);
							$texte = preg_replace_callback('#{I}(.+){/I}#', function($matches){
								return '<img class="img-responsive" src="data:image/jpg;charset=utf8;base64,'.base64_encode(file_get_contents($matches[1])).'"/>';
							}, $texte);

							$texte = preg_replace('#[\\n]#', '<br>', $texte);
							$texte = unhtmlentities($texte);
							$texte = preg_replace('#(<br> )+#', '', $texte);
							echo $texte;?>
							</div>
						</div>
						<?php } ?>
					</div>
					<?php if(!empty($article->getReferencesArticle())){?>
					
					<div class="col-sm-8 backgroundArticle centering fadeIn">
						<div class="col-sm-12 referencesArticle centering text-center">
							<h4>Référence(s)</h4>
						</div>
						<div class="col-sm-12 centering text-justify">
							<?php 
							$references = nl2br($article->getReferencesArticle());
							$references = preg_replace('#{L}(.+){/L}#', '<a href="$1" target="_blank">Lien</a>', $references);
							?>
							<p><?php echo $references;?></p>
						</div>
					</div>
					<?php }?>

					<div class="col-sm-8 backgroundArticle centering fadeIn">
						<div class="col-sm-12 commentairesArticle centering text-center">
							<h4>Commentaire(s)</h4>
						</div>
						<div class="col-sm-12 centering">
							
							<?php foreach($article->getCommentaires() as $commentaire){?>
							
								<?php 
								if($commentaire->getEnLigneCommentaire()){
									$date = new DateTime($commentaire->getDateCommentaire());
									?>
								<div class="commentaire alert alert-info">
									<h5>Par <strong><?php echo $commentaire->getUtilisateur()->getNomUtilisateur();?></strong>, publié le <strong><?php echo $date->format('d-m-Y');;?></strong></h5>
									<p><?php 
									$texteCommentaire = nl2br($commentaire->getTexteCommentaire());
									$texteCommentaire = preg_replace('#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#iS', '<a href="$0" target="_blank">Lien</a>', $texteCommentaire);
									echo $texteCommentaire?></p>
								</div>
								<?php }?>
							<?php } ?>
						</div>
						<div class="col-sm-12 formCommentaireArticle centering">
							<form id="formAddComment" class="well well-lg">
								<?php if(!isset($nameUser)){?>
								<div class="form-group">
									<label>Votre nom</label>
									<input type="text" required name="nom" class="form-control" placeholder="Entrer un nom..."/>
								</div>
								<?php }?>
								<?php if(!isset($emailUser)){?>
								<div class="form-group">
									<label>Votre adresse électronique</label>
									<input type="email" required name="adresseMail" class="form-control" placeholder="Entrer une adresse électonique valide..."/>
								</div>
								<?php }?>
								<div class="form-group">
									<label>Votre message</label>
									<textarea rows="5" required name="texteCommentaire" class="form-control" placeholder="Votre texte ici..."></textarea>							
								</div>
								<?php if(!isset($emailUser)){?>
								<div class="form-group">
									<div class="checkbox">
									  <label><input type="checkbox" required name="newsletter" checked="" value="1">Inscription à la newsletter ?</label>
									</div>
								</div>
								<?php }?>
								<input type="hidden" name="idArticle" value="<?php echo $article->getIdArticle();?>"/>
								<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Traitement..." type="submit">Envoyer</button>	
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php 
		function unhtmlentities($chaineHtml) {
			$tmp = get_html_translation_table(HTML_ENTITIES);
			$tmp = array_flip ($tmp);
			$chaineTmp = strtr ($chaineHtml, $tmp);
			return $chaineTmp;
		}?>