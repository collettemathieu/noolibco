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
 * @name: overlayGestionnaireDonnesDirective
 * @access: public
 * @version: 1
 */
application.directive('btnAnimation',function($animate){
 	return {
 		link: function(scope,element,attrs){
 				/*if(attrs.btnAnimation){
 					$animate.animate(element,{
 						'left':'400px'
 					}),1500);
 				}
 				else{
 					$animate.animate(element,{
 						'left':'500px'
 					}),1500);

 				}*/
 				//element.addClass("hello");
 			
 			
 		};
 	}
 });