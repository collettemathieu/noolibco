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

application.controller('descriptionController', ['$scope', '$uibModalInstance', '$http', 'tableOfCategories', function($scope, $uibModalInstance, $http, tableOfCategories){
	
	// Position par defaut du bouton envoyer
	$scope.displayButtonForm = false;

	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};

	// Pour initialiser le formulaire
	$scope.idApp = $scope.application.id;
	$scope.descriptionApp = $scope.application.description;
	//******Naoures
	$scope.lienApp = $scope.application.lien;
	//******
	$scope.motsClesApp = $scope.application.motCles;
	$scope.tableOfCategories = tableOfCategories;

	// Pour soumettre le formulaire
	$scope.validFormDescription = function(){
		if($scope.formDescriptionApp.$valid){
			$scope.displayButtonForm = true;

			$http({
				method: 'POST',
				url: '/HandleApplication/ChangeDescriptionApplication',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
			    },
				data: {
					idApp: $scope.idApp,
					motsClesApp: $scope.motsClesApp,
					descriptionApp: $scope.descriptionApp,
					lienApp: $scope.lienApp, //added by Naoures
					categorieApp: $scope.selectedCategory.nameCategory
				}
			})
			.success(function(response){
				if(response['description'] && response['lien'] && response['motCles'] && response['categorie']){
					$scope.application.description = response['description'];
					$scope.application.lien = response['lien']; //added by Naoures
					$scope.application.motCles = response['motCles'];
					$scope.application.categorie = response['categorie'];
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

