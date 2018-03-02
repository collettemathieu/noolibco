// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour la fenêtre modale New Function						  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  newFunctionController
 * @access: public
 * @version: 1
 */

application.controller('newFunctionController', ['$scope', '$uibModalInstance', '$http', 'idTache', 'tableLanguages', function($scope, $uibModalInstance, $http, idTache, tableLanguages){
	
	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};

	// Initialisation des variables
	$scope.idTache = idTache;
	$scope.urlDropZone = '/HandleApplication/ValidFormFonction';
	$scope.tableLanguages = tableLanguages;
	$scope.language = '';

	// On s'abonne à l'évènement de la dropZone
	$scope.$on('dropEnded', function(evt, value){
		if(value){
			// Evènement de l'arbre des applications
			$scope.$emit('treeHasChanged', false);
			// On ferme la fenêtre modale
			$uibModalInstance.dismiss('cancel');
		}
	});
}]);

