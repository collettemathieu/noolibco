<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-sm-6 col-sm-offset-1 sousMenu">
			<h2>Account settings</h2>
			<h3>Delete your account forever</h3>
			<blockquote>
				Deleting your account means that all of the information you have added in the past will be lost, including your applications, data, and stats...
					If you delete your account, you will not be able to re-activate it later.
			</blockquote>

			<form method="post" class="well well-lg" action="/Settings/ParametresCompte/SupprimerUtilisateur">
				<div class="form-group">
					<label for="passwordUser">Please enter your password</label>
					<input type="password" class="form-control input-lg" id="passwordUser" name="password"/>
				</div>
				<button class="btn btn-primary" type="submit">Delete my account forever</button>
			</form>
		</div>
	</div>
</div>