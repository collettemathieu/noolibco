// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour la page d'accueil /LogIn/							  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  logInController
 * @access: public
 * @version: 1
 */

application.controller('logInController', ['$scope', '$http', '$window', '$uibModal', function($scope, $http, $window, $uibModal){
	// Pour accéder à la page de récupération du mot de passe
	$scope.forgottenPassword = function(){
		$window.location.href = '/LogIn/RecupPassword/';
	}

	// Action lors de l'ouverture de la fenêtre modale "About"
	$scope.aboutModal = function(){
		$uibModal.open({
	      animation: true,
	      templateUrl: '/JavaScript/ApplicationsJS/LogIn/Directives/Templates/aboutTemplate.html',
	      controller: 'aboutController'
	    });
	}

	// Action lors de l'ouverture de la fenêtre modale "Contact"
	$scope.contactModal = function(){
		$uibModal.open({
	      animation: true,
	      templateUrl: '/JavaScript/ApplicationsJS/LogIn/Directives/Templates/contactTemplate.html',
	      controller: 'contactController'
	    });
	}

	// Action lors de l'ouverture de la fenêtre modale "RegistrationForm"
	$scope.registrationFormModal = function(){
		$uibModal.open({
	      animation: true,
	      templateUrl: '/JavaScript/ApplicationsJS/LogIn/Directives/Templates/registrationFormTemplate.html',
	      controller: 'registrationController',
	      resolve: {
	        // On récupère les status des utilisateurs
			allStatus: ['$http', '$q', function($http, $q){
				var deferred = $q.defer(); // -> promise
				$http({
					method: 'POST',
					url: '/LogIn/GetStatut'
				})
				.success(function(response){
					deferred.resolve(response);
				})
				.error(function(error){
					displayInformationsClient(error);
				});

				return deferred.promise;
			}
	      ]}
	    });
	}
}]);

