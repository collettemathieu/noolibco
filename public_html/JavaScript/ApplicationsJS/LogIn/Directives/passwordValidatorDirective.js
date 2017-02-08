// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour contrôler que les deux mots de passe sont identiques  |
// | du formulaire LogIn.	  											  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  passwordValidatorDirective
 * @access: public
 * @version: 1
 */

application.directive('passwordValidatorDirective', function(){
	return{
		restrict: 'A',
		require: 'ngModel',
		link: function(scope, element, attrs, ngModelCtrl){
			ngModelCtrl.$validators.motDePasseConfirme = function(value){
				if(value == scope.motDePasseFormulaire){
					return true;
				}else{
					return false;
				}
			}	
		}
	};
});

