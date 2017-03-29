// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour améliorer le design du select.						  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  selectDesignDirective
 * @access: public
 * @version: 1
 */

application.directive('selectDesignDirective', function(){
	return{
		restrict: 'A',
		require: 'ngModel',
		priority: 10001,
		link: function(scope, element){
			scope.$watch('selectedType', function(){
				element.selectpicker('refresh');
			});
		}
	};
});
