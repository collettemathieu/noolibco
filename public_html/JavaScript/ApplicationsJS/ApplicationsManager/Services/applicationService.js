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
		getApplication: function(idApplication){
			var deferred = $q.defer();
			$http({
				method: 'POST',
				url: '/HandleApplication/GetApplication',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
			    },
				data: {
					idApp: idApplication
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
