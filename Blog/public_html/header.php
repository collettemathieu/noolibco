<header>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	  <div class="container">
	  	<?php if(!$user->getAttribute('isAdmin')) { ?>
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="/">The blog</a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse " id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav">
	        <li><a href="/Articles/">Articles</a></li>
	        <li><a href="/Services/">Services</a></li>
	        <li><a href="https://www.noolib.com">noolib.com</a></li>
	      </ul>
	      <form class="navbar-form pull-right hidden-xs" action="/Sphinx/Search/" method="post">
	      	<input type="text" name="mots" class="input-sm form-control" placeholder="Rechercher..."/>
	      	<button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
    		</form>
	    </div>

	      <?php }else{ ?>
		<?php if($user->getAttribute('isSuperAdmin')){ ?>
		<!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse " id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav">
				<li><a href="/ForAdminOnly/Prez/">Prez</a></li>
				<li><a href="/ForAdminOnly/Articles/">Articles</a></li>
				<li><a href="/ForAdminOnly/CoursSelect/">Cours</a></li>
				<li><a href="/ForAdminOnly/Commentaires/">Commentaires</a></li>
				<li><a href="/ForAdminOnly/Utilisateurs/">Utilisateurs</a></li>
				<li><a href="/ForAdminOnly/Outils/">Outils</a></li>
				<li><a href="/ForAdminOnly/SortirDuModeAdmin">Quitter</a></li>
			</ul>
	    </div>

	    <?php }else{ ?>

	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse " id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav">
				<li><a href="/ForAdminOnly/Articles/">Articles</a></li>
				<li><a href="/ForAdminOnly/Cours/">Cours</a></li>
				<li><a href="/ForAdminOnly/SortirDuModeAdmin">Quitter</a></li>
			</ul>
	    </div>
	    <?php }}?>
	    <!--ALERT -->
	    <div id="alertBrowser" class="hidden alert alert-danger fade in">
	    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	    </div>
	  </div>
	</nav>
	
</header>