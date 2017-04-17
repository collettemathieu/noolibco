// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour contrôler la valeur maximum du paramètre			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  controlMaximumValueParameter
 * @access: public
 * @version: 1
 */
application.directive('controlMaximumValueParameter', function(){
	return{
		require: 'ngModel',
		restrict: 'A',
		link: function(scope, element, attrs, ngModelCtrl){
			ngModelCtrl.$validators.valeurMaxParametre = function(value){
				if(typeof scope.valeurMinParametre != 'undefined'){
					var value = parseInt(value),
						min = parseInt(scope.valeurMinParametre);
					if(value > min){
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

