// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour Step1 de l'application SubmitAnApplication			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  controllerStep1
 * @access: public
 * @version: 1
 */

application.controller('controllerStep1', ['$scope', '$http', '$location', 'tableOfCategories', 'invalidNameTooShort', function($scope, $http, $location, tableOfCategories, invalidNameTooShort){
	
	// Initialisation
	$scope.validStep2 = false;

	// On renseigne les catégories dans le select
	$scope.tableOfCategories = tableOfCategories;
	$scope.selectedCategory = $scope.tableOfCategories[0];

	// On renseigne les variables d'information
	$scope.infoInvalidNameApplication = invalidNameTooShort;

	// Action lors de la soumission du formulaire
	$scope.validStep1 = function(){
		if($scope.formStep1.$valid){
			$scope.validStep2 = true;
			$http({
				method: 'POST',
				url: '/SubmitAnApplication/ValidStep1',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
			    },
				data: {
					nomApp: $scope.nomApp,
					descriptionApp: $scope.descriptionApp,
					categorieApp: $scope.selectedCategory.nameCategory
				}
			})
			.success(function(response){
				if(response['reussites']){
					$location.url('/SubmitAnApplicationStep2/');
					$location.replace(); // Permet de ne pas créer d'historique
				}else{
					displayInformationsClient(response);
				}
			})
			.error(function(error){
				var response = {
					'erreurs': '<p>A system error has occurred: '+error+'</p>'
				};
				displayInformationsClient(response);
				$scope.validStep2 = false;
			});
		}
	};
	
}]);

