<!DOCTYPE HTML>
<html dir="ltr" lang="fr-FR">
<!--

 _   _             _      _ _     
| \ | |           | |    (_) |    
|  \| | ___   ___ | |     _| |__  
| . ` |/ _ \ / _ \| |    | | '_ \ 
| |\  | (_) | (_) | |____| | |_) |
|_| \_|\___/ \___/|______|_|_.__/  Hire !

Go to https://www.noolib.com/Hire/ .

-->

	<head>
		
		<meta charset="UTF-8">

		<!-- Mobile -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

		<!-- Chargement des librairies CSS -->
		<?php echo $librairiesCSS; ?>

		<!-- Insertion d'une icône dans la barre latérale du navigateur -->
		<link rel="apple-touch-icon" sizes="57x57" href="/Images/Favicon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/Images/Favicon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/Images/Favicon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/Images/Favicon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/Images/Favicon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/Images/Favicon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/Images/Favicon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/Images/Favicon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/Images/Favicon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/Images/Favicon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/Images/Favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/Images/Favicon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/Images/Favicon/favicon-16x16.png">
		<link rel="manifest" href="/Images/Favicon/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/Images/Favicon/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		
		<meta name="keywords" content="Library, application, research, plateform, internet, scientific, program, informatic, c, c++, python, java, javascript, php, submit, promote, simulator, education, researches, researcher, phd, student, engineer, university, school, professor"/>
		
		<meta name="description" content="Create and promote your scientific applications for your researches."/>
		<title>Welcome - NooLib Web Application</title>
	</head>

	<body ng-app="LogIn" ng-controller="logInController" ng-strict-di><!-- ng-strict-di for throwing an exception when minify operation errors appeared -->

		<!-- LOADER -->
		<?php if($_SERVER['REQUEST_URI'] != '/LogIn/JSNotActivated'){?>
		<div class="overlay">
			<div class="globalLoader"></div>
		</div>
		<?php }?>
		<script>
		window.onload=function(){
			$('.overlay').fadeOut();
		};
		</script>

		
		<!-- HEADER -->
		<header></header>

		<!-- SECTION -->
		<section>
		<?php
			echo $content;
		?>
		</section>
		
		<!-- FOOTER -->
		<footer>
			<div class="container">
				<div class="row">
					<div class="col-lg-12 centering maxWidth">
						<div class="col-lg-7 text-center centering">
							<h5>© 2017 NooLib - All rights reserved - </h5>
							<a href="https://blog.noolib.com" target="_blank" class="btn btn-primary btn-xs" data-toggle="tooltip" title="The NooLib Blog!">The Blog</a>
							<button class="btn btn-default btn-xs" data-toggle="modal" data-target="#team">Team</button>
							<button ng-click="contactModal()" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Contact us!">Contact</button>
							<ul>
								<li><a href="https://www.tipeee.com/noolib" target="_blank" data-toggle="tooltip" title="Help us on tipeee!"><img src="/Images/Social/tipeee.png"/></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</footer>

		<div id="team" class="modal fade" role="dialog">
		  <div class="modal-dialog modal-lg">

		    <!-- Modal content-->
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">x</button>
					<h2 class="teamTitle modal-title">The NooLib Team</h2>
				</div>
				<div class="modal-body">
					<ul class="list-unstyled">
						<li><h3>Authors (Thanks a lot)</h3>
							<ul class="list-unstyled">
								<li class="teamPicture"><span></span><p class="text-center">Léna Buron (2 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Corentin Chevallier (2 months)</p></li>
								<li class="teamPicture"><a href="/Profile/idAuteur=122"><span></span><p class="text-center">Quentin Denis (2 months)</p></a></li>
								<li class="teamPicture"><a href="/Profile/idAuteur=79"><span></span><p class="text-center">Guénaël Dequeker (5 months)</p></a></li>
								<li class="teamPicture"><span></span><p class="text-center">Steve Despres (3 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Remi Dugue (2 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Antoine Fauchard (2 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Kévin Grosbois (1 month)</p></li>
								<li class="teamPicture"><a href="/Profile/idAuteur=97"><span></span><p class="text-center">Naoures Hassine (6 months)</p></a></li>
								<li class="teamPicture"><span></span><p class="text-center">Baptiste Houssais (1 month)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Brian Le Bras (1 month)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Jean Mainguy (1 month)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Baptiste Maudet (2 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Yohann Pichois (1 month)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Clément Richard (1 month)</p></li>
							</ul>
						</li>
						<li><h3>Founder & author</h3><ul class="list-unstyled"><li class="teamPicture"><a href="/Profile/idAuteur=58"><div class="founder"></div><p class="text-center">Mathieu Collette</p></a></li></ul></li>
						<li><h3>Partners</h3>
						<ul class="list-inline">
							<li>
								<a href="https://www.chu-angers.fr/" target="_blank"><img src="/Images/Social/chuAngers.png" alt="Centre hospitalier et universitaire d'Angers"/></a>
							</li>
							<li>
								<a href="http://www.uco.fr/ima/" target="_blank"><img src="/Images/Social/ima.jpg" alt="Institut de Mathématiques Appliquées de l'Université Catholique de l'Ouest"/></a>
							</li>
						</ul>
					</li>
				</ul>
		      </div>
		    </div>
		  </div>
		</div>

		<!-- Chargement des librairies JS -->
		<?php echo $librairiesJS; ?>
	</body>
</html>