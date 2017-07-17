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

		<!-- FrameWorks CSS -->
		<link rel="stylesheet" href="/Css/Frameworks/styleBreadCrumb.min.css"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" crossorigin="anonymous"/>
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" crossorigin="anonymous"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css" crossorigin="anonymous"/>

		<!-- Files CSS -->
		<link rel="stylesheet" href="/Css/styleGeneral.css" />
		<link rel="stylesheet" href="/Css/styleHeader.css" />
		<link rel="stylesheet" href="/Css/styleSection.css" />
		<link rel="stylesheet" href="/Css/styleDockApplication.css" />
		<link rel="stylesheet" href="/Css/styleMenuContextuel.css" />

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
		<?php
			$userSession = unserialize($user->getAttribute('userSession'));
		?>
		<title><?php echo $userSession->getPrenomUtilisateur().' '.strtoupper($userSession->getNomUtilisateur());?> - NooLib</title>

		<!-- Ajout d'une base pour les routes AngularJS -->
		<base href="/">

		<!-- Redirection sans JS -->
	    <noscript>
	    	<meta http-equiv="refresh" content="0;URL=/LogIn/JSNotActivated">
	    </noscript>

	</head>
	
	
	<body style="
		<?php
			echo( 'background-image: url(' . $userSession->getUrlBackgroundUtilisateur() . ')' );
		?>
	">

		<!-- LOADER -->
		<div class="overlay">
			<div class="globalLoader"></div>
		</div>
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
		
		<!-- Informations à l'utilisateur -->
		<div class="informationsClient" id="informationsClient">
			<?php if($user->getMessageClient()->hasErreur()) { ?>
				<div class="alert alert-danger alert-dismissable" id="informationExists">
					<button type="button" class="close" data-dismiss="alert">x</button>
					<h3>Warning</h3>
					<?php foreach($user->getMessageClient()->getErreurs() as $erreur) { ?>
						<p><?php echo($erreur); ?></p>
					<?php } ?>
				</div>

			<?php }
			if($user->getMessageClient()->hasReussite()){ ?>
				<div class="alert alert-success alert-dismissable" id="informationExists">
					<button type="button" class="close" data-dismiss="alert">x</button>
					<h3>Information</h3>
					<?php foreach($user->getMessageClient()->getReussites() as $reussite) { ?>
						<p><?php echo($reussite); ?></p>
					<?php } ?>
				</div>
			<?php } ?>
		</div>

		
		<!-- FrameWorks and Libraries JS -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
		<script type="text/javascript" src="/JavaScript/Frameworks/jquery.contextMenu.min.js"></script>
		<script type="text/javascript" src="/JavaScript/Frameworks/jquery.ui.position.min.js"></script>
		<script type="text/javascript" src="/JavaScript/Frameworks/jquery.easing.1.3.min.js"></script>
		<script type="text/javascript" src="/JavaScript/Frameworks/jquery.jBreadCrumb.1.1.min.js"></script>
		
		<!-- Boostrap -->
		<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/JavaScript/Frameworks/bootstrap-filestyle.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

		<!-- Initialisation -->
		<script type="text/javascript" src="/JavaScript/init.js"></script>

		<!-- Chargement des librairies JS -->
		<?php echo $librairiesJS; ?>		
	</body>
</html>