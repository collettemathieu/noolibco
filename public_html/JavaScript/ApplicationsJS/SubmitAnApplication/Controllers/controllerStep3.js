// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour Step3 de l'application SubmitAnApplication			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  controllerStep3
 * @access: public
 * @version: 1
 */

application.controller('controllerStep3', ['applicationService', 'publicationsApplication', '$uibModal', '$scope', '$http', '$window', function(applicationService, publicationsApplication, $uibModal, $scope, $http, $window){
	
	// Initialisation
	$scope.validDeposit = false;
	$scope.deletingPublication = new Array();
    for(var i=0, c=publicationsApplication.length; i<c; ++i){
        $scope.deletingPublication[i] = false;
    }

	// On met à jour les publications de l'application
	$scope.publicationsApplication = publicationsApplication;

	// On s'abonne à la mise à jour des publications de l'application (venant de la fenêtre modale)
	$scope.$on('listOfPublicationsUpdated', function(evt, publications){
		$scope.publicationsApplication = publications;
	});

	// Action lors de l'ouverture de la fenêtre modale d'ajout d'une publication
	$scope.openAddPublication = function(){
		$uibModal.open({
	      animation: true,
	      templateUrl: '/JavaScript/ApplicationsJS/SubmitAnApplication/Directives/Templates/modalAddPublication.html',
	      controller: 'controllerModalAddPublication',
	      size: 'md',
	      resolve: {
	        // On récupère les types des publications pour le select
			dataStep3: ['$http', '$q', function($http, $q){
				var deferred = $q.defer(); // -> promise
				$http({
					method: 'POST',
					url: '/SubmitAnApplication/GetDataStep3'
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
	};


	// Action pour supprimer une publication
	$scope.deletePublication = function(idPublication, idApplication, index){
		$scope.deletingPublication[index] = true;
		applicationService.deletePublication(idPublication, idApplication)
		.then(function(){
			return applicationService.getPublications();
		})
		.then(function(publications){
			// On met à jour la variable
			$scope.publicationsApplication = publications;
			$scope.deletingPublication[index] = false;
		})
		.catch(function(error){
			var response = {
				'erreurs': '<p>A system error has occurred: '+error+'</p>'
			};
			displayInformationsClient(response);
			$scope.deletingPublication[index] = false;
		});
	};


	// Action lors de la soumission du formulaire
	$scope.validStep3 = function(){
		if($scope.formStep3.$valid){
			$scope.validDeposit = true;
			$http({
				method: 'POST',
				url: '/SubmitAnApplication/DepositApplication'
			})
			.success(function(response){
				if(response['idApp']){
					// On récupère l'id de l'application
					var idApplication = parseInt(response['idApp']);
					// On rédirige vers l'arbre de l'application
					$window.location.href = '/ManagerOfApplications/app='+idApplication;
				}else{
					displayInformationsClient(response);
				}
			})
			.error(function(error){
				var response = {
					'erreurs': '<p>A system error has occurred: '+error+'</p>'
				};
				displayInformationsClient(response);
				$scope.validDeposit = false;
			});
		}
	};
	
}]);

