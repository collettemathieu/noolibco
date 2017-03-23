// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Controleur pour ajouter une publication 							  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  controllerModalAddPublication
 * @access: public
 * @version: 1
 */

application.controller('controllerModalAddPublication', ['applicationService', '$http', '$scope', '$rootScope', '$uibModalInstance', 'dataStep3', function(applicationService, $http, $scope, $rootScope, $uibModalInstance, dataStep3){
	
	// On ajoute les types des publications à la balise select
	$scope.types = dataStep3['typePublication'];
	$scope.selectedType = $scope.types[0];

	// On ajoute l'id de l'application à l'input hidden
	$scope.idApplication = dataStep3['idApp'];
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
                    $scope.selectedType = $scope.types[1];
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
	
	// Pour fermer la fenêtre modale
	$scope.close = function(){
		 $uibModalInstance.dismiss('cancel');
	};
}]);

