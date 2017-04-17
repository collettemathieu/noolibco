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

application.controller('authorsController', ['$scope', '$uibModalInstance', '$http', function($scope, $uibModalInstance, $http){
	
	// Position par defaut du bouton envoyer
	$scope.displayButtonForm = false;

	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};

	// Pour soumettre le formulaire
	$scope.validFormContributor = function(e){
		if($scope.formAddContributor.$valid){
			$scope.displayButtonForm = true;

			var formData = new FormData(e.target);
			formData.append('idApplication', $scope.application.id);

			$http({
				method: 'POST',
				url: '/HandleApplication/AddAuthor',
				headers: {'Content-Type': undefined},
                transformRequest: angular.identity,
                data: formData
			})
			.success(function(response){
				if(response['contributeurs']){
					$scope.application.contributeurs = response['contributeurs'];
				}

				displayInformationsClient(response);
				
				// Position par defaut du bouton envoyer
				$scope.displayButtonForm = false;
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
	};

	// Pour supprimer un auteur de l'application
    $scope.removeAuthor = function(idApp, idAuteur){
        // Envoi de la requête HTTP en mode asynchrone
        $http({
			method: 'POST',
			url: '/HandleApplication/RemoveAuthor',
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
		    },
			data: {
				idApplication: idApp,
				idAuteur: idAuteur
			}
		})
		.success(function(response){
			if(response['contributeurs']){
				$scope.application.contributeurs = response['contributeurs'];
			}

			displayInformationsClient(response);
			
			// Position par defaut du bouton envoyer
			$scope.displayButtonForm = false;
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
    };

}]);

