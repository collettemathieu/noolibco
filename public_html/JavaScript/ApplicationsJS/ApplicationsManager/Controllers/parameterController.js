// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour la fenêtre modale gérer les paramètres				  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  parameterController
 * @access: public
 * @version: 1
 */

application.controller('parameterController', ['$scope', '$uibModalInstance', '$http', 'idParameter', 'typesParameter', function($scope, $uibModalInstance, $http, idParameter, typesParameter){
	
	// Position par defaut du bouton envoyer
	$scope.displayButtonForm = false;
	$scope.displayButtonDelete = false;

	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};

	// Initialisation des variables
	$scope.typesParameter = typesParameter;
	$scope.application.versions.forEach(function(version){
		version.taches.forEach(function(tache){
			tache.fonctions.forEach(function(fonction){
				fonction.parameters.forEach(function(parameter){
					if(parameter.id == idParameter){
						$scope.idParameter = idParameter;
						$scope.nomParametre = parameter.nom;
						$scope.descriptionParametre = parameter.description;
						$scope.valeurMinParametre = parameter.valeurMin;
						$scope.valeurMaxParametre = parameter.valeurMax;
						$scope.valeurDefautParametre = parameter.valeurDefaut;
						$scope.valeurPasParametre = parameter.valeurPas;
						typesParameter.forEach(function(type, index){
							if(type.name === parameter.typeAffichageParametre){
								$scope.indexType = index;
							}
						});
						$scope.status = parameter.statusParametre ? '1' : '0';
					}
				});
			});
		});
	});

	// Pour soumettre le formulaire
	$scope.formValidParameter = function(e){
		if($scope.formParameter.$valid){
			$scope.displayButtonForm = true;
			
			var formData = new FormData(e.target);

			$http({
                url: '/HandleApplication/ValidModifParametre',
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


	// Pour supprimer le paramètre
	$scope.formValidDeleteParameter = function(e){
		$scope.displayButtonDelete = true;
		var formData = new FormData(e.target);
		
		$http({
            url: '/HandleApplication/DeleteParametre',
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
			// Evènement de l'arbre des applications
			$scope.$emit('treeHasChanged', false);
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

