// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour l'arbre des applications							  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  mainController
 * @access: public
 * @version: 1
 */

application.controller('mainController', ['$scope', '$http', '$window', '$uibModal', function($scope, $http, $window, $uibModal){
	
	// Action lors de l'ouverture de la fenêtre modale "About"
	$scope.logoApplicationModal = function(){
		$uibModal.open({
	      animation: true,
	      templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/logoTemplate.html',
	      controller: 'logoController'
	    });
	}

}]);

