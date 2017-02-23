// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Service pour récupérer des informations d'une institution, 		  |
// | d'un laboratoire ou d'une équipe.									  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Service teamService
 * @access: public
 * @version: 1
 */

application.factory('teamService', ['$q', '$http', function($q, $http){
	return{
		getInstitutions: function(){
			var deferred = $q.defer();
			$http({
				method: 'POST',
				url: '/Profile/GetInstitutions'
			})
			.success(function(response){
				deferred.resolve(response);
			})
			.error(function(error){
				displayInformationsClient(error);
			});

			return deferred.promise;
		},
		getLaboratories: function(idInstitution){
			var deferred = $q.defer();
			$http({
				method: 'POST',
				url: '/Profile/GetLaboratories',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
			    },
				data: {
					idEtablissement: idInstitution
				}
			})
			.success(function(response){
				deferred.resolve(response);
			})
			.error(function(error){
				displayInformationsClient(error);
			});

			return deferred.promise;
		},
		getTeams: function(idLaboratory){
			var deferred = $q.defer();
			$http({
				method: 'POST',
				url: '/Profile/GetTeams',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
			    },
				data: {
					idLaboratoire: idLaboratory
				}
			})
			.success(function(response){
				deferred.resolve(response);
			})
			.error(function(error){
				displayInformationsClient(error);
			});

			return deferred.promise;
		}
	};
}]);

