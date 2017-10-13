	<div class="container">
		<div class="row">
			<!-- Si JS n'est pas activé -->
		    <noscript>
			    <div class="col-lg-12 alertJS">
			        <div class="col-lg-5 alert alert-warning">
			        	<span>JavaScript is not enabled.</span>
			        	<span>NooLib does not work properly without JavaScript enabled <a href="https://support.google.com/adsense/answer/12654?hl=fr" target="_blank">(Help?)</a>.</span>
			        </div>
			    </div>
		    </noscript>
			<div class="col-lg-12 fadeIn">
				<div class="col-sm-6">	
			        <div id="content">
						<h1>Welcome to NooLib.</h1>
						<h2>NooLib offers a library of scientific applications for Research.</h2>
						<h2>It is very simple to submit your own algorithms written in Matlab/JS/Java/PHP or Python.</h2>
						<h2>Interact smartly with other applications and share your results...</h2>
						<button class="btn btn-default" ng-click="aboutModal()">About</button>
						<button class="btn btn-primary" ng-click="registrationFormModal()">Sign up</button>
					</div>
				</div>
				<form name="formLogIn" class="well well-lg col-sm-5" method="post" action="/LogIn/ValidationLogIn">
					<div class="form-group has-feedback" ng-class="{'has-error':formLogIn.adresseMailLogIn.$invalid && formLogIn.adresseMailLogIn.$dirty, 'has-success':formLogIn.adresseMailLogIn.$valid}">
						<input type="email" required class="form-control input-lg" name="adresseMailLogIn" ng-model="adresseMailLogIn" placeholder= "Your email address" value="<?php if($user->getAttribute('mailUser') != null){echo $user->getAttribute('mailUser');}?>" ng-pattern="/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/"/>
						<span ng-class="{'glyphicon form-control-feedback glyphicon-remove':formLogIn.adresseMailLogIn.$invalid && formLogIn.adresseMailLogIn.$dirty, 'glyphicon form-control-feedback glyphicon-ok':formLogIn.adresseMailLogIn.$valid}"></span>
						<span ng-show="formLogIn.adresseMailLogIn.$invalid && formLogIn.adresseMailLogIn.$dirty" class="help-block">Enter a valid email address.</span>
					</div>
					<div class="form-group has-feedback" ng-class="{'has-error':formLogIn.motDePasseFormulaireLogIn.$invalid && formLogIn.motDePasseFormulaireLogIn.$dirty, 'has-success':formLogIn.motDePasseFormulaireLogIn.$valid}">
						<input type="password" required class="form-control input-lg" name="motDePasseFormulaireLogIn" ng-model="motDePasseFormulaireLogIn" placeholder="Your password" ng-pattern="/(?=(.*[A-Z]){1,})(?=(.*[a-z]){1,})(?=(.*[0-9]){1,})/"/>
						<span ng-class="{'glyphicon form-control-feedback glyphicon-remove':formLogIn.motDePasseFormulaireLogIn.$invalid && formLogIn.motDePasseFormulaireLogIn.$dirty, 'glyphicon form-control-feedback glyphicon-ok':formLogIn.motDePasseFormulaireLogIn.$valid}"></span>
						<span ng-show="formLogIn.motDePasseFormulaireLogIn.$invalid && formLogIn.motDePasseFormulaireLogIn.$dirty" class="help-block">Enter a password of at least eight characters in length with at least one number, uppercase and lowercase.</span>
					</div>
					<div class="checkbox">
						<label>
							<input switch-directive type="checkbox" name="resterConnecte"/> Remember me?
						</label>
						<a ng-click="forgottenPassword()">Forgot password?</a>
					</div>
					<button class="btn btn-primary" ng-disabled="formLogIn.$invalid" type="submit">Log In</button>
				</form>
			</div>
		</div>
	</div>