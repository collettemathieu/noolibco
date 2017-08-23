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
				<div class="row red">
					<div class="col-lg-12 centering maxWidth">
						<div class="col-lg-7 centering">
							<h5>© 2017 NooLib - All rights reserved - </h5>
							<ul>
								<li><a href="https://www.tipeee.com/noolib" target="_blank" data-toggle="tooltip" title="Help us with tipeee!"><img src="/Images/Social/tipeee.png"/></a></li>
								<li><a href="https://blog.noolib.com" target="_blank" data-toggle="tooltip" title="NooLib The Blog!"><img src="/Images/Social/blog.png"/></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</footer>

		<!-- Chargement des librairies JS -->
		<?php echo $librairiesJS; ?>
	</body>
</html>