<ul class="nav nav-pills">
	<li class="active"><a href="#videoOverview" data-toggle="tab">An overview of NooLib</a></li>
	<li><a href="#videoDataManager" data-toggle="tab">Discover your data manager</a></li>
	<li><a href="#contactNooLib" data-toggle="tab">Contact us</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active text-center" id="videoOverview">
		<br/>
		<iframe src="https://player.vimeo.com/video/157548147?title=0&byline=0&portrait=0" width="700" height="438" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
		<div class="alert alert-info col-lg-6 centering">Click on the CC button to active the legend of the video.</div>
	</div>
	<div class="tab-pane text-center" id="videoDataManager">
		<br/>
		<iframe src="https://player.vimeo.com/video/157559756?title=0&byline=0&portrait=0" width="700" height="438" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
		<div class="alert alert-info col-lg-6 centering">Click on the CC button to active the legend of the video.</div>
	</div>
	<div class="tab-pane" id="contactNooLib">
		<br/>
		<form id="formContact" class="well well-lg col-lg-6 centering">
			<div class="form-group has-feedback">
				<input type="text" class="form-control" name="emailAddress" placeholder="Your email..." value="<?php echo $mailUtilisateur;?>"/>
				<span class="glyphicon form-control-feedback"></span>
				<span class="help-block"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="text" class="form-control" name="headerMessageMail" placeholder="A title..."/>
				<span class="glyphicon form-control-feedback"></span>
				<span class="help-block"></span>
			</div>
			<div class="form-group has-feedback">
				<textarea class="form-control" name="bodyMessageMail" placeholder="Your message..."></textarea>
				<span class="glyphicon form-control-feedback"></span>
				<span class="help-block"></span>
			</div>
			<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Loading..." type="submit">
		    Send
		 	</button>
		</form><br/>
		<div class="alert alert-warning col-lg-6 centering">Be confident, your email address will not be used for marketing purposes, sold or shared with third parties.</div>
	</div>
</div>
