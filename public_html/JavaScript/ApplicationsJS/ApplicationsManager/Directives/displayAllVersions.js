// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour afficher toutes les versions de l'application		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  displayAllVersions
 * @access: public
 * @version: 1
 */
application.directive('displayAllVersions', function(){
	return{
		restrict: 'A',
		priority: 1,
		link: function(scope, element, attrs){
			element.on('click', function(e){
				$(this).next('ul').toggle();
				e.stopPropagation();
				e.preventDefault();
			});
		}
	};
});

