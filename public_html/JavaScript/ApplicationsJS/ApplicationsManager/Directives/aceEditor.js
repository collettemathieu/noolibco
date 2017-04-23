// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour créer un éditeur de texte pour les fonctions		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  addData
 * @access: public
 * @version: 1
 */
application.directive('aceEditor', ['$compile', function($compile){
	return{
		restrict: 'A',
		link: function(scope, element, attrs){
			//console.log(element);
			var editor = ace.edit(element[0]);
			editor.setValue(scope.textFunction);
			editor.setTheme('ace/theme/twilight');

		}
	};
}]);

