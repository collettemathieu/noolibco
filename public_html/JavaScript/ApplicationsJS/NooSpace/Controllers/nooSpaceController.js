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

  $scope.screenChange().then(function(){
  	if($scope.class=="glyphicon-fullscreen"){
		$scope.class='glyphicon glyphicon-resize-small  fullScreen infoBulleRight';
		$scope.attribut='Normal screen mode';
	}else{
		$scope.class='glyphicon glyphicon-fullscreen fullScreen infoBulleRight';
		$scope.attribut='Full screen mode';
	}
  },function(error){
			var response = {
	             'erreurs': '<p>Failed to enable fullscreen. Your browser is not supported.</p>'
	                    };
	           displayInformationsClient(response);}

	       });	

}]);