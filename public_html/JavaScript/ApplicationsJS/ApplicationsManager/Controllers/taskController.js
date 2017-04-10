// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour la fenêtre modale New Task							  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  newTaskController
 * @access: public
 * @version: 1
 */

application.controller('taskController', ['$scope', '$uibModalInstance', '$http', 'typeData', 'idTache', function($scope, $uibModalInstance, $http, typeData, idTache){
	
	// Position par defaut des boutons envoyer
	$scope.displayButtonForm = false;
	$scope.displayButtonDelete = false;

	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};

	// Pour initialiser les variables du formulaire
	$scope.dataTypes = typeData['dataTypes'];
	$scope.dataUnits = typeData['dataUnits'];
	$scope.application.versions.forEach(function(version){
		version.taches.forEach(function(tache){
			if(tache.id == idTache){
				$scope.idTache = tache.id;
				$scope.nomTache = tache.nom;
				$scope.descriptionTache = tache.description;
				$scope.typesTache = tache.types;
			}
		});
	});
	// Initialiser les numéro des types de données
	var description = [];
	for (var i = 0; i < $scope.typesTache.length; ++i) {
		description.push('description'+i);
	}
	$scope.description = description;
	

	// Pour mettre à jour la tâche
	$scope.formValidTask = function(e){
		if($scope.formTask.$valid){
			$scope.displayButtonForm = true;
			treeHasChanged = true;

			var formData = new FormData(e.target);

			$http({
                url: '/HandleApplication/ValidModifTache',
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
	}


	// Pour supprimer la tâche
	$scope.formValidDeleteTask = function(e){
		$scope.displayButtonDelete = true;
		treeHasChanged = true;
		var formData = new FormData(e.target);
		
		$http({
            url: '/HandleApplication/DeleteTache',
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
}]);

