<!DOCTYPE HTML>
<html dir="ltr" lang="fr-FR">
<!--

  ___   _                    _    _____        _                         
 / _ \ | |                  | |  /  ___|      (_)                        
/ /_\ \| |__    ___   _   _ | |_ \ `-.    ___  _   ___  _ __    ___  ___ 
|  _  || '_ \  / _ \ | | | || __| ` -. \ / __|| | / _ \| '_ \  / __|/ _ \
| | | || |_) || (_) || |_| || |_ /\__/ /| (__ | ||  __/| | | || (__|  __/
\_| |_/|_.__/  \___/  \__,_| \__|\____/  \___||_| \___||_| |_| \___|\___|


-->

	<head>
		
		<meta charset="UTF-8">
		<meta name="robots" content="noindex" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

		<!-- FrameWorks CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
		
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
		
		<meta name="keywords" content="blog, science, article, cours, collette, mathieu, pédagogie, enseignement, étudiant, université, laboratoire, entreprise, plateforme, collaboratif"/>
		
		<meta name="description" content="Bienvenue sur ScienceAPart, le blog qui permet d'aborder les sciences avec philosophie."/>

		<title>ScienceAPart : démêler la science !</title>
		<!-- Redirection si JS -->
		<script>
		var url = document.location.href;
		if(url.indexOf("/LogIn/InvalidBrowser/") === -1){
			document.location.href = '/';
		}
		</script>
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
		
		<!-- Chargement des librairies JS -->
		<?php echo $librairiesJS; ?>
		
		
	</body>
</html>