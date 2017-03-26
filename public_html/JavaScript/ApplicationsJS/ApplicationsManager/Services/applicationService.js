// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Service pour récupérer des informations d'une application.			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Service applicationService
 * @access: public
 * @version: 1
 */

application.factory('applicationService', ['$q', '$http', function($q, $http){
	return{
		getApplication: function(idApplication){
			var deferred = $q.defer();
			$http({
				method: 'POST',
				url: '/HandleApplication/GetApplication',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
			    },
				data: {
					idApp: idApplication
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
		},
		createTree: function(idVersion, idApplication){
			$.ajax({
	            url: '/HandleApplication/DataApplication',
	            type:'POST',
	            data:{
	                idApp:parseInt(idApplication),
	                idVersion:parseInt(idVersion)
	            },
	            async: true,
	            cache: true,
	            success: function(response) {

	                var response = JSON.parse(response);

	                if(response['erreurs'] != 'undefined'){
	                    displayInformationsClient(response);
	                }

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
	                        id: data[i].id,
	                        color: colors[i+5]
	                    });

	                    // add function data

	                    drillDataLen = data[i].drilldown.data.length;
	                    for (j = 0; j < drillDataLen; ++j) {
	                        brightness = 0.2 - (j / drillDataLen) / 5;
	                        fonctionsData.push({
	                            name: data[i].drilldown.fonction[j],
	                            y: data[i].drilldown.data[j],
	                            id: data[i].drilldown.id[j],
	                            tacheId: data[i].drilldown.tacheID,
	                            color: Highcharts.Color(colors[i+5]).brighten(brightness).get()
	                        });


	                        // add parameter data

	                        drillParameterLen = data[i].drilldown.drillparameter[j].data.length;
	                        
	                        for (k = 0; k < drillParameterLen; ++k) {
	                            brightness = 0.1 - (k / drillParameterLen) / 5;
	                            parametersData.push({
	                                name: data[i].drilldown.drillparameter[j].parameter[k],
	                                y: data[i].drilldown.drillparameter[j].data[k],
	                                id: data[i].drilldown.drillparameter[j].id[k],
	                                fonctionId: data[i].drilldown.drillparameter[j].fonctionID,
	                                color: Highcharts.Color(colors[i+5]).brighten(brightness).get()
	                            });
	                        }
	                    }
	                }

	                // Create the chart
	                $('#containerTreeApplication').highcharts({
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
	                            center: ['50%', '50%']
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
	                            formatter: function () {
	                                return this.point.name;
	                            },
	                            color: 'white',
	                            distance: -140
	                        },
	                        point:{
	                            events:{
	                                click: function (event) {

	                                    // Envoi de la requête HTTP en mode asynchrone
	                                    $.ajax({
	                                        url: '/HandleApplication/ModifTache',
	                                        type: 'POST',
	                                        data:{
	                                            idVersion:parseInt(idVersion),
	                                            idApp:parseInt(idApplication),
	                                            idTache:this.id
	                                        },
	                                        async: true,
	                                        cache: true,
	                                        success: function(response) {

	                                            $('#contenuForm').html(response);
	                                            $('#formulaireApplication').modal();
	                                            manageTypeDonneeUtilisateur();
	                                            initTypeDonneeUtilisateur();
	                                            
	                                            // Pour modifier la tâche
	                                            $('#formulaireApplication form:first').on('submit', function(e){
	                                            
	                                                e.preventDefault();
	                                                var formData = new FormData(e.target);
	                                                $(this).find('button').button('loading');
	                                                validerFormulaireApplicationByAjax(formData, '/HandleApplication/ValidModifTache');
	                                            });

	                                            // Pour supprimer la tâche
	                                            $('#formulaireApplication form:last').on('submit', function(e){
	                                                e.preventDefault();
	                                                var formData = new FormData(e.target),
	                                                    btn = $(this).find('button');
	                                                btn.button('loading');
	                                                formData.append('idApp', parseInt(idApplication));
	                                                formData.append('idVersion', parseInt(idVersion));
	                                                // Envoi de la requête HTTP en mode asynchrone
	                                                $.ajax({
	                                                    url: '/HandleApplication/DeleteTache',
	                                                    type: 'POST',
	                                                    data: formData,
	                                                    async: true,
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
	                            distance: -60
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
	                
	            },
	            error: function(){
	                var response = {
	                    'erreurs': '<p>A system error has occurred.</p>'
	                };
	                displayInformationsClient(response);
	            }
	        });
		},
		getPublications: function(idApplication){
			// On importe les publications déjà enregistrées de l'application
			var deferred = $q.defer();
			$http({
				method: 'POST',
				url: '/HandleApplication/GetPublications',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
			    },
				data: {
					idApp: idApplication
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
		},
		deletePublication: function(idPublication, idApplication){
			// On importe les publications déjà enregistrées de l'application
			var deferred = $q.defer();
			$http({
				method: 'POST',
				url: '/HandleApplication/DeletePublication',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				transformRequest: function(obj) {
			        var str = [];
			        for(var p in obj)
			        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
			        return str.join("&");
			    },
				data: {
					idApp: idApplication,
					idPublication: idPublication
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
		}
	};
}]);

