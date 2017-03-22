// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Service pour récupérer des informations d'une application.			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Service applicationService
 * @access: public
 * @version: 1
 */

application.factory('applicationService', ['$q', '$http', function($q, $http){
	return{
		getPublications: function(){
			// On importe les publications déjà enregistrées de l'application
			var deferred = $q.defer();
			$http({
				method: 'POST',
				url: '/SubmitAnApplication/GetPublicationsApplication'
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
		},
		deletePublication: function(idPublication, idApplication){
			// On importe les publications déjà enregistrées de l'application
			var deferred = $q.defer();
			$http({
				method: 'POST',
				url: '/HandleApplication/DeletePublication',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
			    },
				data: {
					idApp: idApplication,
					idPublication: idPublication
				}
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
	};
}]);

