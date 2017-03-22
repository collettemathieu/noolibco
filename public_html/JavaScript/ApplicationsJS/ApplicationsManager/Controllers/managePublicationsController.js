// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour gérer les publications de l'application    		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  managePublicationsController
 * @access: public
 * @version: 1
 */

application.controller('managePublicationsController', ['applicationService', '$http', '$scope', '$rootScope', '$uibModalInstance', 'typePublications', function(applicationService, $http, $scope, $rootScope, $uibModalInstance, typePublications){
	
    // On récupère les publications de l'application

	applicationService.getPublications($scope.application.id).then(function(response){ // <- c'est une promise
        if(response['erreurs']){
            displayInformationsClient(response);
        }else{
            $scope.application.publications = response;
        }
    }, function(error){
        var response = {
            'erreurs': '<p>A system error has occurred: '+error+'</p>'
        };
        displayInformationsClient(response);
    });

    // On ajoute les types des publications à la balise select
	$scope.types = typePublications['typePublication'];
	$scope.selectedType = $scope.types[1];

    // On initialise le button de recherche par DOI
    $scope.loading = false;
    $scope.loadingAddPublication = false;

	// Pour faire une recherche à partir d'une DOI
	$scope.validDOI = function(){
		
        if($scope.formDOI.$valid){
            var formDOI = document.getElementById('formDOI'),
    			formData = new FormData(formDOI);

            $scope.loading = true;
    		// Envoi de la requête HTTP en mode asynchrone
            $http({
                url: '/HandleApplication/RequestPublication',
                method: 'POST',
                headers: {'Content-Type': undefined},
                data: formData
            })
            .success(function(response) {
                $scope.loading = false;
                if(response['erreurs']){
                	displayInformationsClient(response);
                }else if(response['reussites']){
                	// On change d'onglet
                	$(document.querySelectorAll('.nav-pills a[href="#manuel"]')).tab('show');
                	
                	// On remplit le tableau du formulaire des publications
                	$scope.titrePublication = response['reussites']['titleArticle'];
                	$scope.auteursPublication = response['reussites']['listeAuteurs'];
                	$scope.anneePublication = response['reussites']['yearPublication'];
                	$scope.journalPublication = response['reussites']['titleJournal'];
                	$scope.urlPublication = response['reussites']['urlRessource'];
                }
            })
            .error(function(){
                $scope.loading = false;
                var response = {
                    'erreurs': '<p>A system error has occurred.</p>'
                };
                displayInformationsClient(response);
            });
        }
	};

    // Pour ajouter une nouvelle publication
    $scope.addPublication = function(){
        if($scope.formAddPublication.$valid){
            var addPublicationForm = document.getElementById('addPublicationForm'),
                formData = new FormData(addPublicationForm);

            // On rajoute le type de publication en paramètre
            formData.append('typePublication', $scope.selectedType.nameType);
            
            $scope.loadingAddPublication = true;
            // Envoi de la requête HTTP en mode asynchrone
            $http({
                url: '/HandleApplication/AddPublication',
                method: 'POST',
                headers: {'Content-Type': undefined},
                data: formData
            })
            .success(function(response) {
                $scope.loadingAddPublication = false;
                displayInformationsClient(response);
                // On ferme la fenêtre modale
                $uibModalInstance.dismiss('cancel');
                // On charge les nouvelles publications
                applicationService.getPublications().then(function(publications){
                    $rootScope.$broadcast('listOfPublicationsUpdated', publications);
                }, function(error){
                    displayInformationsClient(error);
                });
            })
            .error(function(){
                $scope.loadingAddPublication = false;
                var response = {
                    'erreurs': '<p>A system error has occurred.</p>'
                };
                displayInformationsClient(response);
            });
        }
    };

    // Action pour supprimer une publication
    $scope.deletePublication = function(idPublication, idApplication){
        $scope.deletingPublication = true;
        applicationService.deletePublication(idPublication, idApplication)
        .then(function(){
            return applicationService.getPublications($scope.application.id);
        })
        .then(function(publications){
            // On met à jour la variable
            $scope.application.publications = publications;
            $scope.deletingPublication = false;
        })
        .catch(function(error){
            var response = {
                'erreurs': '<p>A system error has occurred: '+error+'</p>'
            };
            displayInformationsClient(response);
            $scope.deletingPublication = false;
        });
    };
	
	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};
}]);

