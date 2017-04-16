// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour la fenêtre modale New Parameter					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  newParameterController
 * @access: public
 * @version: 1
 */

application.controller('newParameterController', ['$scope', '$uibModalInstance', '$http', 'idFonction', 'typesParameter', function($scope, $uibModalInstance, $http, idFonction, typesParameter){
	
	// Position par defaut du bouton envoyer
	$scope.displayButtonForm = false;

	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};

	// Initialisation des variables
	$scope.idFonction = idFonction;
	$scope.typesParameter = typesParameter;

	// Pour soumettre le formulaire
	$scope.formValidNewParameter = function(e){
		if($scope.formNewParameter.$valid){
			$scope.displayButtonForm = true;
			
			var formData = new FormData(e.target);

			$http({
                url: '/HandleApplication/ValidFormParametre',
                method: 'POST',
                headers: {'Content-Type': undefined},
                transformRequest: angular.identity,
                data: formData
            })
			.success(function(response){
				
				displayInformationsClient(response);
				// Position par defaut du bouton envoyer
				$scope.displayButtonForm = false;
				// Fermer la fenêtre modale
				$uibModalInstance.dismiss('cancel');
				// Evènement de l'arbre des applications
				$scope.$emit('treeHasChanged', false);
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

