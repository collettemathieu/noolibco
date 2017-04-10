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
			// Initialisation des variables
			$scope.application = response;
			$scope.idVersion = response.versions[response.versions.length-1].id;
			$scope.numVersion = response.versions[response.versions.length-1].numero;
			$scope.noteVersion = response.versions[response.versions.length-1].note;
			applicationService.getTree($scope.idVersion, response.id).then(function(newValue){
				$scope.tree = newValue;
			});
		}
	}, function(error){
		var response = {
			'erreurs': '<p>A system error has occurred: '+error+'</p>'
		};
		displayInformationsClient(response);
	});

	// Pour créer une nouvelle version
	$scope.createVersionModal = function(){
		var modal = $uibModal.open({
	      animation: true,
	      templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/versionTemplate.html',
	      controller: 'versionController',
	      scope: $scope
	    });

		// On met à jour les variables lorsque la fenêtre se ferme
	    modal.result.then(function(e){
	    }, function(){
	    	// Mise à jour des variables
			$scope.idVersion = $scope.application.versions[$scope.application.versions.length-1].id;
			$scope.numVersion = $scope.application.versions[$scope.application.versions.length-1].numero;
			$scope.noteVersion = $scope.application.versions[$scope.application.versions.length-1].note;
			applicationService.getTree($scope.idVersion, $scope.application.id).then(function(newValue){
				$scope.tree = newValue;
			});
	    });
	}

	// Pour créer une nouvelle tâche
	$scope.createTaskModal = function(){
		var modal = $uibModal.open({
	      animation: true,
	      templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/newTaskTemplate.html',
	      controller: 'newTaskController',
	      scope: $scope,
	      size: 'lg',
	      resolve: {
	        // On récupère les types des données pour le select
			typeData: ['$http', '$q', function($http, $q){
				var deferred = $q.defer(); // -> promise
				$http({
					method: 'POST',
					url: '/HandleApplication/GetTypeData'
				})
				.success(function(response){
					deferred.resolve(response);
				})
				.error(function(error){
					var response = {
						'erreurs': '<p>A system error has occurred: '+error+'</p>'
					};
					displayInformationsClient(response);
				});

				return deferred.promise;
			}
	      ]}
	    });

		// On met à jour l'arbre de l'application et l'application lorsque la fenêtre se ferme
	    modal.result.then(function(e){
	    }, function(){
			applicationService.getApplication(idApplication).then(function(response){ // <- c'est une promise
				if(response['erreurs']){
					displayInformationsClient(response);
				}else{
					// Initialisation des variables
					$scope.application = response;
					applicationService.getTree($scope.idVersion, $scope.application.id).then(function(newValue){
						$scope.tree = newValue;
					});
				}
			}, function(error){
				var response = {
					'erreurs': '<p>A system error has occurred: '+error+'</p>'
				};
				displayInformationsClient(response);
			});
	    });
	}

	// Pour supprimer définitivement l'application
	$scope.deleteApplicationModal = function(){
		$uibModal.open({
	      animation: true,
	      templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/deleteTemplate.html',
	      controller: 'deleteController',
	      scope: $scope
	    });
	}

	// Pour gérér les publications
	$scope.publicationsModal = function(){
		$uibModal.open({
	      animation: true,
	      templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/publicationsTemplate.html',
	      controller: 'managePublicationsController',
	      scope: $scope,
	      resolve: {
	        // On récupère les types des publications pour le select
			typePublications: ['$http', '$q', function($http, $q){
				var deferred = $q.defer(); // -> promise
				$http({
					method: 'POST',
					url: '/HandleApplication/GetTypePublications'
				})
				.success(function(response){
					deferred.resolve(response);
				})
				.error(function(error){
					var response = {
						'erreurs': '<p>A system error has occurred: '+error+'</p>'
					};
					displayInformationsClient(response);
				});

				return deferred.promise;
			}
	      ]}
	    });
	}

	// Action lors de l'ouverture de la fenêtre modale "Logo"
	$scope.logoApplicationModal = function(){
		$uibModal.open({
	      animation: true,
	      templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/logoTemplate.html',
	      controller: 'logoController',
	      scope: $scope,
	      size: 'lg'
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

	// Action lors de l'ouverture de la fenêtre modale "Description"
	$scope.descriptionApplicationModal = function(){
		$uibModal.open({
	      animation: true,
	      templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/descriptionTemplate.html',
	      controller: 'descriptionController',
	      scope: $scope,
	      resolve: {
				// On récupère les catégories pour l'affichage dans le select
				tableOfCategories: ['$http', '$q', function($http, $q){
					
					// On récupère l'ensemble des catégories
					var deferred = $q.defer(); // -> promise
					$http({
						method: 'POST',
						url: '/SubmitAnApplication/GetAllCategories'
					})
					.success(function(response){
						deferred.resolve(response);
					})
					.error(function(error){
						var response = {
							'erreurs': '<p>A system error has occurred: '+error+'</p>'
						};
						displayInformationsClient(response);
					});

					return deferred.promise;
				}
			]}
	    });
	}

	// Action lors de l'ouverture de la fenêtre modale "Authors"
	$scope.authorsApplicationModal = function(){
		$uibModal.open({
	      animation: true,
	      templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/authorsTemplate.html',
	      controller: 'authorsController',
	      scope: $scope,
	      size: 'lg'
	    });
	}
}]);

