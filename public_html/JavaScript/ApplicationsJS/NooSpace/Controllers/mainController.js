// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour l'arbre des applications							  |
// +----------------------------------------------------------------------+
// | Auteur : Naoures HASSINE
// +----------------------------------------------------------------------+

/**
 * @name:  mainController
 * @access: public
 * @version: 1
 */

application.controller('mainController', ['$scope', '$http', '$window', '$uibModal', 'Fullscreen', function($scope, $http, $window, $uibModal, Fullscreen){
	
	//Gestion full screen
	$scope.isInFullscreen=false;
	$scope.attribut="Full screen mode";
	
	$scope.goFullscreen = function(){
		if(Fullscreen.isEnabled())
		{
			$scope.attribut="Full screen mode";
			$scope.isInFullscreen=false;
			Fullscreen.cancel();
		}
		else{
			$scope.attribut="Normal screen mode";
			$scope.isInFullscreen=true;
			Fullscreen.all();
		}	
	};
	$scope.changeScreen = function($eventNew){
		if($eventNew.which===27 )
			{
				console.log("esc clicked!");
			$scope.attribut="Full screen mode";
			$scope.isInFullscreen=false;
			Fullscreen.cancel();
		}
	};


//Gestionnaire de donnée
	$scope.isOverlay=false;	
	$scope.overlayGestionnaireDonnees = function(){
		$scope.isOverlay=!$scope.isOverlay;
	};
}]);

