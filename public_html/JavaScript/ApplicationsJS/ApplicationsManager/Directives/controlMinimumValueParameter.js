// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour contrôler la valeur minimum du paramètre			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  controlMinimumValueParameter
 * @access: public
 * @version: 1
 */
application.directive('controlMinimumValueParameter', function(){
	return{
		require: 'ngModel',
		restrict: 'A',
		link: function(scope, element, attrs, ngModelCtrl){
			ngModelCtrl.$validators.valeurMinParametre = function(value){	
				if(typeof scope.valeurMaxParametre != 'undefined'){
					if(value < scope.valeurMaxParametre){
						return true;
					}else{
						return false;
					}
				}else{
					return true;
				}
			};
		}
	};
});

