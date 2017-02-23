// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour la page des profils utilisateurs.					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  profileUserController
 * @access: public
 * @version: 1
 */

application.controller('profileUserController', ['teamService', '$scope', '$http', function(teamService, $scope, $http){
	// On récupère la liste des établissements
	teamService.getInstitutions().then(function(institutions){ // <- c'est une promise
		$scope.institutions = institutions;
	}, function(error){
		displayInformationsClient(error);
	});

	
	// On récupère la liste des laboratoires
	teamService.getLaboratories($scope.idEtablissement).then(function(laboratoires){ // <- c'est une promise
		$scope.laboratoires = laboratoires;
	}, function(error){
		displayInformationsClient(error);
	});
}]);
