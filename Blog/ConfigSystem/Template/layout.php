<!DOCTYPE HTML>
<html dir="ltr" lang="fr-FR">
<!--

 _____        _                            ___   ______             _   
/  ___|      (_)                          / _ \  | ___ \           | |  
\ `__.   ___  _   ___  _ __    ___  ___  / /_\ \ | |_/ /__ _  _ __ | |_ 
 `__. \ / __|| | / _ \| '_ \  / __|/ _ \ |  _  | |  __// _` || '__|| __|
/\__/ /| (__ | ||  __/| | | || (__|  __/ | | | | | |  | (_| || |   | |_ 
\____/  \___||_| \___||_| |_| \___|\___| \_| |_/ \_|   \__,_||_|    \__|


-->

	<head>
		
		<meta charset="UTF-8">

		<!-- Mobile -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

		<!-- FrameWorks CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css"/>

		<!-- Files CSS -->
		<link rel="stylesheet" href="/Css/styleGeneral.css" />
		<link rel="stylesheet" href="/Css/styleHeader.css" />
		<link rel="stylesheet" href="/Css/styleFooter.css" />

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
		
		<meta name="keywords" content="blog, science, article, cours, collette, mathieu, science à part, pédagogie, enseignement, étudiant, université, laboratoire, entreprise, plateforme, collaboratif"/>
		
		<meta name="description" content="Bienvenue sur ScienceAPart, le blog qui permet d'aborder les sciences avec philosophie."/>
		
		<title><?php
			$URI = $_SERVER['REQUEST_URI'];
			if($URI == '/'){
				echo 'ScienceAPart : démêler la science !';
			}else{
				$repertoires = explode('/', $URI);
				if(empty($repertoires[count($repertoires)-1])){
					echo $repertoires[count($repertoires)-2].' - ScienceAPart';
				}else{
					echo $repertoires[count($repertoires)-1];
				}
			}
		?></title>

		<!-- Redirection sans JS -->
	    <noscript>
	    	<meta http-equiv="refresh" content="0;URL=/LogIn/JSNotActivated">
	    </noscript>

	    <!-- Google Analytics -->
	    <script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-87133497-1', 'auto');
		  ga('send', 'pageview');

		</script>
	</head>
	
	
	<body>
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
		<!-- Critical scripts -->
		<script type="text/javascript" src="/JavaScript/initBrowser.js"></script>
		<!-- Files JS -->
		<script type="text/javascript" src="/JavaScript/displayInformationsClient.js"></script>
		<!-- Chargement des librairies JS -->
		<?php echo $librairiesJS; ?>
	</body>
</html>