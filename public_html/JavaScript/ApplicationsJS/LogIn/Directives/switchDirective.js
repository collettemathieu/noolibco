// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour contrôler le switch boostrap du formulaire LogIn.	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  switchDirective
 * @access: public
 * @version: 1
 */

application.directive('switchDirective', function(){
	return{
		restrict: 'A',
		link: function(scope, element){
			element.bootstrapSwitch({
	       	'size': 'mini',
	       	'onText': 'Yes',
	       	'offText': 'No'
	       });	
		}
	};
});

