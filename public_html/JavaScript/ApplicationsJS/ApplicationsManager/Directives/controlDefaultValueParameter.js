// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour contrôler la valeur par défaut du paramètre			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  controlDefaultValueParameter
 * @access: public
 * @version: 1
 */
application.directive('controlDefaultValueParameter', function(){
	return{
		require: 'ngModel',
		restrict: 'A',
		link: function(scope, element, attrs, ngModelCtrl){
			ngModelCtrl.$validators.valeurDefautParametre = function(value){
				if(typeof scope.valeurMinParametre != 'undefined' && scope.valeurMaxParametre != 'undefined'){	
					var value = parseInt(value),
						min = parseInt(scope.valeurMinParametre),
						max = parseInt(scope.valeurMaxParametre);
					if(value >= min && value <= max){
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

