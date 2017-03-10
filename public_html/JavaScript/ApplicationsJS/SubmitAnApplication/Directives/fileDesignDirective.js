// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour améliorer le design du input file.					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  fileDesignDirective
 * @access: public
 * @version: 1
 */

application.directive('fileDesignDirective', function(){
	return{
		restrict: 'A',
		require: 'ngModel',
		priority: 1001,
		link: function(scope, element){
			element.filestyle({
				iconName: 'glyphicon glyphicon-download-alt'
			});	
		}
	};
});

