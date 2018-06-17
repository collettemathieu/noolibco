<!DOCTYPE HTML>
<html dir="ltr" lang="fr-FR">
<!--
  _   _             _      _ _       _______ _            ____  _             
 | \ | |           | |    (_) |     |__   __| |          |  _ \| |            
 |  \| | ___   ___ | |     _| |__      | |  | |__   ___  | |_) | | ___   __ _ 
 | . ` |/ _ \ / _ \| |    | | '_ \     | |  | '_ \ / _ \ |  _ <| |/ _ \ / _` |
 | |\  | (_) | (_) | |____| | |_) |    | |  | | | |  __/ | |_) | | (_) | (_| |
 |_| \_|\___/ \___/|______|_|_.__/     |_|  |_| |_|\___| |____/|_|\___/ \__, |
                                                                         __/ |
                                                                        |___/ 
-->

	<head>
		
		<meta charset="UTF-8">
		<meta name="robots" content="noindex" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

		<!-- FrameWorks CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css"/>
		
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
		
		<meta name="keywords" content="blog, science, article, cours, pédagogie, enseignement, étudiant, université, laboratoire, entreprise, plateforme, collaboratif"/>
		
		<meta name="description" content="Bienvenue sur le Blog de NooLib."/>
		
		<title><?php
			$URI = $_SERVER['REQUEST_URI'];
			if($URI == '/'){
				echo 'NooLib The Blog';
			}else{
				$repertoires = explode('/', $URI);
				if(empty($repertoires[count($repertoires)-1])){
					echo $repertoires[count($repertoires)-2].' - NooLib';
				}else{
					echo $repertoires[count($repertoires)-1];
				}
			}
		?></title>

		<!-- Redirection sans JS -->
	    <noscript>
	    	<meta http-equiv="refresh" content="0;URL=/LogIn/JSNotActivated">
	    </noscript>
	</head>
	
	
	<body>

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
					<h3>Attention</h3>
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
		<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/JavaScript/Frameworks/dropzone.min.js"></script>
		<script type="text/javascript" src="/JavaScript/Frameworks/progressBar.min.js"></script>
		<script type="text/javascript" src="/JavaScript/Frameworks/loDash.min.js"></script>
		
		<!-- Files JS -->
		<script type="text/javascript" src="/JavaScript/dropZoneFunction.js"></script>
		<script type="text/javascript" src="/JavaScript/managerUtilisateurs.js"></script>
		<script type="text/javascript" src="/JavaScript/managerCommentairesBackend.js"></script>
		<script type="text/javascript" src="/JavaScript/managerActualites.js"></script>
		<script type="text/javascript" src="/JavaScript/managerArticles.js"></script>
		<script type="text/javascript" src="/JavaScript/managerCours.js"></script>
		<script type="text/javascript" src="/JavaScript/managerCoursGlobal.js"></script>
		<script type="text/javascript" src="/JavaScript/managerMedias.js"></script>
		<script type="text/javascript" src="/JavaScript/initBackend.js"></script>

		<!-- Critical scripts -->
		<script type="text/javascript" src="/JavaScript/init.js"></script>

		<!-- Chargement des librairies JS -->
		<?php echo $librairiesJS; ?>
		
		
	</body>
</html>