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
	var applicationElement = document.querySelector('#application'),
		idApplication = parseInt(applicationElement.getAttribute('idApplication'));
	
	applicationService.getApplication(idApplication).then(function(response){ // <- c'est une promise
		if(response['erreurs']){
			displayInformationsClient(response);
		}else{
			$scope.application = response;
		}
	}, function(error){
		var response = {
			'erreurs': '<p>A system error has occurred: '+error+'</p>'
		};
		displayInformationsClient(response);
	});

	

	// Action lors de l'ouverture de la fenêtre modale "Logo"
	$scope.logoApplicationModal = function(){
		$uibModal.open({
	      animation: true,
	      templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/logoTemplate.html',
	      controller: 'logoController',
	      scope: $scope
	    });
	}

	// Action lors de l'ouverture de la fenêtre modale "Name"
	$scope.nameApplicationModal = function(){
		$uibModal.open({
	      animation: true,
	      templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/nameTemplate.html',
	      controller: 'nameController',
	      scope: $scope
	    });
	}

}]);

