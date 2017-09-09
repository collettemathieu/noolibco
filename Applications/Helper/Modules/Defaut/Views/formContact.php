<form id="formContact" class="well well-lg col-lg-10 centering">
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
		<textarea class="form-control" rows="5" name="bodyMessageMail" placeholder="Your message..."></textarea>
		<span class="glyphicon form-control-feedback"></span>
		<span class="help-block"></span>
	</div>
	<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Loading..." type="submit">
    Send
 	</button>
</form><br/>
<div class="alert alert-warning col-lg-10 centering">Be confident, your email address will not be used for marketing purposes, sold or shared with third parties.</div>
