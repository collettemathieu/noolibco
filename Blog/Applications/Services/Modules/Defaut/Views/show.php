	<div class="services service1">
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="col-lg-12 maxWidth centering">
					<div class="col-sm-offset-1 col-sm-5 service">
						<h1>Pour tout le monde</h1>
						<p>NooLib The Blog est réalisé et enrichi par des passionnés. Cependant, la création
							des articles demande beaucoup de temps de préparation (écriture, 
							mise en forme, mise en ligne, etc.). Le maintien de la plateforme sur 
							différents supports informatiques est également très chronophage.
						</p>
						<p>Vous pouvez aider le développement de NooLib en
							 apportant votre contribution financière. Dans ce cas, rendez-vous
							 sur notre page <a href="https://www.tipeee.com/noolib" target="_blank">Tipeee</a>. 
							 Il n'y a évidement aucune 
							obligation à cela et nous nous efforçerons toujours
							de vous fournir un contenu de qualité.
						</p>
						<p>Et si vous avez une remarque à nous adresser,
							n'hésitez pas à nous <a href="#contact">contacter</a> directement.</p>
						<form class="well well-lg" method="post" action="/NewsLetter/AjouterUtilisateur/">
							<legend>Envie de recevoir la newsletter ?</legend>
							<div class="form-group">
								<input type="input" required name="nom" class="form-control" placeholder="Entrez votre nom..."/>
							</div>
							<div class="form-group">
								<input type="email" required name="adresseMail" class="form-control" placeholder="Entrez votre adresse électonique..."/>
							</div>
							<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Traitement..." type="submit">C'est parti !</button>	
						</form>
					</div>
					<div class="col-sm-5 text-center imageService">
						<img class="hidden-xs" src="/Images/computer.png"/>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="services service2">
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="col-lg-12 maxWidth centering">
					<div class="col-sm-5 text-center imageService">
						<img class="hidden-xs" src="/Images/serviceEnseignementSup.png"/>
					</div>
					<div class="col-sm-offset-1 col-sm-5 service">
						<h1>Pour les écoles et les laboratoires</h1>
						<p>NooLib est bien au fait de la recherche académique et collabore par différentes voies :</p>
						<ul>
							<li>Aide à la diffusion de vos algorithmes de Recherche via notre plateforme <a class="noolibService" href="https://wwww.noolib.com" target="_blank">NooLib</a></li>
							<li>Aide à la valorisation de vos activités de Recherche auprès du grand public</li>
							<li>Mise en place de projets collaboratifs</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="services service4">
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="col-lg-12 maxWidth centering">
					<div class="col-sm-offset-1 col-sm-5 service">
						<h1>Pour les entreprises</h1>
						<p>Nous avons l’habitude de collaborer avec des entreprises de différentes manières :</p>
						<ul>
							<li>Insertion intelligente de bannières publicitaires</li>
							<li>Affiliation</li>
							<li>Mise en place de projets collaboratifs</li>
							<li>Création de contenus dédiés hébergés sur <a class="noolibService" href="https://www.noolib.com" target="_blank">NooLib</a> ou sur votre site Internet</li>
							<li>Création de contenus en marque blanche</li>
						</ul>
						<p>Nous serions ravis d’échanger avec vous sur vos problématiques afin d’y répondre via une prestation sur-mesure.</p>
					</div>
					<div class="col-sm-5 text-center imageService">
						<img class="hidden-xs" src="/Images/entreprise3.png"/>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="services service1">
		<div id="contact" class="container-fluid">
			<div class="row-fluid services service1">
				<div class="col-lg-12 maxWidth centering">
					<div class="col-sm-5 centering service">
						<h1>Contactez-nous !</h1>
						<form class="well well-lg" method="post" action="/Services/Contact/">
							<legend>Petit formulaire à remplir !</legend>
							<div class="form-group">
								<label for="nom">Votre nom</label>
								<input type="input" id="nom" required name="nom" class="form-control" placeholder="Entrez votre nom..."/>
							</div>
							<div class="form-group">
								<label for="adresseMail">Votre adresse de contact</label>
								<input type="email" id="adresseMail" required name="adresseMail" class="form-control" placeholder="Entrez votre adresse électonique..."/>
							</div>
							<div class="form-group">
								<label for="select">Objet du contact</label>
								<select id="select" name="sujet" class="form-control">
									<option>Question ?</option>
									<option>Affiliation</option>
									<option>Publicité</option>
									<option>Projet collaboratif</option>
									<option>Aide à la valorisation</option>
									<option>Aide à la diffusion</option>
									<option>Autre ?</option>
								</select>
							</div>
							<div class="form-group">
								<label for="message">Votre message</label>
								<textarea rows="4" id="message" required name="message" class="form-control" placeholder="Votre texte..."></textarea>
							</div>
							<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Traitement..." type="submit">Envoyer</button>	
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>