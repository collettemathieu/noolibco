// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour contrôler l'existance du nom de l'application. 		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  nameApplicationValidator
 * @access: public
 * @version: 1
 */

application.directive('nameApplicationValidator', ['$q', '$http', 'invalidNameTooShort', 'invalidNameApplication', function($q, $http, invalidNameTooShort, invalidNameApplication){
	return{
		restrict: 'A',
		require: 'ngModel',
		link: function(scope, element, attrs, ngModelCtrl){
			ngModelCtrl.$asyncValidators.nomApp = function(nomApplicationEntered){
				
				var deferred = $q.defer();
				
				$http({
					method: 'POST',
					url: '/SubmitAnApplication/ValidNameApplication',
					headers: {'Content-Type': 'application/x-www-form-urlencoded'},
					transformRequest: function(obj) {
				        var str = [];
				        for(var p in obj)
				        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
				        return str.join("&");
				    },
					data: {
						nomApp: nomApplicationEntered
					}
				})
				.success(function(response){
					if(response['reussites']){
						deferred.resolve();
						scope.infoInvalidNameApplication = invalidNameTooShort;
					}else{
						deferred.reject();
						scope.infoInvalidNameApplication = invalidNameApplication;
					}
				})
				.error(function(error){
					displayInformationsClient(error);
					deferred.reject();
				});

				return deferred.promise;
			}	
		}
	};
}]);

