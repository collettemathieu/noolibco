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

application.controller('deleteController', ['$scope', '$uibModalInstance', '$http', function($scope, $uibModalInstance, $http){
	
	// Position par defaut du bouton envoyer
	$scope.displayButtonForm = false;

	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};

	// Pour supprimer l'application
    $scope.removeApplication = function(idApp){
    	// Position loading du bouton envoyer
		$scope.displayButtonForm = true;

        // Envoi de la requête HTTP en mode asynchrone
        $http({
			method: 'POST',
			url: '/HandleApplication/Delete',
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
		    },
			data: {
				idApplication: idApp
			}
		})
		.success(function(response){
			if(response['reussites']){
				setTimeout(function(){
	                location.reload();
	            }, 1000);
			}
			displayInformationsClient(response);
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

