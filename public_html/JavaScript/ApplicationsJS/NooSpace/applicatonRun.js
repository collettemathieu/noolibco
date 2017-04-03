// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Application ApplicationsManager									  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Application NooSpace
 * @access: public
 * @version: 1
 */

var application = angular.module('NooSpace', []);

/* Configuration */
application.config(['$locationProvider', '$httpProvider', '$compileProvider', function($locationProvider, $httpProvider, $compileProvider){
	$locationProvider.html5Mode(true); // Activation du mode HTML 5 pour l'url des routes
	$httpProvider.defaults.cache = true; // Activation du cache pour toutes les requêtes HTTP de type GET
	$httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'; // Activation du header Ajax
	$compileProvider.debugInfoEnabled(false); // Désactivation des informations de debug afin de gagner en performance

}]);

