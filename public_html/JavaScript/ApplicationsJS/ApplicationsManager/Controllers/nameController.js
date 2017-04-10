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

application.controller('nameController', ['$scope', '$uibModalInstance', '$http', 'invalidNameTooShort',function($scope, $uibModalInstance, $http, invalidNameTooShort){
	
	// Position par defaut du bouton envoyer
	$scope.displayButtonForm = false;

	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};

	// On renseigne les variables d'information
	$scope.infoInvalidNameApplication = invalidNameTooShort;

	// Pour soumettre le formulaire
	$scope.validFormName = function(e){
		if($scope.formNameApp.$valid){
			$scope.displayButtonForm = true;

			var formData = new FormData(e.target);
			
			$http({
				method: 'POST',
				url: '/HandleApplication/ChangeNameApplication',
				headers: {'Content-Type': undefined},
				data: formData
			})
			.success(function(response){
				if(response['nameApplication']){
					$scope.application.nom = response['nameApplication'];
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

