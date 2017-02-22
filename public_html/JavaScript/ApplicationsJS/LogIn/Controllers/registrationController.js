// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour la fenêtre modale Registration Form.				  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  registrationController
 * @access: public
 * @version: 1
 */

application.controller('registrationController', ['$scope', '$http', '$uibModalInstance', 'allStatus', function($scope, $http, $uibModalInstance, allStatus){
	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};

	// Pour afficher les différents types de statut des utilisateurs dans le select du formulaire
	if(allStatus['allStatus']){
		$scope.statuts = allStatus['allStatus'];
	}else if(allStatus['erreurs']){
		displayInformationsClient(allStatus['erreurs']);
	}

	// Pour contrôler l'envoi du formulaire d'inscription
	$scope.loadingSignUp = false;
	$scope.submitRegistrationForm = function(){
		if($scope.registrationForm.$valid){
			$scope.loadingSignUp = true;
			$http({
				method: 'POST',
				url: '/LogIn/ValiderInscription',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
			    },
				data: {
					prenom: $scope.prenom,
					nom: $scope.nom,
					adresseMail: $scope.adresseMail,
					statutUtilisateur: $scope.statutUtilisateur.nameType,
					motDePasseFormulaire: $scope.motDePasseFormulaire,
					motDePasseConfirme: $scope.motDePasseConfirme
				}
			})
			.success(function(response){
                $scope.loadingSignUp = false;
                displayInformationsClient(response);

				// On indique à l'utilisateur que son inscription s'est bien passée.
                var content = document.getElementById('content');
                content.innerHTML = '<h1>Congratulations.</h1><h2>Your registration has been successful submitted.</h2><h2> Please confirm your e-mail of registration in order to activate your account.</h2>';
                
                // On ferme la fenêtre
                $uibModalInstance.dismiss('cancel');
			})
			.error(function(error){
				$scope.loadingSignUp = false;
				var response = {
            		'erreurs': '<p>A system error has occured.</p>'
            	};
	            displayInformationsClient(response);
	            // On ferme la fenêtre
                $uibModalInstance.dismiss('cancel');
			});
		}
	}
}]);

