// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour sélectionner une version d'application				  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  selectVersion
 * @access: public
 * @version: 1
 */

application.directive('selectVersion', ['applicationService', function(applicationService){
	return{
		restrict: 'A',
		scope: false,
		replace: true,
		priority: 1,
		templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/selectVersionTemplate.html',
		link: function(scope, element, attrs){
			// Pour mettre à jour les variables et l'arbre de l'application
			scope.changeVersion = function(version){
				scope.numVersion = version.numero;
				scope.idVersion = version.id;
				scope.noteVersion = version.note;
				applicationService.getTree(scope.idVersion, scope.application.id).then(function(response){
					scope.tree = response;
				});
			};
		}
	};
}]);

