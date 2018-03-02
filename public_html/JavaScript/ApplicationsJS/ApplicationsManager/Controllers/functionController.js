// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour la fenêtre modale pour les Functions				  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  functionController
 * @access: public
 * @version: 1
 */

application.controller('functionController', ['$scope', '$uibModalInstance', '$http', 'idTache', 'idFunction', 'dataFunction', 'tableLanguages', function($scope, $uibModalInstance, $http, idTache, idFunction, dataFunction, tableLanguages){
	
	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};

	// Initialisation des variables
	$scope.idFunction = idFunction;
	$scope.urlDropZone = '/HandleApplication/ValidModifFonction';
	$scope.textFunction = dataFunction['text'];
	$scope.extFunction = dataFunction['ext'];
	$scope.tableLanguages = tableLanguages;
	$scope.language = dataFunction['lang']+' '+dataFunction['versionLang'];

	// On s'abonne à l'évènement de la dropZone
	$scope.$on('dropEnded', function(evt, value){
		if(value){
			// Evènement de l'arbre des applications
			$scope.$emit('treeHasChanged', false);
		}
	});

	// Pour supprimer la fonction
	$scope.formValidDeleteFunction = function(e){
		$scope.displayButtonDelete = true;
		var formData = new FormData(e.target);
		
		$http({
            url: '/HandleApplication/DeleteFonction',
            method: 'POST',
            headers: {'Content-Type': undefined},
            transformRequest: angular.identity,
            data: formData
        })
		.success(function(response){
			
			displayInformationsClient(response);
			// Position par defaut du bouton envoyer
			$scope.displayButtonDelete = false;
			// Fermer la fenêtre modale
			$uibModalInstance.dismiss('cancel');
			// Evènement de l'arbre des applications
			$scope.$emit('treeHasChanged', false);
		})
		.error(function(error){
			var response = {
				'erreurs': '<p>A system error has occurred: '+error+'</p>'
			};
			displayInformationsClient(response);
			// Position par defaut du bouton envoyer
			$scope.displayButtonDelete = false;
			// Fermer la fenêtre modale
			$uibModalInstance.dismiss('cancel');
		});
	}

	// Pour soumettre le formulaire
	$scope.validTextFunction = function(e){
			
		$scope.displayButtonForm = true;
		$http({
           url: '/HandleApplication/ValidModifFonction',
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			transformRequest: function(obj) {
		        var str = [];
		        for(var p in obj)
		        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
		        return str.join("&");
		    },
			data: {
				idApp: $scope.application.id,
				textFunction: $scope.textEditor,
				idFunction: $scope.idFunction,
				idVersion: $scope.idVersion
			}
        })
		.success(function(response){
			
			displayInformationsClient(response);
			// Position par defaut du bouton envoyer
			$scope.displayButtonForm = false;
			// Evènement de l'arbre des applications
			$scope.$emit('treeHasChanged', false);
		})
		.error(function(error){
			var response = {
				'erreurs': '<p>A system error has occurred: '+error+'</p>'
			};
			displayInformationsClient(response);
			// Position par defaut du bouton envoyer
			$scope.displayButtonForm = false;
		})
	}

	// Pour sauvegarder le formulaire par combinaison des touches
	var body = document.querySelector('body');
    body.onkeydown = function (e) {
        if((e.metaKey || e.ctrlKey) && e.keyCode == 'S'.charCodeAt(0)){
			e.preventDefault();
        	$scope.validTextFunction();
        }
    }

}]);

