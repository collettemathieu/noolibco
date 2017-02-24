	<div class="container">
		<div class="row">
			<div class="col-lg-12 fadeIn">
				<div class="col-lg-6">
					<h1>Reset your password.</h1>
					<h2>Enter a new password of at least eight letters in length with at least one number, uppercase and lowercase.</h2>
				</div>

				<form class="well well-lg col-lg-5" method="post" action="/LogIn/ResetPassword/">
					<div class="form-group">
						<input type="password" class="form-control input-lg" name="newPassword1" placeholder= "Your new password"/>
					</div>
					<div class="form-group">
						<input type="password" class="form-control input-lg" name="newPassword2" placeholder= "Confirm your new password"/>
					</div>
					<input type="hidden" name="jetonUser" value="<?php if(isset($jetonUser)){echo $jetonUser;}?>"/>
					<button class="btn btn-primary" type="submit">Send</button>
				</form>
			</div>
		</div>
	</div>


