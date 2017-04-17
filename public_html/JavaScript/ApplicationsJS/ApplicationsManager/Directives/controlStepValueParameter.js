// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour contrôler le pas du paramètre						  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  controlStepValueParameter
 * @access: public
 * @version: 1
 */
application.directive('controlStepValueParameter', function(){
	return{
		require: 'ngModel',
		restrict: 'A',
		link: function(scope, element, attrs, ngModelCtrl){
			ngModelCtrl.$validators.valeurMaxParametre = function(value){
				var value = parseInt(value),
					min = parseInt(scope.valeurMinParametre),
					max = parseInt(scope.valeurMaxParametre);
				if(value > 0){
					if(typeof scope.valeurMinParametre != 'undefined' && scope.valeurMaxParametre != 'undefined'){	
						if(value < (max - min)){
							return true;
						}else{
							return false;
						}
					}else{
						return true;
					}
				}else{
					return false;
				}
			};
		}
	};
});

