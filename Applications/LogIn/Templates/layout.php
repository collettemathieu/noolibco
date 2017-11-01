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

		<!-- Ajout d'une base pour les routes AngularJS -->
		<base href="/">
		
		<!-- Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109093444-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-109093444-1');
		</script>
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
		<?php
			include_once('../public_html/header.php');
		?>
		
		<!-- SECTION -->
		<section>
		<?php
			echo $content;
		?>
		</section>
		
		<!-- FOOTER -->
		<?php
			include_once('../public_html/footer.php');
		?>

		<!-- Informations à l'utilisateur-->
		<div class="informationsClient" id="informationsClient">
			<?php if($user->getMessageClient()->hasErreur()) { ?>
				<div class="alert alert-danger" id="informationExists">
					<h3>Warning</h3>
					<?php foreach($user->getMessageClient()->getErreurs() as $erreur) { ?>
						<p><?php echo($erreur); ?></p>
					<?php } ?>
				</div>

			<?php }
			if($user->getMessageClient()->hasReussite()){ ?>
				<div class="alert alert-success" id="informationExists">
					<h3>Information</h3>
					<?php foreach($user->getMessageClient()->getReussites() as $reussite) { ?>
						<p><?php echo($reussite); ?></p>
					<?php } ?>
				</div>
			<?php } ?>
		</div>

		<!-- Chargement des librairies JS -->
		<?php echo $librairiesJS; ?>
	</body>
</html>