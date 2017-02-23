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
	teamService.getInstitutions().then(function(response){ // <- c'est une promise
		if(response['erreurs']){
			displayInformationsClient(response);
		}else{
			$scope.institutions = response;
		}
	}, function(error){
		var response = {
			'erreurs': '<p>A system error has occurred: '+error+'</p>'
		};
		displayInformationsClient(response);
	});

	
	// On récupère la liste des laboratoires
	$scope.$watch("selectedInstitution", function(newInstitution){
		if(typeof(newInstitution) != 'undefined'){
			teamService.getLaboratories(newInstitution.id).then(function(response){ // <- c'est une promise
				if(response['erreurs']){
					displayInformationsClient(response);
				}else{
					$scope.laboratories = response;
					$scope.selectedLaboratory = response[0];
				}
			}, function(error){
				var response = {
				  	'erreurs': '<p>A system error has occurred: '+error+'</p>'
				};
				displayInformationsClient(response);
			});
		}
	});


	// On récupère la liste des équipes
	$scope.$watch("selectedLaboratory", function(newLaboratory){
		if(typeof(newLaboratory) != 'undefined'){
			teamService.getTeams(newLaboratory.id).then(function(response){ // <- c'est une promise
				if(response['erreurs']){
					displayInformationsClient(response);
				}else{
					$scope.teams = response;
					$scope.selectedTeam = response[0];
				}
			}, function(error){
				var response = {
				  	'erreurs': '<p>A system error has occurred: '+error+'</p>'
				};
				displayInformationsClient(response);
			});
		}
	});
}]);

