// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour la fenêtre modale Version							  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  versionController
 * @access: public
 * @version: 1
 */

application.controller('versionController', ['$scope', '$uibModalInstance', '$http', 'applicationService', function($scope, $uibModalInstance, $http, applicationService){
	
	// Position par defaut du bouton envoyer
	$scope.displayButtonForm = false;

	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};

	// Pour créer une nouvelle version de l'application
	$scope.validFormVersion = function(e){
		if($scope.formNewVersion.$valid){
			$scope.displayButtonForm = true;

			var formData = new FormData(e.target);
			
			$http({
				method: 'POST',
				url: '/HandleApplication/CreateNewVersionApplication',
				headers: {'Content-Type': undefined},
				data: formData
			})
			.success(function(response){
				if(response['versions']){
					$scope.application.versions = response['versions'];	
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

