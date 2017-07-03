$(function(){
	//test **********************
 
 		$.ajax({
				url: '/NooSpace/getSession',
				type: 'POST',
				async: true,
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					response=JSON.parse(response);
					sessionStorage.setItem("id",response['id']);
					sessionStorage.setItem("isAdmin",response['isAdmin']);
					//console.log(sessionStorage);
				},
				error: function(){
					console.log('erreur4');
					
				}
			});

	
	//***************************
	if($('#noospace').length !=0){

		// Pour gérer l'affichage du gestionnaire de données
		var overlayGestionnaireDonnees = $('#overlayGestionnaireDonnees'),
			decalageInitiale = -parseInt(overlayGestionnaireDonnees.css('width'));
		dataManagerAlreadyOpened = false // Pour connaître le statut du gestionnaire de données
		addDataAlreadyLoaded = false; // Pour éviter un envoi multiple des formulaires lors de l'ajout de données
		overlayGestionnaireDonnees.css('display', 'inline-block').css('left', decalageInitiale+'px');
		$('#boutonShowGestionnaireDonnees').on('click', openGestionnaireDonnees);
		// Pour gérer l'affichage de la mule
		$('#laMule').hide();


		// On contrôle s'il y a une application déjà présente, dans ce cas on la déploie
		if($('#noospace .runIt').length !=0){
			var widthNoospace = parseInt($('#noospace').css('width'));
			$('#noospace').css('width', widthNoospace-50+'px'); // Bidouille pour éviter un agrandissement de la noospace
			
			var appRunIt = $('#noospace .runIt');
			deployApplication(appRunIt, $('#noospace'));
			appRunIt.remove();
		}

		// Sinon pour toutes les applications déplacées sur la noospace
		$('#noospace').droppable({
		    drop: function(event, ui){
		    	var pos = $(this).offset();
		    	var positionSourisX = event.clientX,
			      	positionSourisY = event.clientY,
			      	positionElement = ui.draggable.offset(),
			      	positionSourisInAppX = positionSourisX - positionElement.left,
			      	positionSourisInAppY = positionSourisY - positionElement.top,
			      	nouvellePositionElementX = positionSourisX - pos.left - positionSourisInAppX,
			      	nouvellePositionElementY = positionSourisY - pos.top - positionSourisInAppY,
			      	largeurGestionnaireDonnee = parseInt($('#overlayGestionnaireDonnees').css('width'));

		      	// Pour insérer une application dans la noospace
		      	if(ui.draggable.parent().attr('id') === 'applicationsInDock' || ui.draggable.hasClass('runIt')){

		      		deployApplication(ui.draggable, $(this), nouvellePositionElementX, nouvellePositionElementY);

				// Pour insérer une nouvelle donnée dans la noospace
			    }else if(ui.draggable.parent().attr('id') === 'inListeDonneesUser' && positionSourisX > largeurGestionnaireDonnee){

			    	// Pour n'insérer que les nouvelles données qui ne sont pas positionées dans une dataBox, car c'est le droppable de l'application qui s'en charge
			    	var isIn = false;
			    	$('#noospace .dataBox').each(function(index){
			      		var p = $(this).offset();
						if(positionSourisX >= p.left && positionSourisX <= p.left +64 && positionSourisY >= p.top && positionSourisY <= p.top + 64){
							isIn = true;
						}
					});

			    	if(!isIn){
			    		initDonneeUtilisateur(ui.draggable.clone(), $(this), positionSourisX - pos.left -34, positionSourisY - pos.top -34);
			    	}
					
			    // Pour retirer une donnée d'une dataBox de l'application
			    }else if(ui.draggable.parent().hasClass('dataBox ui-droppable')){
			    	
			    	$(this).append(ui.draggable);
					ui.draggable.css('position','absolute').css('top', nouvellePositionElementY+'px').css('left', nouvellePositionElementX+'px');
			    	
			    // Pour ajouter une donnée locale à la noospace
			    }else if(ui.draggable.hasClass('newTempData')){
			    	ui.draggable.removeClass('newTempData');

			    	initDonneeUtilisateur(ui.draggable, $(this), nouvellePositionElementX, nouvellePositionElementY);
			    }

		    }
		});

		// Pour déployer une application dans la NooSpace
		function deployApplication(app, element, nouvellePositionElementX, nouvellePositionElementY){
			if(app.hasClass('runIt')){
      			app.removeClass('runIt');
      		}
      		if(typeof nouvellePositionElementX == 'undefined' || typeof nouvellePositionElementY == 'undefined'){
      			var largeurNooSpace = parseInt($('#noospace').width()),
      				hauteurNooSpace = parseInt($('#noospace').height());
      			nouvellePositionElementX = parseInt(largeurNooSpace/2);
      			nouvellePositionElementY = parseInt(hauteurNooSpace/3);
      		}
      		var cloneApplication = app.clone();
      		cloneApplication.appendTo(element);

      		cloneApplication.css('width','240px').css('position','absolute').css('top', nouvellePositionElementY+'px').css('left', nouvellePositionElementX-93+'px'); // 93 pour contrer l'ajout de width:240px

	      	cloneApplication.draggable({
				revert: false,
				containment: '#noospace',
				start: function(){
					$(this).children('img').addClass('noClick');
				}
	      	});

	      	// On réajuste la taille de l'image de l'application
	      	cloneApplication.find('.imageApplication').animate({
	      		'width':64,
	      		'height': 64
	      	}, 1500);

	      	// On affiche les boîtes de dialogue supplémentaires autour de l'application
	      	cloneApplication.children('hr').show().css('display', 'inline-block');
			setTimeout(function(){
				cloneApplication.children('.dataBox').show('slice').css('display', 'inline-block');
  				setTimeout(function(){
  					cloneApplication.children('.resultBox').show('slice').css('display', 'inline-block');
  				}, 500);
  			}, 200);
  			
  			
	      	// Pour receuillir les données dans la dataBox de l'application
	      	cloneApplication.children('.dataBox').droppable({
	      		drop: function(event, ui){
	      			var positionSourisX = event.clientX,
	      			largeurGestionnaireDonnee = parseInt($('#overlayGestionnaireDonnees').css('width'));

	      			if(ui.draggable.parent().attr('id') === 'inListeDonneesUser' && positionSourisX > largeurGestionnaireDonnee){

	      				initDonneeUtilisateur(ui.draggable.clone(), $(this), 2, 2);

	      			}else{
	      				$(this).append(ui.draggable);
			      		ui.draggable.css('position','absolute').css('top', 2+'px').css('left', 2+'px');
	      			}
	      		}
	      	});

	      	
			// Menu contextuel de l'application
			$.contextMenu.types.Tache = function(item, opt, root) {

		        $(cloneApplication.children('.tachesApplication').html())
		            .appendTo(this)
		            .on('click', 'li', function() {
		               	
		            	try{
			               	// On affiche le loader
				      		cloneApplication.children('.ajaxLoaderApplication').css('visibility', 'visible').css('display', 'block');
				      		
				      		// On ajoute les données et les paramètres pour le lancement de l'application
				      		var form = paramForm = cloneApplication.find('.parametresApplication form').serializeArray(),
				      			formData = new FormData();

							formData.append('tache0data0', 'noolibData_'+cloneApplication.find('.donneeUser').attr('id'));
							formData.append('idApplication', cloneApplication.attr('id'));
							formData.append('idVersion', cloneApplication.attr('idVersion'));
							formData.append('tache0', $(this).attr('id'));
							// On ajoute le formulaire des paramètres au formulaire général
							for (var i=0; i<paramForm.length; i++)
							    formData.append(paramForm[i].name, paramForm[i].value);

							runTheMule(formData, cloneApplication);
						}
			      		catch(e){
			      			var response = {
							  'erreurs': '<p>A system error has occurred.</p>'
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

		            this.removeClass('context-menu-item').addClass('context-menu-tache');     
		    };


		   	// Pour définir le menu contextuel de l'application
		    cloneApplication.contextMenu({
		    	selector: '.imageApplication',
		        callback: function(key, options) {
		            if(key === 'delete'){
		            	$(this).parent().remove();
		            }
		            if(key === 'mule'){

		            	// On récupère la mule de l'application
		            	// On ajoute l'id de l'application
			      		var formData = new FormData();
						formData.append('idApplication', cloneApplication.attr('id'));
						
			      		$.ajax({
							url: '/HandleApplication/ShowMule',
							type: 'POST',
							async: true,
							cache: false,
							data: formData,
							contentType: false,
							processData: false,
							success: function(response) {
								try{
									response = JSON.parse(response);
									
									// Pour afficher/cacher la mule
						      		var tailleManager = parseInt($('#overlayGestionnaireDonnees').css('width')),
						      			positionLeftBouton = parseInt($('#boutonShowGestionnaireDonnees').css('left')),
						      			timeFadeIn = 1200;

						      		// On réinitialise la mule
						      		var saveTask = $('#formMule').find('.task:first');
						      		saveTask.find('.form-group').empty();
						      		$('#formMule').find('.task').remove();
						      		$('#formMule').find('button').remove();
						      		saveTask.prependTo($('#formMule'));
						      		
									if(response['erreurs']){
										displayInformationsClient(response);
										if(tailleManager > 500){
											$('#overlayGestionnaireDonnees').animate({'width': tailleManager-400}, 1500);
								      		$('#boutonShowGestionnaireDonnees').animate({'left':positionLeftBouton-400}, 1500);
								      		$('#laMule').hide();
								      	}
									}else{
							      		if(tailleManager > 500){
								      		$('#overlayGestionnaireDonnees').animate({'width': tailleManager-400}, 1000).animate({'width': tailleManager}, 1500);
								      		$('#boutonShowGestionnaireDonnees').animate({'left':positionLeftBouton-400}, 1000).animate({'left':positionLeftBouton}, 1500);

								      		$('#laMule').hide();
								      		timeFadeIn = timeFadeIn+1000;
								      	}else{
								      		$('#overlayGestionnaireDonnees').animate({'width': tailleManager+400}, 1500);
								      		$('#boutonShowGestionnaireDonnees').animate({'left':positionLeftBouton+400}, 1500);
								      		if(!dataManagerAlreadyOpened){
									      		setTimeout(function(){
									      			openGestionnaireDonnees();
									      		}, timeFadeIn+300);
									      	}
								      	}
								      	// Pour afficher la mule avec un temps de retard
								      	setTimeout(function(){
								      		$('#laMule').fadeIn();
								      	}, timeFadeIn);

										// Pour charger le nom de l'application
										$('#laMule').find('.titreGestionnaire').html('Mule of '+response['nomApplication']);

										// Pour charger le formulaire
										var listTypeDonnee = response['listeTypeDonnee'],
											nomFirstTache = response['listeTypeDonnee'][0]['nomTache'];

										$('#formMule').find('.form-group:first').append(response['listeTache']);

										initMule(nomFirstTache, listTypeDonnee);

										// On ajoute les boutons
										$('<button type="submit" data-loading-text="<span class=\'glyphicon glyphicon-refresh spinning\'></span> Running..." class="btn btn-default pull-right">Go forward</button>').insertAfter('.task:last');
										$('<button class="btn btn-default pull-left" disabled>&ndash;</button>').insertAfter('.task:last');
										$('<button class="btn btn-default pull-left">+</button>').insertAfter('.task:last');

										// Pour contrôler le changement des select
										$('#formMule').find('.listeTache').change(function(){
											initMule($(this).val(), listTypeDonnee, $(this).parent().next());
										});

								      	// Pour gérer les formulaires de la mule
							      		manageTaskMule(nomFirstTache, listTypeDonnee);


								      	$('#formMule').find('button:last').click(function(e){
								      		e.preventDefault();
								      		// Pour afficher un message d'attente
								      		$(this).button('loading');
								      		// On récupère la mule de l'application
							            	// On ajoute l'id de l'application
								      		var form = document.querySelector('#formMule'),
								      			paramForm = cloneApplication.find('.parametresApplication form').serializeArray(),
								      			formData = new FormData(form);
								      		// On ajoute le formulaire des paramètres au formulaire général
											for (var i=0; i<paramForm.length; i++)
											    formData.append(paramForm[i].name, paramForm[i].value);

											formData.append('idApplication', cloneApplication.attr('id'));
											formData.append('idVersion', cloneApplication.attr('idVersion'));
								      		runTheMule(formData, cloneApplication);

								      	});
									    
									}
								}
								catch(e){
									var response = {
									  'erreurs': '<p>A system error has occurred.</p>'
									};
									displayInformationsClient(response);
									// Pour afficher/cacher la mule
						      		var tailleManager = parseInt($('#overlayGestionnaireDonnees').css('width')),
						      			positionLeftBouton = parseInt($('#boutonShowGestionnaireDonnees').css('left'));
									if(tailleManager > 500){
										$('#overlayGestionnaireDonnees').animate({'width': tailleManager-400}, 1500);
							      		$('#boutonShowGestionnaireDonnees').animate({'left':positionLeftBouton-400}, 1500);
							      		$('#laMule').hide();
							      		$('#formMule').find('.form-group').empty(); // On efface les données précédentes
							      	}
								}
							},
							error: function(){
								var response = {
								  'erreurs': '<p>A system error has occurred.</p>'
								};
								displayInformationsClient(response);
							}
						});

		            }
					if(key === 'parametreApplication') {
						console.log("from key parametre Application");
						// On copie les paramètres de l'application dans la fenêtre modale
						var panelSettingsApplication = $('#panelSettingsApplication'),
							contenuPanel = $(this).parent().find('.parametresApplication'),
							modalBody = panelSettingsApplication.find('.modal-body'),
							cloneContenuPanel = contenuPanel.clone();

						modalBody.html(cloneContenuPanel.html());
						panelSettingsApplication.modal('show');
						sliderParametreApplication(modalBody);

						// Pour la sauvegarde des paramètres dans l'application, on copie
						// la fenêtre modale dans les paramètres de l'application
						modalBody.find('button').click(function(e){
							e.preventDefault();
							contenuPanel.html(modalBody.html());
							panelSettingsApplication.modal('hide');
						});
					}
					
		        },
		        autoHide: true,
		        items: {
		            "fold1": {
		                "name": "Tasks", 
		                "items": {
		                    Tache: {
				            	type: "Tache", 
				            	customName: "tache"
				            },
		                }
		            },
		             "parametreApplication": {
		            	name: "Set this application"
		            },
		            "mule":{
		            	name: "Load/Unload the mule"
		            },
		            "sep1": "---------",
		            "delete": {
		            	name: "Remove from the NooSpace", 
		            	icon: "delete"
		            }
		        }
		    });
		}


		// Pour initialiser les fonctionnalitées de la donnée utilisateur une fois ajouter à la NooSpace
		function initDonneeUtilisateur(donneeUtilisateur, drop, nouvellePositionElementX, nouvellePositionElementY){

			donneeUtilisateur.children('img').on({
				click: function(){
					// Pour éviter le click durant le drag
					if($(this).hasClass('noClick')){
						$(this).removeClass('noClick');
					}else{
						forShowingData(donneeUtilisateur);
					}
					
				}
			});
	    	donneeUtilisateur.appendTo(drop);
	  		donneeUtilisateur.css('position','absolute').css('top', nouvellePositionElementY+'px').css('left', nouvellePositionElementX+'px');

			// On affiche les info-bulles
			donneeUtilisateur.popover({placement:'bottom', trigger:'hover'});

	  		donneeUtilisateur.contextMenu({
		    	selector: 'img',
		        callback: function(key, options) {
		            if(key === 'delete'){
		            	$(this).parent().remove();
		            }
		        },
		        autoHide: true,
		        items: {
		            "delete": {
		            	name: "Remove from the NooSpace", 
		            	icon: "delete"
		            }
		        }
		    });
	 
	      	donneeUtilisateur.draggable({
				revert: false,
				containment: '#noospace',
				snap: '.dataBox',
				start: function(){
					$(this).children('img').addClass('noClick');
				},
				drag: function(event, ui){
					$(this).popover('hide');
				}
	      	});
		}

		// Pour gérer le dropable des données dans la mule
		function initDragAndDropMule(){

			$('#formMule').find('.dashedBorder').droppable({
				drop: function(event, ui){
					if($(this).is(':empty')){
						var donneeUtilisateur = ui.draggable.clone();
						
						$(this).append(donneeUtilisateur);
						$(this).next().next().attr('value','noolibData_'+donneeUtilisateur.attr('id'));
						
						donneeUtilisateur.contextMenu({
					    	selector: 'img',
					        callback: function(key, options) {
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
		}


		// Pour lancer l'application et gérer les résultats de retour
		function runTheMule(formData, cloneApplication){
			
			// On lance la requête ajax
			formData.append('id',sessionStorage['id']);
			formData.append('isAdmin',sessionStorage['isAdmin']);
      		$.ajax({
				url:  '/HandleApplication/RunTheMule',//'http://'+window.location.hostname+':3000/runTheMule/',
				type: 'POST',
				async: true,
				cache: false,
				data: formData,
				contentType: false,
				processData: false,
				success: function(response) {
					
					console.log(response);
					// Pour réinitialiser le message d'attente du bouton
					$('#formMule').find('button:last').button('reset');

					// On cache le loader
					cloneApplication.children('.ajaxLoaderApplication').css('visibility', 'hidden').css('display', 'none');

					// On cache les résultats précédents
					cloneApplication.find('.resultBox img').hide(600);

					// On efface le rapport précédent
					cloneApplication.find('.applicationReports').empty();

					try{
						console.log("parse response");
					    JSON.parse(response);
					}
					catch(e){
						console.log('erreur de parse');
						var response = {
						  'erreurs': '<p>A system error has occurred while running the application.</p>'
						};
						displayInformationsClient(response);
					}
					//**************
					console.log("response['resultat']");
					console.log(response['resultat']);
					//**********
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
							var tableauReponse = response['resultat'][i];
							try{
								var tableauReponse = JSON.parse(response['resultat'][i]);
								console.log(tableauReponse);
								
							}
							catch(e){
								console.log("erreur parse tableau response");
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
								//**************
								console.log("tableauReponse['table']");
								console.log(tableauReponse['table']);
								//**********	
							if(tableauReponse['table']){
								
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
							            	console.log("here 4");
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
						  	console.log("here 5");
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
							console.log("here 12");
							var response = {
							  'erreurs': '<p>A system error has occurred while running the application.</p>'
							};
							displayInformationsClient(response);
						}
					}	
				
				},
				error: function(){
					$('#formMule').find('button:last').button('reset');
					cloneApplication.children('.ajaxLoaderApplication').css('visibility','hidden').css('display','none');
					console.log('erreur');
					
				}
			});
		}

		// Pour initialiser la mule
      	function initMule(nomTache, listTypeDonnee, formGroup){
      		
      		
      		if(typeof formGroup === 'undefined'){
      			var formGroup = $('#formMule').find('.form-group:last');
      		}
      		var numeroDonnee = 0,
      			numeroTache = formGroup.parent().find('.form-group:first select').attr('name');

      		formGroup.html('');
			for(var i=0, c=listTypeDonnee.length; i<c ; ++i){
				if(listTypeDonnee[i]['nomTache'] === nomTache){
					if(listTypeDonnee[i]['ext'] != 'input.txt'){
						var contenu = '<div class="donneeUser dashedBorder" data-html="true" data-toggle="popover" data-content="<span class=\'badge\'>'+listTypeDonnee[i]['ext']+'</span> '+listTypeDonnee[i]['description']+'" title="'+listTypeDonnee[i]['nomTypeDonnee']+'"></div>';
						contenu += '<input type="hidden" class="inputData" name="'+numeroTache+'data'+numeroDonnee+'" value=""/>';
					}else{
						var contenu = '<input type="txt" name="'+numeroTache+'data'+numeroDonnee+'" class="donneeUser dashedBorder input-sm" value="" placeholder="'+listTypeDonnee[i]['description']+'" data-html="true" data-toggle="popover" data-content="'+listTypeDonnee[i]['description']+'" title="'+listTypeDonnee[i]['nomTypeDonnee']+'"/>';
					}
					formGroup.append(contenu);
					++numeroDonnee;
				}
			}

			// On affiche les info-bulles
			$('#formMule .dashedBorder').popover({placement:'right', trigger:'hover'});
			// Pour gérer le dropable
			initDragAndDropMule();
      	}

		// Pour ajouter une nouvelle tâche ou la supprimer de la mule
        function manageTaskMule(nomFirstTache, listTypeDonnee){
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
                
                initMule(nomFirstTache, listTypeDonnee);
                // Pour contrôler le changement des select
				$('#formMule').find('.listeTache').change(function(){
					initMule($(this).val(), listTypeDonnee, $(this).parent().next());
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
        }

        // Pour activer la fonction plein écran
		$('#boutonFullScreen').click(function(){
			var btn = $(this);
		    if (screenfull.enabled) {

		    	function screenChange(e){
		    		// remove this event
					e.target.removeEventListener(e.type, arguments.callee);
					if(screenfull.isFullscreen){
						btn.removeClass('glyphicon-fullscreen').addClass('glyphicon-resize-small');
						btn.attr('data-original-title','Normal screen mode');
					}else{
						btn.removeClass('glyphicon-resize-small').addClass('glyphicon-fullscreen');
						btn.attr('data-original-title','Full screen mode');
					}
			    }

			    function screenError(e){
			    	// remove this event
					e.target.removeEventListener(e.type, arguments.callee);
			        var response = {
	                        'erreurs': '<p>Failed to enable fullscreen. Your browser is not supported.</p>'
	                    };
	                displayInformationsClient(response);
			    }
		    	
		    	document.addEventListener(screenfull.raw.fullscreenerror, screenError);
			    screenfull.toggle(); // Attention, cette fonction empêche l'apparition de message d'erreurs. Fonctionne avec screefull.request()
				document.addEventListener(screenfull.raw.fullscreenchange, screenChange);
		    }
		});
	}
});