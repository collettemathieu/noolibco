// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Application SubmitAnApplication									  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Application SubmitAnApplication
 * @access: public
 * @version: 1
 */

var application = angular.module('applicationSubmitAnApplication', ['ngRoute', 'ui.bootstrap']);

/* Configuration */
application.config(['$locationProvider', '$httpProvider', '$compileProvider', '$routeProvider', function($locationProvider, $httpProvider, $compileProvider, $routeProvider){
	$locationProvider.html5Mode(true); // Activation du mode HTML 5 pour l'url des routes
	$httpProvider.defaults.cache = true; // Activation du cache pour toutes les requêtes HTTP de type GET
	$httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'; // Activation du header Ajax
	$compileProvider.debugInfoEnabled(false); // Désactivation des informations de debug afin de gagner en performance

	// Déclaration des routes
	$routeProvider
	.when('/SubmitAnApplication/:idApp?', {
		templateUrl: '/JavaScript/ApplicationsJS/SubmitAnApplication/Views/formStep1.html',
		controller: 'controllerStep1',
		resolve: {
			// On récupère les catégories pour l'affichage dans le select
			tableOfCategories: ['$http', '$q', '$location', function($http, $q, $location){
				// On contrôle au préalable l'url par une regex et l'étape de soumission de l'application
				/^\/SubmitAnApplication\/([0-9]+)$/.exec($location.url());
				var idApp = parseInt(RegExp.$1);
				if(Number.isInteger(idApp)){
					$http({
						method: 'GET',
						url: '/SubmitAnApplication/GetStatusApplication/idApp='+idApp
					})
					.success(function(response){
						if(response['statusApp']){
							response['statusApp'] = parseInt(response['statusApp']);
							if(response['statusApp'] > 1 && response['statusApp'] < 4){
								$location.url('/SubmitAnApplicationStep'+response['statusApp']+'/');
							}
						}else if(response['erreurs']){
							displayInformationsClient(response);
						}
					})
					.error(function(error){
						displayInformationsClient(error);
					});
				}
				
				// Sinon on affiche l'étape 1
				var deferred = $q.defer(); // -> promise
				$http({
					method: 'POST',
					url: '/SubmitAnApplication/GetAllCategories'
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
	})
	.when('/SubmitAnApplicationStep2/',{
		templateUrl: '/JavaScript/ApplicationsJS/SubmitAnApplication/Views/formStep2.html',
		controller: 'controllerStep2'
	})
	.when('/SubmitAnApplicationStep3/',{
		templateUrl: '/JavaScript/ApplicationsJS/SubmitAnApplication/Views/formStep3.html',
		controller: 'controllerStep3',
		resolve:{
			publicationsApplication: ['$q', '$http', 'applicationService', function($q, $http, applicationService){
				return applicationService.getPublications();
			}
		]}
	});
}]);