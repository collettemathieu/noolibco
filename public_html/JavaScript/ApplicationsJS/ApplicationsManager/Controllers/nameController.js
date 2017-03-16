// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour la fenêtre modale Logo								  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  logoController
 * @access: public
 * @version: 1
 */

application.controller('logoController', ['$scope', '$uibModalInstance', '$http', function($scope, $uibModalInstance, $http){
	
	// Position par defaut du bouton envoyer
	$scope.displayButtonForm = false;

	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};

	// Pour soumettre le formulaire
	$scope.validFormLogo = function(){
		if($scope.formLogoApp.$valid){
			$scope.displayButtonForm = true;

			var form = document.querySelector('#formLogoApp'),
				formData = new FormData(form);
			$http({
				method: 'POST',
				url: '/HandleApplication/ChangePictureApplication',
				headers: {'Content-Type': undefined},
				data: formData
			})
			.success(function(response){
				if(response['urlLogo']){
					$scope.application.urlLogo = response['urlLogo'];
				}

				displayInformationsClient(response);
				// Position par defaut du bouton envoyer
				$scope.displayButtonForm = false;
				// Fermer la fenêtre modale
				$uibModalInstance.dismiss('cancel');
			})
			.error(function(error){
				var response = {
					'erreurs': '<p>A system error has occurred: '+error+'</p>'
				};
				displayInformationsClient(response);
				// Position par defaut du bouton envoyer
				$scope.displayButtonForm = false;
				// Fermer la fenêtre modale
				$uibModalInstance.dismiss('cancel');
			});
		}
	}
}]);

