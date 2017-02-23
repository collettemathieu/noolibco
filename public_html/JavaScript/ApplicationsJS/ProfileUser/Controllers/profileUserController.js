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

	$scope.idEtablissement = null;
	// On récupère la liste des laboratoires
	$scope.$watch("idEtablissement", function(newIdEtablissement){
		teamService.getLaboratories(newIdEtablissement).then(function(laboratoires){ // <- c'est une promise
			console.log(laboratoires);
			$scope.laboratoires = laboratoires;
		}, function(error){
			displayInformationsClient(error);
		});
	});
}]);

