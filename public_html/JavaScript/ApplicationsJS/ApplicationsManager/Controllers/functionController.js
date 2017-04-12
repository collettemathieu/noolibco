// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour la fenêtre modale pour les Functions				  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  functionController
 * @access: public
 * @version: 1
 */

application.controller('functionController', ['$scope', '$uibModalInstance', '$http', 'idTache', 'idFunction', 'textFunction', function($scope, $uibModalInstance, $http, idTache, idFunction, textFunction){
	
	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};

	// Initialisation des variables
	$scope.idFunction = idFunction;
	$scope.urlDropZone = '/HandleApplication/ValidModifFonction';
	$scope.textFunction = textFunction;

	// On s'abonne à l'évènement de la dropZone
	$scope.$on('uploadEnded', function(evt, value){
		if(value){
			treeHasChanged = true;
			$uibModalInstance.dismiss('cancel');
		}
	});

	// Pour supprimer la fonction
	$scope.formValidDeleteFunction = function(e){
		$scope.displayButtonDelete = true;
		treeHasChanged = true;
		var formData = new FormData(e.target);
		
		$http({
            url: '/HandleApplication/DeleteFonction',
            method: 'POST',
            headers: {'Content-Type': undefined},
            transformRequest: angular.identity,
            data: formData
        })
		.success(function(response){
			
			displayInformationsClient(response);
			// Position par defaut du bouton envoyer
			$scope.displayButtonDelete = false;
			// Fermer la fenêtre modale
			$uibModalInstance.dismiss('cancel');
		})
		.error(function(error){
			var response = {
				'erreurs': '<p>A system error has occurred: '+error+'</p>'
			};
			displayInformationsClient(response);
			// Position par defaut du bouton envoyer
			$scope.displayButtonDelete = false;
			// Fermer la fenêtre modale
			$uibModalInstance.dismiss('cancel');
		});
	}

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

