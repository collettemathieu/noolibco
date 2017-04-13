// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Application ApplicationsManager									  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Application ApplicationsManager
 * @access: public
 * @version: 1
 */



application.factory('muleService',['dataService', function(dataService){
	return{
		
           // Pour ajouter une nouvelle tâche ou la supprimer de la mule
           manageTaskMule :function(nomFirstTache, listTypeDonnee){
           	// Pour ajouter une nouvelle tâche
            $('#formMule').find('button:first').click(function(e){
                e.preventDefault();
                var divNode = $(this).parent().find('.task:last'),
                    num_data = $(this).parent().find('.task').length,
                    divClone = divNode.clone();
                // On met à jour le nom de la tâche
                divClone.find('select').attr('name', 'tache'+num_data);
                // On insert la nouvelle tâche
                divClone.insertAfter(divNode);
                if(num_data == 1){
                	$('#formMule').find('button:odd').removeAttr('disabled');
                }
                
                dataService.initMule(nomFirstTache, listTypeDonnee);
                // Pour contrôler le changement des select
												$('#formMule').find('.listeTache').change(function(){
													dataService.initMule($(this).val(), listTypeDonnee, $(this).parent().next());
														});
            });

            // Pour supprimer un type de paramètre à la tâche - le dernier n'est pas supprimé
            $('#formMule').find('button:odd').click(function(e){
                e.preventDefault();
                var divNode = $(this).parent().find('.task');
                if(divNode.length > 1){
                    divNode[divNode.length-1].remove();
                    if(divNode.length == 2){
                    	$(this).attr('disabled', 'disabled');
                    }
                }
            });
           },
           initDragAndDropMule : function(){
           	$('#formMule').find('.dashedBorder').droppable({
												drop: function(event, ui){
															if($(this).is(':empty')){
															var donneeUtilisateur = ui.draggable.clone();
						
																$(this).append(donneeUtilisateur);
																$(this).next().next().attr('value','noolibData_'+donneeUtilisateur.attr('id'));
						
																donneeUtilisateur.contextMenu({
					    							selector: 'img',
					        			cloneallback: function(key, options) {
					           		 if(key === 'delete'){
					            			$(this).parent().parent().next().attr('value', '');
					            			$(this).parent().remove();
					            	}
					       			 },
					       	 autoHide: true,
					        	items: {
					            "delete": {
					            	name: "Unload the mule", 
					            	icon: "delete"
					            }
					        }
					    			});
												}else{
												var response = {
						 						 'erreurs': '<p>Unload the mule firstly.</p>'
													};
															displayInformationsClient(response);
													}
													}
													});
           },
           // Pour lancer l'application et gérer les résultats de retour
           runTheMule : function(formData, cloneApplication){
           			// On lance la requête ajax
      		$.ajax({
				url: '/HandleApplication/RunTheMule',
				type: 'POST',
				async: true,
				cache: false,
				data: formData,
				contentType: false,
				processData: false,
				success: function(response) {
					
					//console.log(response);
					// Pour réinitialiser le message d'attente du bouton
					$('#formMule').find('button:last').button('reset');

					// On cache le loader
					cloneApplication.children('.ajaxLoaderApplication').css('visibility', 'hidden').css('display', 'none');

					// On cache les résultats précédents
					cloneApplication.find('.resultBox img').hide(600);

					// On efface le rapport précédent
					cloneApplication.find('.applicationReports').empty();

					try{
						response = JSON.parse(response);
					}
					catch(e){
						var response = {
						  'erreurs': '<p>A system error has occurred while running the application.</p>'
						};
						displayInformationsClient(response);
					}

					if(response['resultat']){
						var reponse = {
							reussites:'<p>A new application report has been released.</p>',
							erreurs: response['erreurs']
						}
						displayInformationsClient(reponse);
						
						// On affiche le rapport
						cloneApplication.find('.resultBox img').show(600);

						// On récupère le template de rapports et on créé les tableaux de résultats
						var templateItemReportApplication = $('#templateItemReportApplication'),
							tabImage = [],
							tabTable = [],
							tabComments = [],
							tabTableOfResults = [];

						for(var i=0,c=response['resultat'].length; i<c ; ++i){

							try{
								var tableauReponse = JSON.parse(response['resultat'][i]);
							}
							catch(e){
								var response = {
								  'erreurs': '<p>Fatal system error: '+response['resultat'][i]+'.</p>'
								};
								displayInformationsClient(response);
							}
							// On créé un nouveau rapport de résultat
							var	reportClone = templateItemReportApplication.clone();
							reportClone.removeAttr('id');
							reportClone.removeClass('hidden');
							if(i==0){reportClone.addClass('active');}
							
							if(tableauReponse['image']){

								var image = new Image(),
								imageResult = reportClone.find('.imageResult');
								image.src = 'data:image/jpeg;base64,'+tableauReponse['image'];
								imageResult.append(image);

								// On enregistre la donnée image
								tabImage.push({
									ext: 'jpeg',
									name: 'image_noolib',
									rawData: tableauReponse['image'],
									data: 'data:image/jpeg;base64,'+tableauReponse['image'],
									sample: 1,
									min: 1,
									size: 1
								});
							}else{
								reportClone.find('.imageResult').html('No picture generated.');
							}

							if(tableauReponse['table']){

								// Création de l'objet TxtReader à partir des données
					            var txtReader = new TXTFile(),
					            	num_points_display = 15000;
					            if(typeof(tableauReponse['table']['legend']) !== 'undefined' && typeof(tableauReponse['table']['data']) !== 'undefined' && typeof(tableauReponse['table']['sampleRate']) !== 'undefined'){
						            txtReader.construct_from_data(tableauReponse['table']['legend'], tableauReponse['table']['data'], num_points_display, tableauReponse['table']['sampleRate']);
						            
						            // Création de la table
						            tableData(txtReader, reportClone);

						            // On enregistre la donnée table
						            tabTable.push({
										ext: 'csv',
										name: 'table_noolib',
										rawData: tableauReponse['table']['data'],
										legend: tableauReponse['table']['legend'],
										data: txtReader,
										sample: tableauReponse['table']['sampleRate'],
										min: 1,
										size: 1
									});
						        }else{
						        	var reponse = {
										erreurs: '<p>Legend, array of data or sample rate is missing for displaying the data.</p>'
									}
									displayInformationsClient(reponse);
						        }

							}else{
								reportClone.find('.tableResult').html('No table generated.');
								reportClone.find('.graphResult').html('No graph generated.');
							}

							if(tableauReponse['comments']){
								reportClone.find('.commentairesResult').html(tableauReponse['comments']);
							}else{
								reportClone.find('.commentairesResult').html('No comment generated.');
							}

							if(tableauReponse['tableOfResults']){
								
								var table = reportClone.find('.tableOfResults');

								for(var id in tableauReponse['tableOfResults']){
									table.append('<table class="table table-nonfluid table-bordered table-striped table-condensed"><thead><tr></tr></thead><tbody><tr></tr></tbody></table>');
								    var headTable = table.find('thead:last tr'),
								        bodyTable = table.find('tbody:last tr');

									headTable.append('<th>'+id+'</th>');
									bodyTable.append('<td>'+tableauReponse['tableOfResults'][id]+'</td>');
								}
							}else{
								reportClone.find('.tableOfResults').html('No result generated.');
							}

							// On insert le nouveau rapport d'activité dans la box de résultats
            				reportClone.appendTo(cloneApplication.find('.applicationReports'));
            				
						}

            		
            			// On gère l'affichage par la fenêtre modale
            			cloneApplication.find('.resultBox img').click(function(){
            				
            				// On affiche la fenêtre
            				$('#resultReportApplication').modal();
            				
            				//On efface les rapports dans le carrousel des autres application s'il y a
            				$('#carouselApplicationReport').find('.item').remove();
            				$('#carouselApplicationReport').find('.carousel-indicators').empty();
            				
        					// On insert les résultats de l'application
            				$('#carouselApplicationReport').find('.carousel-inner').prepend(cloneApplication.find('.applicationReports').html());
            				
            				// On gère la numérotation des onglets
            				$('#carouselApplicationReport').find('.item').each(function(index){
            					$(this).find('.tab-pane').each(function(){
            						$(this).attr('id', $(this).attr('id')+index);
            					});
            					$(this).find('a').each(function(){
            						$(this).attr('href', $(this).attr('href')+index);
            					});
            					
            					if(index == 0){
            						var carouselIndicators = '<li data-target="#carouselApplicationReport" data-slide-to="'+index+'" class="active">';
            					}else{
            						var carouselIndicators = '<li data-target="#carouselApplicationReport" data-slide-to="'+index+'">';
            					}
            					$('#carouselApplicationReport').find('.carousel-indicators').append(carouselIndicators);
		
            				});


							// On retarde légèrement le traitement des données afin de permettre à la fenêtre modale de s'ouvrir
            				setTimeout(function(){
            					// Traitement des données du caroussel
	            				if(tabImage.length != 0){
		            				$('#carouselApplicationReport').find('.imageResult').each(function(index, e){
			            				$(e).click(function(){
			            					
			            					var blob = base64toBlob(tabImage[index]['rawData'], 'image/png'),
												nombre = Math.floor(Math.random()*1000+1),
												fileName = 'Picture_generated_by_NooLib_'+nombre+'.png';
											saveAs(blob, fileName);
			            				});
			            			});
	            				}
		            			if(tabTable.length != 0){
		            				$('#carouselApplicationReport').find('.tableResult').each(function(index, e){
		            					
										// Création du graphe
								        graphLocalData(tabTable[index]['data'], 15000, $(e).parent());

		            					$(e).click(function(){

			            					var stringCSV = tableToCSV(tabTable[index]['legend'], tabTable[index]['rawData']),
			            						blob =  new Blob([stringCSV], {type: "text/csv;charset=utf-8"}),
												nombre = Math.floor(Math.random()*1000+1),
												fileName = 'Table_generated_by_NooLib_'+nombre+'.csv';
											saveAs(blob, fileName);
			            				});
		            				});
		            			}
            				},400);
            				
							
            			});

						// On gère la sauvegarde des résultats
						// Tous les résultats sont sauvegardés en une seule fois avec pls appels Ajax
						$('#formSaveResult').on('submit', function(e) {

							e.preventDefault();
							// Variable d'autorisation d'upload
     	 					var uploadAllowed = true;

						  try{
						  	
						  	for(var i=0, c=tabImage.length; i<c; ++i){
							  	
							  	var result = tabImage[i];

							  	$('#extensionDataResult').attr('value', result.ext);
							  	$('#nomDataResult').attr('value', result.name);
							  	$('#dataResult').attr('value', result.data);
								$('#sampleRateDataResult').attr('value',result.sample);
								$('#tailleDataResult').attr('value', result.size);
								$('#tempsMinimumDataResult').attr('value', result.min);
								
							  	
							    if(uploadAllowed){
							      var formData = new FormData(e.target);


							       // On affiche l'indicateur de progression
									  $('#image-result-waiter').show();

									  // On empêche de charger ou d'uploader une nouvelle donnée
									  $('#labelSubmitSaveResult').attr('for', '');
									  $('#labelSubmitSaveResult img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');
							      

							      // Envoi de la requête HTTP en mode asynchrone
							      $.ajax({
							          url: '/HandleData/AddLocalData',
							          type: 'POST',
							          data: formData,
							          async: true,
							          cache: false,
							          contentType: false,
							          processData: false,
							          success: function(response) {
							            try{
							              var response = JSON.parse(response);
							            }
							            catch(e){
							              var response = {
							                'erreurs': '<p>A system error has occurred: '+e+'</p>'
							              };

							              displayInformationsClient(response);
							            }

							            if(response['reussites']){
							               
							                displayInformationsClient(response);
							                // On actualise les données
							                $('#inListeDonneesUser').html(response['listeDonneeUtilisateur']);
							                // On met à jour l'affichage du gestionnaire de données
							                var parametres = {
							                    'tailleMoDonneesUtilisateur': response['tailleMoDonneesUtilisateur'],
							                    'tailleMaxDonneesUtilisateur': response['tailleMaxDonneesUtilisateur'],
							                    'progressionPourcent': response['progressionPourcent']
							                };

							                showData(parametres);

							            }else if(response['erreurs']){
							                displayInformationsClient(response);
							            }

							            // On ferme l'indicateur de progression
										$('#image-result-waiter').hide();
										// On permet d'uploader la donnée
										$('#labelSubmitSaveResult').attr('for', 'submitSaveResult');
										$('#labelSubmitSaveResult img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Load all results in your data manager');
							          },
							          error: function(xhr, ajaxOptions, thrownError){
							            var response = {
							                'erreurs': '<p>The size of data exceeds the limit authorized.</p><p>Please try again with another data.</p>'
							            };
							            displayInformationsClient(response);

							            // On ferme l'indicateur de progression
										$('#image-result-waiter').hide();
										// On permet d'uploader la donnée
										$('#labelSubmitSaveResult').attr('for', 'submitSaveResult');
										$('#labelSubmitSaveResult img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Load all results in your data manager');
							          }
							      });
							    }else{

							      var response = {
							          'erreurs': '<p>The size of data exceeds the limit authorized.</p><p>Please try again with an another data/image or a smaller interval.</p>'
							      };

							      displayInformationsClient(response);
							    }
							}
						  }
						  catch(e){
						      var response = {
						          'erreurs': '<p>A system error has occurred: '+e+'</p>'
						      };

						      displayInformationsClient(response);

						  }
						});
						
					}else{
						if(response['erreurs']){
							displayInformationsClient(response);
						}else{
							var response = {
							  'erreurs': '<p>A system error has occurred while running the application.</p>'
							};
							displayInformationsClient(response);
						}
					}	
				
				},
				error: function(){
					
					// Pour réinitialiser le message d'attente
					$('#formMule').find('button:last').button('reset');
					
					// On affiche un message d'erreur
					var response = {
					  'erreurs': '<p>A system error has occurred. Please drag and drop a valid data into the data box of the application.</p>'
					};
					displayInformationsClient(response);

					// On cache le loader
					cloneApplication.children('.ajaxLoaderApplication').css('visibility', 'hidden').css('display', 'none');

					// On cache les résultats précédents
					cloneApplication.find('.resultBox img').hide(600);

					// On efface le rapport précédent
					cloneApplication.find('.applicationReports').html('');

					
				}
			});
  }

	}
}]);  

