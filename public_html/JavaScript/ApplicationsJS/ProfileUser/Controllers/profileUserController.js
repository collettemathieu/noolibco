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
	$scope.institutions = teamService.getInstitutions();
	console.log($scope.institutions[0].name);
}]);

