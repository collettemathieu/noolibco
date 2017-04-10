// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour créer l'arbre de l'application 						  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  treeApplication
 * @access: public
 * @version: 1
 */

application.directive('treeApplication', ['$uibModal', 'applicationService', function($uibModal, applicationService){
	return{
		restrict: 'A',
		scope: false,
		replace: true,
		link: function(scope, element, attrs){
			scope.$watch('tree', function(response){
				if(typeof response != 'undefined'){
					// Make monochrome colors and set them as default for all pies
					Highcharts.getOptions().plotOptions.pie.colors = (function () {
					    var colors = [],
					        base = Highcharts.getOptions().colors[0],
					        i;

					    for (i = 0; i < 10; i += 1) {
					        // Start out with a darkened base color (negative brighten), and end
					        // up with a much brighter color
					        colors.push(Highcharts.Color(base).brighten((i - 3) / 9).get());
					    }
					    return colors;
					}());

					var task = response['task'],
		                data = response['data'],
		                tasksData = [],
		                fonctionsData = [],
		                parametersData = [],
		                i,
		                j,
		                k,
		                dataLen = data.length,
		                drillDataLen,
		                drillParameterLen,
		                brightness,
		                colors = Highcharts.getOptions().colors;
		                
		            // Build the data arrays
		            for (i = 0; i < dataLen; ++i) {

		                // add task data
		                tasksData.push({
		                    name: task[i],
		                    y: data[i].y,
		                    id: data[i].id
		                });

		                // add function data

		                drillDataLen = data[i].drilldown.data.length;
		                for (j = 0; j < drillDataLen; ++j) {
		                    brightness = 0.2 - (j / drillDataLen) / 5;
		                    fonctionsData.push({
		                        name: data[i].drilldown.fonction[j],
		                        y: data[i].drilldown.data[j],
		                        id: data[i].drilldown.id[j],
		                        tacheId: data[i].drilldown.tacheID
		                    });


		                    // add parameter data

		                    drillParameterLen = data[i].drilldown.drillparameter[j].data.length;
		                    
		                    for (k = 0; k < drillParameterLen; ++k) {
		                        brightness = 0.1 - (k / drillParameterLen) / 5;
		                        parametersData.push({
		                            name: data[i].drilldown.drillparameter[j].parameter[k],
		                            y: data[i].drilldown.drillparameter[j].data[k],
		                            id: data[i].drilldown.drillparameter[j].id[k],
		                            fonctionId: data[i].drilldown.drillparameter[j].fonctionID
		                        });
		                    }
		                }
		            }

		            // Create the chart
		            element.highcharts({
		                chart: {
		                    type: 'pie',
		                    backgroundColor: 'transparent'
		                },
		                credits: {
		                  enabled: false
		                },
		                title:{
		                    text:''
		                },
		                exporting: { 
		                    enabled: false
		                },
		                plotOptions: {
		                    pie: {
		                        shadow: false,
		                        center: ['50%', '50%'],
		                        dataLabels: {
					                enabled: true,
					                style: {
					                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
					                }
					            }
		                    },
		                    series: {
		                        cursor: 'pointer'
		                    }
		                },
		                tooltip: {
		                    shared: true,
		                    useHTML: true,
		                    headerFormat: '<small>{point.key}</small><table>',
		                    pointFormat: '',
		                    footerFormat: '</table>',
		                    valueDecimals: 2
		                },
		                series: [{
		                    name: 'Tasks',
		                    data: tasksData,
		                    size: '100%',
		                    innerSize: '5%',
		                    dataLabels: {
		                    	enabled: true,
		                        formatter: function () {
		                            return 'Task';
		                        },
		                        color: 'white',
		                        distance: -140
		                    },
		                    point:{
		                        events:{
		                            click: function (event) {
		                            	var idTache = this.id;
		                            	var modal = $uibModal.open({
											animation: true,
											templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/taskTemplate.html',
											controller: 'taskController',
											scope: scope,
											size: 'lg',
											resolve: {
												// On récupère les types des données pour le select
												typeData: ['$http', '$q', function($http, $q){

													var deferred = $q.defer(); // -> promise
													$http({
														method: 'POST',
														url: '/HandleApplication/GetTypeData'
													})
													.success(function(response){
														deferred.resolve(response);
													})
													.error(function(error){
														var response = {
															'erreurs': '<p>A system error has occurred: '+error+'</p>'
														};
														displayInformationsClient(response);
													});

													return deferred.promise;
												}],
												idTache: function(){
													return idTache;
												}
											}
									    });

									    // On met à jour l'arbre de l'application et l'application lorsque la fenêtre se ferme
									    modal.result.then(function(e){
									    }, function(){
											if(treeHasChanged){
												treeHasChanged = false;
												applicationService.getApplication(scope.application.id).then(function(response){ // <- c'est une promise
													if(response['erreurs']){
														displayInformationsClient(response);
													}else{
														// Initialisation des variables
														scope.application = response;
														applicationService.getTree(scope.idVersion, scope.application.id).then(function(newValue){
															scope.tree = newValue;
														});
													}
												}, function(error){
													var response = {
														'erreurs': '<p>A system error has occurred: '+error+'</p>'
													};
													displayInformationsClient(response);
												});
											}
									    });
		                            }
		                        }
		                    } 
		                }, {
		                    name: 'Functions',
		                    data: fonctionsData,
		                    size: '100%',
		                    innerSize: '40%',
		                    dataLabels: {
		                        formatter: function () {
		                            // display only if larger than 1
		                            return this.point.name;
		                        },
		                        color: 'white',
		                        distance: -60,
		                        y: 30
		                    },
		                    point:{
		                        events:{
		                            click: function (event) {
		                                if(this.id != -1){
		                                    // Envoi de la requête HTTP en mode asynchrone
		                                    $.ajax({
		                                        url: '/HandleApplication/ModifFonction',
		                                        type: 'POST',
		                                        data:{
		                                            idVersion:parseInt(idVersion),
		                                            idApp:parseInt(idApplication),
		                                            idFonction:this.id
		                                        },
		                                        async: true,
		                                        cache: true,
		                                        success: function(response) {
		                                            $('#contenuForm').html(response);
		                                            $('#formulaireApplication').modal();

		                                            // Pour modifier la fonction
		                                            addFunctionToTask('/HandleApplication/ValidModifFonction');

		                                            // Pour supprimer la fonction
		                                            $('#formulaireApplication form:last').on('submit', function(e){
		                                                e.preventDefault();
		                                                var formData = new FormData(e.target),
		                                                    btn = $(this).find('button');
		                                                btn.button('loading');
		                                                formData.append('idApp', parseInt(idApplication));
		                                                formData.append('idVersion', parseInt(idVersion));
		                                                // Envoi de la requête HTTP en mode asynchrone
		                                                $.ajax({
		                                                    url: '/HandleApplication/DeleteFonction',
		                                                    type: 'POST',
		                                                    data: formData,
		                                                    cache: false,
		                                                    contentType: false,
		                                                    processData: false,
		                                                    success: function(response) {
		                                                        btn.button('reset');
		                                                        var response = JSON.parse(response);
		                                                        
		                                                        if(response['reussites']){
		                                                            setTimeout(function(){
		                                                                    location.reload();
		                                                            }, 1000);
		                                                        }

		                                                        displayInformationsClient(response);
		                                                    },
		                                                    error: function(){
		                                                        btn.button('reset');
		                                                        var response = {
		                                                            'erreurs': '<p>A system error has occurred.</p>'
		                                                        };
		                                                        displayInformationsClient(response);
		                                                    }
		                                                });
		                                            });
		                                                
		                                        },
		                                        error: function(){
		                                            var response = {
		                                                'erreurs': '<p>A system error has occurred.</p>'
		                                            };
		                                            displayInformationsClient(response);
		                                        }
		                                    });
		                                }else{
		                                   
		                                    // Envoi de la requête HTTP en mode asynchrone
		                                    $.ajax({
		                                        url: '/HandleApplication/FormFonction',
		                                        type: 'POST',
		                                        data:{
		                                            idVersion:parseInt(idVersion),
		                                            idApp:parseInt(idApplication),
		                                            idTache:this.tacheId
		                                        },
		                                        async: true,
		                                        cache: true,
		                                        success: function(response) {
		                                            $('#contenuForm').html(response);
		                                            $('#formulaireApplication').modal();

		                                            addFunctionToTask('/HandleApplication/ValidFormFonction');
		                                        },
		                                        error: function(){
		                                            var response = {
		                                                'erreurs': '<p>A system error has occurred.</p>'
		                                            };
		                                            displayInformationsClient(response);
		                                        }
		                                    });
		                                }
		                            }
		                        }
		                    } 
		                },
		                {
		                    name: 'Parameters',
		                    data: parametersData,
		                    size: '100%',
		                    innerSize: '100%',
		                    dataLabels: {
		                        formatter: function () {
		                            return this.point.name;
		                        },
		                    },
		                    point:{
		                        events:{
		                            click: function (event) {
		                                if(this.id != -1){
		                                    // Envoi de la requête HTTP en mode asynchrone
		                                    $.ajax({
		                                        url: '/HandleApplication/ModifParametre',
		                                        type: 'POST',
		                                        data:{
		                                            idVersion:parseInt(idVersion),
		                                            idApp:parseInt(idApplication),
		                                            idParametre:this.id
		                                        },
		                                        async: true,
		                                        cache: true,
		                                        success: function(response) {
		                                            $('#contenuForm').html(response);
		                                            $('#formulaireApplication').modal();
		                                            
		                                            // Pour modifier le paramètre
		                                            $('#formulaireApplication form:first').on('submit', function(e){
		                                            
		                                                e.preventDefault();
		                                                var formData = new FormData(e.target),
		                                                    btn = $(this).find('button');
		                                                btn.button('loading');
		                                                validerFormulaireApplicationByAjax(formData, '/HandleApplication/ValidModifParametre');
		                                                
		                                            });

		                                            // Pour supprimer le paramètre
		                                            $('#formulaireApplication form:last').on('submit', function(e){
		                                                e.preventDefault();
		                                                var formData = new FormData(e.target),
		                                                    btn = $(this).find('button');
		                                                btn.button('loading');
		                                                formData.append('idApp', parseInt(idApplication));
		                                                formData.append('idVersion', parseInt(idVersion));
		                                                
		                                                // Envoi de la requête HTTP en mode asynchrone
		                                                $.ajax({
		                                                    url: '/HandleApplication/DeleteParametre',
		                                                    type: 'POST',
		                                                    data: formData,
		                                                    cache: false,
		                                                    contentType: false,
		                                                    processData: false,
		                                                    success: function(response) {
		                                                        btn.button('reset');
		                                                        var response = JSON.parse(response);
		                                                        
		                                                        if(response['reussites']){
		                                                            setTimeout(function(){
		                                                                    location.reload();
		                                                            }, 1000);
		                                                        }

		                                                        displayInformationsClient(response);
		                                                    },
		                                                    error: function(){
		                                                        btn.button('reset');
		                                                        var response = {
		                                                            'erreurs': '<p>A system error has occurred.</p>'
		                                                        };
		                                                        displayInformationsClient(response);
		                                                    }
		                                                });
		                                            });

		                                        },
		                                        error: function(){
		                                            var response = {
		                                                'erreurs': '<p>A system error has occurred.</p>'
		                                            };
		                                            displayInformationsClient(response);
		                                        }
		                                    });
		                                }else{
		                                    
		                                    // Envoi de la requête HTTP en mode asynchrone
		                                    $.ajax({
		                                        url: '/HandleApplication/FormParametre',
		                                        type: 'POST',
		                                        data:{
		                                            idVersion:parseInt(idVersion),
		                                            idApp:parseInt(idApplication),
		                                            idFonction:this.fonctionId
		                                        },
		                                        async: true,
		                                        cache: true,
		                                        success: function(response) {
		                                            
		                                            $('#contenuForm').html(response);
		                                            $('#formulaireApplication').modal();

		                                            $('#formulaireApplication form').on('submit', function(e){
		                                            
		                                                e.preventDefault();
		                                                var formData = new FormData(e.target),
		                                                    btn = $(this).find('button');
		                                                btn.button('loading');
		                                                validerFormulaireApplicationByAjax(formData, '/HandleApplication/ValidFormParametre');
		                                                
		                                            });
		                                            
		                                        },
		                                        error: function(){
		                                            var response = {
		                                                'erreurs': '<p>A system error has occurred.</p>'
		                                            };
		                                            displayInformationsClient(response);
		                                        }
		                                    });
		                                }
		                            }
		                        }
		                    } 
		                }]
		            });
				}
			});
		}
	};
}]);

