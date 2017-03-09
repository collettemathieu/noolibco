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

application.controller('mainController', ['$scope', '$http', '$window', '$uibModal', 'applicationService', function($scope, $http, $window, $uibModal, applicationService){
	
	// Récupération des éléments de l'application
	var applicationElement = document.querySelector('#application');
	console.log(parseInt(applicationElement.getAttribute('idApplication')));
	//var application = applicationService->getApplication();

	// Action lors de l'ouverture de la fenêtre modale "Logo"
	$scope.logoApplicationModal = function(){
		$uibModal.open({
	      animation: true,
	      templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/logoTemplate.html',
	      controller: 'logoController'
	    });
	}

}]);

