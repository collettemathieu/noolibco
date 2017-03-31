// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour la fenêtre modale Logo								  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  mainController
 * @access: public
 * @version: 1
 */


application.controller('nooSpaceController', ['$scope', '$uibModalInstance', '$http', function($scope, $uibModalInstance, $http){

  $scope.screenChange = function(){
  	if($scope.class=="glyphicon-fullscreen"){
		$scope.class="glyphicon-resize-small";
		$scope.attribut="Normal screen mode";
	}else{
		$scope.class="glyphicon-fullscreen";
		$scope.attribut="Full screen mode";
	}
	       }

}]);