// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour la fenêtre modale New Function						  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  newFunctionController
 * @access: public
 * @version: 1
 */

application.controller('newFunctionController', ['$scope', '$uibModalInstance', '$http', 'idTache', function($scope, $uibModalInstance, $http, idTache){
	
	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};

	// Initialisation des variables
	$scope.idTache = idTache;
	$scope.treeHasChanged = false;

	// Pour soumettre le formulaire
	/*
	$scope.formValidNewTask = function(e){
		if($scope.formNewTask.$valid){
			$scope.displayButtonForm = true;
			treeHasChanged = true;

			var formData = new FormData(e.target);

			$http({
                url: '/HandleApplication/ValidFormTache',
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
	}*/
}]);

