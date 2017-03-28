	<div class="container-fluid">
		<div class="row-fluid">
			<div class="col-lg-9 sousMenu centering maxWidth fadeIn">
				<div class="row">
					<div class="col-sm-12 informationApplication">
						<div class="col-sm-12">
							<img src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($app->getUrlLogoApplication())); ?>"/>
							<h3><?php echo $app->getNomApplication();?></h3>
							<a class="infoBulle pull-right btn btn-primary btn-margin btn-lg" href="/NooSpace/a=<?php echo $app->getIdApplication(); ?>v=" title="Use it in the noospace"><i class="glyphicon glyphicon-log-out"></i></a>
							<form id="formAddApplicationInDock" class="pull-right" method="post" action="/HandleApplication/AddRemoveToDock">
								<button type="submit" id="boutonAjouterAuDock" class="infoBulle btn <?php if(!$appIsInDock){echo 'btn-success';}else{echo 'btn-danger';}?> btn-margin btn-lg" title="Add / Remove from the dock"><?php if(!$appIsInDock){echo '<i class="glyphicon glyphicon-ok"></i>';}else{echo '<i class="glyphicon glyphicon-remove"></i>';} ?></button>
								<input type="hidden" name="idApplication" value=<?php echo '"'.$app->getIdApplication().'"';?>/>
							</form>
							<button type="button" class="infoBulle pull-right btn btn-info btn-margin btn-lg" data-toggle="modal" href="#infoApplication" title="More information"><i class="glyphicon glyphicon-search"></i></button>
						</div>
					</div>
					<div class="col-lg-12"><hr></div>
					<div class="col-sm-6">
						<ul class="list-unstyled sousMenu">
							<li><h3>Author(s)</h3>
								<ul class="list-unstyled">
									<li><?php if (sizeof($app->getCreateur()) != 0)
									{ ?>
						           		<a href="/Profile/idAuteur=<?php echo($app->getCreateur()->getIdUtilisateur()); ?>">
											<?php echo($app->getCreateur()->getNomUtilisateur().' '.$app->getCreateur()->getPrenomUtilisateur()); ?>
										</a><?php } ?>
									</li>
									<?php echo $otherAuthors; ?>
								</ul>
							</li>
							<li><h3>Settings</h3>
								<ul class="list-unstyled">
									<li>Status: <?php 
										if (sizeof($app->getStatut()) != 0){
						           		echo $app->getStatut()->getNomStatut();}?>
						           	</li>
									<li>Version: <?php
										if (sizeof($app->getVersions()) != 0){?>
											<?php 
											for($i=count($app->getVersions())-1; $i>=0; $i=$i-1){
												if($app->getVersions()[$i]->getActiveVersion()){
													$versionAAfficher = $app->getVersions()[$i]->getNumVersion();
													break;
												}
											}?>
							          		<a data-toggle="modal" href="#infoVersion"><?php echo $versionAAfficher;?></a>
							          	<?php }?>
						          	</li>
									<li>Catergory: <?php 
							           if (sizeof($app->getCategorie()) != 0){
							           		echo $app->getCategorie()->getNomCategorie();
							       		}?>
						       		</li>
									<li>KeyWords: <?php if($app!=null && $app->getMotCles() != null){echo implode(', ',$app->getMotCles());}?>
						       		</li>
						       		<li>Date: <?php echo $app->getDateSoumissionApplication();?>
						       		</li>
								</ul>
							</li>
							<!-- *************** -->
							<li><h3>Settings</h3>
								<ul class="list-unstyled">
								<?php 
							           if (sizeof($app->getLienApplication()) != 0){
						       		        echo "<li>Link: <a href='".$app->getLienApplication()."' target='_blank'>"; 
							           		echo $app->getLienApplication();
							       			echo "</a></li>";
						       		}?>
								</ul>
								</li>
							<!-- *************** -->


							<li><h3>Publication(s)</h3>
								<?php if(count($app->getPublications()) != 0){?>
								<table class="table table-bordered table-striped table-condensed">
									<thead>
								        <tr>
								            <th>Title</th>
								            <th>First Author</th>
								            <th>Journal</th>
								            <th>Year</th>
								            <th>Link</th>
								        </tr>
								    </thead>
									<tbody>
							    		<?php foreach($app->getPublications() as $publication){?>
							    		<tr>
											<td><?php echo $publication->getTitrePublication();?></td>
								           	<td><?php
								           	$premierAuteur = $publication->getAuteurs()[0];
								          	echo $premierAuteur->getPrenomAuteur()." ".$premierAuteur->getNomAuteur();
								           	?></td>
											<td><?php echo $publication->getJournalPublication();?></td>
								           	<td><?php echo $publication->getAnneePublication();?></td>
											<td><?php echo '<a href="'.$publication->getUrlPublication().'" target="_blank">See</a>';?></td>
							      		</tr>
							      		<?php } ?>
									</tbody>
								</table>
								<?php 
							   }else{?>
							   	<div class="alert alert-warning">
							   		<p><?php echo $app->getNomApplication();?> is not currently validated by scientific publications.</p>
							   		<button class="btn btn-primary" data-toggle="modal" href="#askToValidate">Propose your help ?</button>
							   	</div>
							   <?php }?>
							</li>
						</ul>
						
					</div>

					<div class="col-sm-6">
						<div class="col-sm-12">
							<blockquote class="cesure"><?php echo nl2br($app->getDescriptionApplication());?></blockquote>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="infoVersion" class="modal fade" role="dialog">
		<div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">x</button>
					<h3 class="modal-title"><?php echo Ucfirst($app->getNomApplication());?> versioning information</h3>
				</div>
				<div class="modal-body">
					<table class="table table-striped table-bordered table-hover ">
					    <thead>
					        <tr>
					            <th>#Version</th>
					            <th>Number</th>
					            <th>Details</th>
					        </tr>
					    </thead>
					    <tbody>
					    	<?php foreach($app->getVersions() as $key => $version){
					    		if($version->getActiveVersion()){
					    		?>
					        <tr>
					            <td class="text-center"><?php echo $key+1;?></td>
					            <td><?php echo $version->getNumVersion();?></td>
					            <td><?php echo $version->getNoteMajVersion();?></td>
					        </tr>
					        <?php }}?>
					    </tbody>
					</table>
		      	</div>
		    </div>
		</div>
	</div>

	<div id="infoApplication" class="modal fade" role="dialog">
		<div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">x</button>
					<h3 class="modal-title">What <?php echo $app->getNomApplication();?> can do ?</h3>
				</div>
				<div class="modal-body">
					<table class="table table-striped table-bordered table-hover ">
					    <thead>
					        <tr>
					            <th>#Task</th>
					            <th>Name</th>
					            <th>Description</th>
					            <th>Data required</th>
					        </tr>
					    </thead>
					    <tbody>
					    	<?php foreach($app->getVersions()[0]->getTaches() as $ordreTache => $tache){?>
					        <tr>
					            <td class="text-center"><?php echo $ordreTache+1;?></td>
					            <td><?php echo $tache->getNomTache();?></td>
					            <td><?php echo $tache->getDescriptionTache();?></td>
					            <td>
					            	<ul class="list-group">
					            		<?php foreach($tache->getTacheTypeDonneeUtilisateurs() as $ordreParametre => $tacheTypeDonneeUtilisateur){?>
									    <li class="list-group-item"><span class="badge"><?php echo $tacheTypeDonneeUtilisateur->getTypeDonneeUtilisateur()->getExtensionTypeDonneeUtilisateur();?></span><?php echo $tacheTypeDonneeUtilisateur->getDescription();?></li>
										<?php }?>
									</ul>	
					            </td>
					        </tr>
					        <?php }?>
					    </tbody>
					</table>
		      	</div>
		    </div>
		</div>
	</div>

	<div id="askToValidate" class="modal fade" role="dialog">
		<div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">x</button>
					<h2 class="modal-title">Contact the author</h2>
				</div>
				<div class="modal-body">
					<div class="alert alert-warning">By sending an email to <?php echo($app->getCreateur()->getPrenomUtilisateur() . ' ' . $app->getCreateur()->getNomUtilisateur());?>, your email address will be known by this person.</div>
					<form class="well well-lg" action="/Library/ContactAuthor" method="post">
						<div class="form-group has-feedback">
							<input type="text" class="form-control" name="headerMessageMail" placeholder="Your title..." value="Ask for validating <?php echo $app->getNomApplication();?> application..."/>
							<span class="glyphicon form-control-feedback"></span>
							<span class="help-block"></span>
						</div>
						<div class="form-group has-feedback">
							<textarea class="form-control" name="bodyMessageMail" placeholder="Your message..."></textarea>
							<span class="glyphicon form-control-feedback"></span>
							<span class="help-block"></span>
						</div>
						<input type="hidden" name="nomApplication" value="<?php echo $app->getNomApplication();?>">
						<input type="hidden" name="idApplication" value="<?php echo $app->getIdApplication();?>">
						<input type="hidden" name="idAuteur" value="<?php echo $app->getCreateur()->getIdUtilisateur(); ?>">
						<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Loading..." type="submit">
					    Send
					 	</button>
					</form>
		      	</div>
		    </div>
		</div>
	</div>