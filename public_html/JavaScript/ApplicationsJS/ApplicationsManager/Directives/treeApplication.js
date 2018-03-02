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
					        colors.push(Highcharts.Color(base).brighten((i-5) / 9).get());
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
		                            	$uibModal.open({
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
		                        y: 10
		                    },
		                    point:{
		                        events:{
		                            click: function (event) {
		                                if(this.id != -1){

		                                	var idTache = this.tacheId,
		                                		idFunction = this.id;
			                            	$uibModal.open({
												animation: true,
												templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/functionTemplate.html',
												controller: 'functionController',
												scope: scope,
												size: 'lg',
												resolve: {
													idTache: function(){
														return idTache;
													},
													idFunction: function(){
														return idFunction;
													},
													// On récupère les types des données pour le select
													dataFunction: ['$http', '$q', function($http, $q){
														var deferred = $q.defer(); // -> promise
														$http({
															method: 'POST',
															url: '/HandleApplication/GetTextFunction',
															headers: {'Content-Type': 'application/x-www-form-urlencoded'},
															transformRequest: function(obj) {
														        var str = [];
														        for(var p in obj)
														        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
														        return str.join("&");
														    },
															data: {
																idFunction: idFunction,
																idApp: scope.application.id,
																idVersion: scope.idVersion
															}
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
													// On récupère les langages de programmation
													tableLanguages: ['$http','$q',function($http,$q){
														var deferred = $q.defer(); // On créé une promise
														$http({
															url:'/HandleApplication/GetLanguages',
															method:'POST',
															headers:{'Content-Type': 'application/x-www-form-urlencoded'}
														})
														.success(function(response){
															deferred.resolve(response);
														})
														.error(function(error){
															var response = {
																'erreurs': '<p>A system error has occurred: '+error+'</p>'
															}
															displayInformationsClient(response);
														});
														return deferred.promise;
													}]
												}
										    });
		                                }else{
		                                	var idTache = this.tacheId;
			                            	$uibModal.open({
												animation: true,
												templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/newFunctionTemplate.html',
												controller: 'newFunctionController',
												scope: scope,
												size: 'lg',
												resolve: {
													idTache: function(){
														return idTache;
													},
													// On récupère les langages de programmation
													tableLanguages: ['$http', '$q', function($http, $q){
														var deferred = $q.defer(); // On créé une promise
														$http({
															method: 'POST',
															url:'/HandleApplication/GetLanguages',
															headers: {'Content-Type': 'application/x-www-form-urlencoded'},	
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
													}]
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
		                                	var idParameter = this.id;

		                                	$uibModal.open({
												animation: true,
												templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/parameterTemplate.html',
												controller: 'parameterController',
												scope: scope,
												size: 'lg',
												resolve: {
													// On récupère les types des paramètres pour le select
													typesParameter: ['$http', '$q', function($http, $q){

														var deferred = $q.defer(); // -> promise
														$http({
															method: 'POST',
															url: '/HandleApplication/GetTypeParameter'
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
													idParameter: function(){
														return idParameter;
													}
												}
										    });
		                                }else{
		                                	var idFunction = this.fonctionId;

		                                	if(idFunction != -1){
				                            	$uibModal.open({
													animation: true,
													templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/newParameterTemplate.html',
													controller: 'newParameterController',
													scope: scope,
													size: 'lg',
													resolve: {
														// On récupère les types des paramètres pour le select
														typesParameter: ['$http', '$q', function($http, $q){

															var deferred = $q.defer(); // -> promise
															$http({
																method: 'POST',
																url: '/HandleApplication/GetTypeParameter'
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
														idFonction: function(){
															return idFunction;
														}
													}
											    });
				                            }else{
				                            	var response = {
	                                                'erreurs': '<p>You must first add a function before adding a parameter.</p>'
	                                            };
	                                            displayInformationsClient(response);
				                            }
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

