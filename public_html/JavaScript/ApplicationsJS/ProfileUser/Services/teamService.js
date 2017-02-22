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
			// On importe les publications déjà enregistrées de l'application
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
		}
	};
}]);

