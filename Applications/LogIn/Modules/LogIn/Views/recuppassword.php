	<div class="container">
		<div class="row">
			<div class="col-lg-12 fadeIn">
				<div class="col-lg-6">
					<h1>Forgotten password.</h1>
					<h2>Please enter your email below and we’ll send you a link to reset your password.</h2>
				</div>

				<form class="well well-lg col-lg-5" method="post" action="/LogIn/RecupPassword/Request">
					<div class="form-group">
						<input type="email" class="form-control input-lg" name="adresseMail" placeholder= "Your email address" value="<?php if($user->getAttribute('mailUser') != null){echo $user->getAttribute('mailUser');}?>"/>
					</div>
					<button class="btn btn-primary" type="submit">Ask a new password</button>
				</form>
			</div>
		</div>
	</div>


