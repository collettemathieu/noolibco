// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour Step2 de l'application SubmitAnApplication			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  controllerStep2
 * @access: public
 * @version: 1
 */

application.controller('controllerStep2', ['$scope', '$http', '$location', function($scope, $http, $location){
	
	// Initialisation
	$scope.validStep3 = false;

	// Action lors de la soumission du formulaire
	$scope.validStep2 = function(){
		if($scope.formStep2.$valid){
			$scope.validStep3 = true;
			var form = document.querySelector('#form'),
				formData = new FormData(form);
			$http({
				method: 'POST',
				url: '/SubmitAnApplication/ValidStep2',
				headers: {'Content-Type': undefined},
				data: formData
			})
			.success(function(response){
				if(response['reussites']){
					$location.url('/SubmitAnApplicationStep3/');
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
				$scope.validStep3 = false;
			});
		}
	}
	
}]);

