$(function(){
	if($('#noospace').length !=0){
		
		var overlayGestionnaireDonnees = $('#overlayGestionnaireDonnees'),
			numberApp = 0, // Compteur du nombre d'application dans la noospace
			tabImage = [],
			tabGraph = [],
			tabComments = [],
			tabTableOfResults = [],
			tabFileResults = [];
		dataManagerAlreadyOpened = false // Pour connaître le statut du gestionnaire de données
		addDataAlreadyLoaded = false; // Pour éviter un envoi multiple des formulaires lors de l'ajout de données
		
		// Requête pour récupérer la session utilisateur à envoyer à NodeJs
		$.ajax({
			url: '/NooSpace/getSession',
			type: 'POST',
			async: true,
			cache: false,
			contentType: false,
			processData: false,
			success: function(response) {
				try{
					response=JSON.parse(response);
					sessionStorage.setItem("id",response['id']);
					sessionStorage.setItem("isAdmin",response['isAdmin']);
				}
				catch(e){
					var response = {
					  'erreurs': '<p>A system error has occurred while getting your user session.</p>'
					};
					displayInformationsClient(response);
				}		
			},
			error: function(){
				var response = {
				  'erreurs': '<p>A system error has occurred while getting your user session.</p>'
				};
				displayInformationsClient(response);
			}
		});


		// Pour gérer l'affichage du gestionnaire de données
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
			      	largeurGestionnaireDonnee = parseInt(overlayGestionnaireDonnees.css('width')),
			      	hauteurGestionnaireDonnee = overlayGestionnaireDonnees.offset().top+parseInt(overlayGestionnaireDonnees.css('height'));

		      	// Pour insérer une application dans la noospace
		      	if(ui.draggable.parent().attr('id') === 'applicationsInDock' || ui.draggable.hasClass('runIt')){

		      		deployApplication(ui.draggable, $(this), nouvellePositionElementX, nouvellePositionElementY);

				// Pour insérer une nouvelle donnée dans la noospace
			    }else if(ui.draggable.parent().attr('id') === 'inListeDonneesUser' && (positionSourisX > largeurGestionnaireDonnee || positionSourisY > hauteurGestionnaireDonnee)){

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
      		cloneApplication.attr('numApp',numberApp);numberApp+=1;// On ajoute un numéro à l'application
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
	      		accept: '.donneeUser',
	      		drop: function(event, ui){
	      			var positionSourisX = event.clientX,
	      			largeurGestionnaireDonnee = parseInt($('#overlayGestionnaireDonnees').css('width'));
	      			
	      			if(positionSourisX > largeurGestionnaireDonnee){

	      				initDonneeUtilisateur(ui.draggable.clone(), $(this), 2, 2);
	      				if(ui.draggable.parent().attr('id') != 'inListeDonneesUser'){
	      					ui.draggable.remove();//On supprime l'originale s'il la donnée n'appartient pas au gestionnaire des données
	      				}
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
		            	var numeroApp = cloneApplication.attr('numApp');
		            	$(this).parent().remove();
		            	tabImage[numeroApp] = [];
						tabGraph[numeroApp] = [];
						tabFileResults[numeroApp] = [];
						console.log(tabImage);
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

		// On gère lorsque la fenêtre modale se ferme - on retourne son contenu dans l'application
		$('#resultReportApplication').on('hidden.bs.modal', function(){
			var numApp = parseInt($('#resultReportApplication').attr('numApp')),
				elem;
			$('.appInDock').each(function(){
				if(parseInt($(this).attr('numApp')) == numApp){
					elem = $(this);
				}
			});

			$('#resultReportApplication').find('.applicationReports').addClass('hidden');
			$('#resultReportApplication').find('.applicationReports').appendTo(elem.find('.resultBox'));
			
			//On vide le caroussel de son contenu
			$('#carouselApplicationReport').empty();
		});
            			


		// Pour lancer l'application et gérer les résultats de retour
		function runTheMule(formData, cloneApplication){
			// On lance la requête ajax
			formData.append('id',sessionStorage['id']);
			formData.append('isAdmin',sessionStorage['isAdmin']);
      		$.ajax({
				url:  'http://'+window.location.hostname+':3000/runTheMule/',
				type: 'POST',
				async: true,
				cache: false,
				data: formData,
				contentType: false,
				processData: false,
				success: function(response) {
					
					console.log(response);

					var numeroApp = cloneApplication.attr('numApp');
					// Pour réinitialiser le message d'attente du bouton
					$('#formMule').find('button:last').button('reset');

					// On cache le loader
					cloneApplication.children('.ajaxLoaderApplication').css('visibility', 'hidden').css('display', 'none');

					// On cache les résultats précédents
					cloneApplication.find('.resultBox img').hide(600);

					// On efface le rapport précédent
					cloneApplication.find('.applicationReports').empty();
					tabImage[numeroApp] = [];
					tabGraph[numeroApp] = [];
					tabFileResults[numeroApp] = [];

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
						// Variables globales
						var templateItemReportApplication = $('#templateItemReportApplication'),
							indexImage = 0,
							indexGraph = 0;

						for(var n=0,lenResultats=response['resultat'].length; n<lenResultats ; ++n){

							try{
								var tableauReponse = JSON.parse(response['resultat'][n]);
							}
							catch(e){
								var response = {
								  'erreurs': '<p>Fatal system error: '+response['resultat'][n]+'.</p>'
								};
								displayInformationsClient(response);
							}
							// On créé un nouveau rapport de résultats
							var	reportClone = templateItemReportApplication.clone(),
								numeroRand = Math.floor(Math.random()*100);
							reportClone.removeAttr('id');
							reportClone.removeClass('hidden');

							// On renomme l'ensemble des onglet
							var elemA =reportClone.find('a'),
								elemPan = reportClone.find('.tab-pane'),
								nav = reportClone.find('.nav'),
								content = reportClone.find('.tab-content');
							elemA[0].href = '#table2D'+numeroApp+numeroRand;
							//elemA[1].href = '#tableResult'+numeroApp+numeroRand;
							elemA[1].href = '#graph'+numeroApp+numeroRand;
							elemA[2].href = '#results'+numeroApp+numeroRand;
							elemA[3].href = '#comments'+numeroApp+numeroRand;
							//elemA[5].href = '#fileResult'+numeroApp+numeroRand;

							elemPan[0].id = 'table2D'+numeroApp+numeroRand;
							//elemPan[1].id = 'tableResult'+numeroApp+numeroRand;
							elemPan[1].id = 'graph'+numeroApp+numeroRand;
							elemPan[2].id = 'results'+numeroApp+numeroRand;
							elemPan[3].id = 'comments'+numeroApp+numeroRand;
							//elemPan[5].id = 'fileResult'+numeroApp+numeroRand;
							
							if(tableauReponse['table']){
								// A créer
								reportClone.find('.table2D').remove();
								$(elemA[0]).parent().remove();
							}else{
								reportClone.find('.table2D').remove();
								$(elemA[0]).parent().remove();	
							}

							if(tableauReponse['images']){

								var images = tableauReponse['images'];

								for(var i=0, lenImages = images.length; i<lenImages; ++i){
									var image = new Image(),
										randomNumberImage = Math.floor(Math.random()*100);
										
									image.src = 'data:image/'+images[i]['ext']+';base64,'+images[i]['data'];
									nav.append('<li><a href="#image'+numeroApp+randomNumberImage+'" data-toggle="tab">'+images[i]['name']+'.'+images[i]['ext']+'</a></li>');
									content.append('<div class="tab-pane results imageResult text-center" index-image="'+indexImage+'" id="image'+numeroApp+randomNumberImage+'"></div>');
									content.find('#image'+numeroApp+randomNumberImage).append(image);
									
									// On enregistre la donnée image
									tabImage[numeroApp].push({
										ext: images[i]['ext'],
										name: images[i]['name'],
										rawData: images[i]['data'],
										dataJson: 'data:image/'+images[i]['ext']+';base64,'+images[i]['data'],
										data: 'data:image/'+images[i]['ext']+';base64,'+images[i]['data'],
										sample: 1,
										min: 1,
										size: 1
									});
									indexImage+=1;
								}
							}
							if(tableauReponse['graph']){

								// Création de l'objet TxtReader à partir des données
					            var txtReader = new TXTFile(),
					            	num_points_display = 15000;
					            if(typeof(tableauReponse['graph']['legend']) !== 'undefined' && typeof(tableauReponse['graph']['data']) !== 'undefined' && typeof(tableauReponse['graph']['sampleRate']) !== 'undefined'){
						            txtReader.construct_from_data(tableauReponse['graph']['legend'], tableauReponse['graph']['data'], num_points_display, tableauReponse['graph']['sampleRate']);
						            
						            // Création de la table
						            tableData(txtReader, reportClone);
						            reportClone.find('.tableOfGraph').attr('index-graph', indexGraph);
						            indexGraph += 1;

						            // On enregistre la donnée table
						            tabGraph[numeroApp].push({
										ext: 'csv',
										name: 'no name',
										rawData: tableauReponse['graph']['data'],
										legend: txtReader.get_legend(),
										data: txtReader,
										sample: txtReader.get_sample_rate(),
										min: 1,
										lengthData: txtReader.get_size_signals(),
										size: txtReader.get_number_of_signals()
									});

									// Création du graphe	
							        graphLocalData(txtReader, 15000, reportClone);


						        }else{
						        	var reponse = {
										erreurs: '<p>Legend, array of data or sample rate is missing for displaying the data.</p>'
									}
									displayInformationsClient(reponse);
						        }

							}else{
								reportClone.find('.graphResult').remove();
								$(elemA[1]).parent().remove();
							}

							if(tableauReponse['results']){
								var table = reportClone.find('.tableOfResults');
								
								for(var i=0, lenTabResults = tableauReponse['results'].length; i<lenTabResults; ++i){
									
									table.append('<table class="table table-nonfluid table-bordered table-striped table-condensed"><thead><tr></tr></thead><tbody><tr></tr></tbody></table>');
								    var headTable = table.find('thead:last tr'),
								        bodyTable = table.find('tbody:last tr');

									headTable.append('<th>'+tableauReponse['results'][i]['name']+'</th>');
									bodyTable.append('<td>'+tableauReponse['results'][i]['value']+'</td>');
									
								}
							}else{
								reportClone.find('.tableOfResults').remove();
								$(elemA[2]).parent().remove();
							}

							if(tableauReponse['comments']){
								reportClone.find('.commentsResult').html(tableauReponse['comments']);
							}else{
								reportClone.find('.commentairesResult').remove();
								$(elemA[3]).parent().remove();
							}

							if(tableauReponse['files']){

								for(var i=0, lenFiles = tableauReponse['files'].length; i<lenFiles ; ++i){
									var randomNumber = Math.floor(Math.random()*100),
										fileName = tableauReponse['files'][i]['name'],
										fileExt = tableauReponse['files'][i]['ext'].toLowerCase();
									nav.append('<li><a href="#file'+numeroApp+randomNumber+'" data-toggle="tab">'+fileName+'.'+fileExt+'</a></li>');
									content.append('<div class="tab-pane results editor" id="file'+numeroApp+randomNumber+'"></div>');
									
									// On enregistre le fichiers sources
									tabFileResults[numeroApp].push({
										ext: 'xml',
										name: fileName,
										dataJson: tableauReponse['files'][i]['data'],
										id: 'file'+numeroApp+randomNumber,
										sample: 1,
										min: 1,
										size: 1
									});	
								}

								// On crée les éditors
								setTimeout(function(){
									for(var i=0, lenFiles = tabFileResults[numeroApp].length; i<lenFiles ; ++i){
										var editor = ace.edit(tabFileResults[numeroApp][i]['id']),
											fileData = base64_decode(tabFileResults[numeroApp][i]['dataJson']);
										editor.$blockScrolling = Infinity; // Remove warning
										editor.setHighlightActiveLine(true); // Underline
										editor.setValue(fileData, 1);
										editor.setTheme('ace/theme/monokai'); // Edit the theme
										editor.getSession().setMode('ace/mode/'+tabFileResults[numeroApp][i]['ext']); // Edit the mode
									}
								}, 500);
							}

							// On insert le nouveau rapport d'activité dans la box de résultats
							reportClone.find('li:first').addClass('active');
							reportClone.find('.tab-pane:first').addClass('active');
            				reportClone.appendTo(cloneApplication.find('.applicationReports'));
            				
						}

						// Pour sauvegarder l'image sur ordinateur
						cloneApplication.find('.imageResult').click(function(event){
							if(tabImage[numeroApp].length != 0){
	            				var elem = $(event.target);

	            				if(!elem.hasClass('imageResult')){
	            					elem = elem.parent();
	            				}
								try{
									var index = parseInt(elem.attr('index-image')),
		            					blob = base64toBlob(tabImage[numeroApp][index]['rawData'], 'image/png'),
										nombre = Math.floor(Math.random()*1000+1),
										fileName = 'Picture_generated_by_NooLib_'+nombre+'.png';
									saveAs(blob, fileName);
								}
								catch(err){
									var response = {
									  'erreurs': '<p>A system error has occurred. No image found.</p>'
									};
									displayInformationsClient(response);
								}
								
            				}
						});

						// Pour sauvegarder les graphes sur ordinateur
						cloneApplication.find('.tableOfGraph').click(function(event){
							if(tabGraph[numeroApp].length != 0){
	            				var elem = $(event.target);
	            				
	            				while(!elem.hasClass('tableOfGraph') && elem[0].nodeName != 'body'){
	            					elem = elem.parent();
	            				}

								try{
									var index = parseInt(elem.attr('index-graph')),
	            						stringCSV = tableToCSV(tabGraph[numeroApp][index]['legend'], tabGraph[numeroApp][index]['rawData']),
	            						blob =  new Blob([stringCSV], {type: "text/csv;charset=utf-8"}),
										nombre = Math.floor(Math.random()*1000+1),
										fileName = 'Table_generated_by_NooLib_'+nombre+'.csv';
									saveAs(blob, fileName);
								}
								catch(err){
									var response = {
									  'erreurs': '<p>A system error has occurred. No table found.</p>'
									};
									displayInformationsClient(response);
								}	
	            			}
	            		});

            			// On gère l'affichage par la fenêtre modale
            			cloneApplication.find('.resultBox img').click(function(){
            				
            				// On affiche la fenêtre
            				$('#resultReportApplication').modal();
            				
            				//On ajoute le numéro de l'application dont sont issues les résultats
            				$('#resultReportApplication').attr('numApp', cloneApplication.attr('numApp'));
            			
        					// On insert les résultats de l'application
        					cloneApplication.find('.applicationReports').removeClass('hidden');
            				cloneApplication.find('.applicationReports').appendTo($('#carouselApplicationReport'));
            					
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

		// On gère la sauvegarde des résultats
		// Tous les résultats sont sauvegardés en une seule fois avec pls appels Ajax
		$('#formSaveResult').on('submit', function(e) {

			e.preventDefault();
			var numApp = parseInt($('#resultReportApplication').attr('numApp'));
			
		  try{
		  	// On sauvegarde l'ensemble des images
		  	for(var i=0, a=tabImage[numApp].length; i<a; ++i){
			  	saveResult(tabImage[numApp][i]);
			}

			// On sauvegarde l'ensemble des fichiers
		  	for(var i=0, b=tabFileResults[numApp].length; i<b; ++i){
			  	saveResult(tabFileResults[numApp][i]);
			}

			// On sauvegarde l'ensemble des tables
			for(var i=0, s=tabGraph[numApp].length; i<s; ++i){

			  	 // On restructure les données pour avoir la forme
	              // Time | Signal 1 | Signal 2 | ...
	              // 0.23 | 343.34   | 4343.34  | ...
	              var data = [],
							M = [];

			  	// On extrait les item sélectionnés par l'utilisateur
	              M[0] = 'Time'; // On ajoute la colonne des temps
	              for(var k=0, c=tabGraph[numApp][i].legend.length; k<c ; ++k){
	                  M[k+1] = tabGraph[numApp][i].legend[k];
	              }

	              data.push(M);

	              // On extrait les données de la table
	              for(var j=0 ; j<tabGraph[numApp][i].lengthData ; ++j){
	                  M=[];
	                  M[0] = j/(tabGraph[numApp][i].sample); // On récupère la colonne des temps
	                  for(var k=0, len=tabGraph[numApp][i].size; k<len ; ++k){
	                      M[k+1] = tabGraph[numApp][i].rawData[k][j];
	                  }

	                  data.push(M);
	              }

	              var dataJSON = JSON.stringify(data);
	              dataJSON = dataJSON.replace('\\r', '');// Bizarement il y a un retour chariot \r qui s'insère à la fin de la légende avec le JSON.stringify. On le retire.
	              tabGraph[numApp][i].dataJson = dataJSON;
			  	saveResult(tabGraph[numApp][i]);
			}


		  }
		  catch(e){
		      var response = {
		          'erreurs': '<p>A system error has occurred: '+e+'</p>'
		      };

		      displayInformationsClient(response);

		  }
		});
		// Fin de la sauvegarde des résultats

		// Pour sauvegarder un résultat dans le gestionnaire de données
		function saveResult(result){
			
			var form = new FormData();
		    form.append('ext', result.ext);
		    form.append('nomFichier', result.name);
		    form.append('donneeUtilisateur', result.dataJson);
		    form.append('sampleRateDonneeUtilisateur', result.sample);
		    form.append('tailleDonneeUtilisateur', result.size);
		    form.append('tempsMinimumDonneeUtilisateur', result.min);

	      // On affiche l'indicateur de progression
		  $('#image-result-waiter').show();

		  // On empêche de charger ou d'uploader une nouvelle donnée
		  $('#labelSubmitSaveResult').attr('for', '');
		  $('#labelSubmitSaveResult img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');
	      

	      // Envoi de la requête HTTP en mode asynchrone
	      $.ajax({
	          url: '/HandleData/AddLocalData',
	          type: 'POST',
	          data: form,
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
					if(listTypeDonnee[i]['ext'] === 'input.txt'){
						var contenu = '<input type="txt" name="'+numeroTache+'data'+numeroDonnee+'" class="donneeUser dashedBorder input-sm" value="" placeholder="'+listTypeDonnee[i]['description']+'" data-html="true" data-toggle="popover" data-content="'+listTypeDonnee[i]['description']+'" title="'+listTypeDonnee[i]['nomTypeDonnee']+'"/>';
					}else if(listTypeDonnee[i]['ext'] === 'input.bool'){
						var contenu = '<div class="checkbox_wrapper donneeUser" placeholder="'+listTypeDonnee[i]['description']+'" data-html="true" data-toggle="popover" data-content="'+listTypeDonnee[i]['description']+'" title="'+listTypeDonnee[i]['nomTypeDonnee']+'">'
						contenu += '<input type="hidden" name="'+numeroTache+'data'+numeroDonnee+'" value="0"/>';
						contenu += '<input type="checkbox" name="'+numeroTache+'data'+numeroDonnee+'" value="1"/>';
						contenu += '<label></label></div>';
					}else{
						var contenu = '<div class="donneeUser dashedBorder" data-html="true" data-toggle="popover" data-content="<span class=\'badge\'>'+listTypeDonnee[i]['ext']+'</span> '+listTypeDonnee[i]['description']+'" title="'+listTypeDonnee[i]['nomTypeDonnee']+'"></div>';
						contenu += '<input type="hidden" class="inputData" name="'+numeroTache+'data'+numeroDonnee+'" value=""/>';	
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


		// On contrôle si l'onglet graph est actif => on autorise l'update
		$('#overlayAfficherDonnee').find('.modal-body a').click(function(e){

		    var href = $(e.target).attr('href');
		    
		    if(href === '#graphResult'){
		        // On permet de sauvegarder la donnée
		        $('#labelSubmitUpdateData').attr('for', 'submitUpdateData');
		        $('#labelSubmitUpdateData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Update this data');
		    }else{
		        // On empêche de mettre à jour une nouvelle donnée
		        $('#labelSubmitUpdateData').attr('for', '');
		        $('#labelSubmitUpdateData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');
		    }
		});

		// On contrôle l'envoi du formulaire pour la sauvegarde de la donnée
		$('#formUpdateData').on('submit', function(e){

		    // On montre l'indicateur de chargement
		    $('#image-waiter').show();

		    // On permet de mettre à jour la donnée
		    $('#labelSubmitUpdateData').attr('for', '');
		    $('#labelSubmitUpdateData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');
		        
		    // On empêche l'envoi du formulaire
		    e.preventDefault();

		    // On lance la requête en ajax avec les paramètres sélectionnés par l'utilisateur
		    $(this).find('#debutDonnee').attr('value', namespaceGraphData.tmin);
		    $(this).find('#finDonnee').attr('value', namespaceGraphData.tmax);
		    $(this).find('#rowSelected').attr('value', JSON.stringify(namespaceGraphData.rowSelected));
		    

		    // Envoi de la requête HTTP en mode asynchrone
		    $.ajax({
		      url: '/HandleData/UpdateData',
		      type: 'POST',
		      async: true,
		      cache: false,
		      processData: false,
		      data: $(this).serialize(),
		      success: function(response) {
		        
		        response = JSON.parse(response);
		        displayInformationsClient(response);
		        // On ferme la fenêtre modale
		        $('#overlayAfficherDonnee').modal('hide');

		        // On permet de mettre à jour la donnée
		        $('#labelSubmitUpdateData').attr('for', 'submitUpdateData');
		        $('#labelSubmitUpdateData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Update this data');
		        
		      },
		      error: function(){
		        var response = {
		          'erreurs': '<p>A system error has occurred.</p>'
		        };
		        displayInformationsClient(response);

		        // On permet de mettre à jour la donnée
		        $('#labelSubmitUpdateData').attr('for', 'submitUpdateData');
		        $('#labelSubmitUpdateData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Update this data');
		      }
		    });

		});	
	}
});


/*****************/
/* Les fonctions */
/*****************/

/* Les espaces de noms */
var namespaceGraphData = {},
    namespaceGraphLocalData = {};

/**
* Permet d'afficher une donnée chargée localement sous forme de graphe
*/
function graphLocalData(object, num_points_display, overlay){
    if(object.get_name() === 'EDFFile' || object.get_name() === 'TXTFile'){
        if(object.get_name() === 'EDFFile'){

            var numSignal = parseInt(object.get_header_item('num_signals_in_data_record')),
                sampleRate = Math.round(object.get_signal_item(0, 'num_samples_in_data_record')/object.get_header_item('duration_of_data_record')),
                totalPoint = Math.round(object.get_header_item('num_data_records')*sampleRate),
                startTime = 0,
                endTime = object.get_header_item('num_data_records');
        }else if(object.get_name() === 'TXTFile'){

            var numSignal = object.get_number_of_signals(),
                sampleRate = object.get_sample_rate(),
                totalPoint = object.get_size_signals(),
                startTime = 0,
                endTime = totalPoint;
        }
        var rowSelected = [], // Signaux sélectionnés par l'utilisateur
            legende = object.get_legend(), // On récupère la légende
            bool = false,
            visibleLegend = true,
            visibleSignal = true;
        rowSelected[0] = 0;  

        // Déclaration des variables globales
        namespaceGraphLocalData.tmin = 0;
        namespaceGraphLocalData.tmax = namespaceGraphLocalData.tmin + Math.round(num_points_display/sampleRate)-1; // On prend les num_points_display premiers points
        if(namespaceGraphLocalData.tmax > endTime) namespaceGraphLocalData.tmax = endTime -1;
        namespaceGraphLocalData.dataForForm = {
            'data': object.get_series(),
            'rowSelected': rowSelected,
            'legend': legende,
            'sampleRate': sampleRate,
            'minimum': namespaceGraphLocalData.tmin,
            'maximum': namespaceGraphLocalData.tmax
        };
        

        /**
        * Charger les données selon le minimum et le maximum demandées par l'utilisateur (lors d'un zoom)
        */
        function resize(startTimeResize, endTimeResize) {
            
            if(startTimeResize < namespaceGraphLocalData.tmin || endTimeResize > namespaceGraphLocalData.tmax){
                
                var chart = $(overlay).find('.graphResult').highcharts();
                
                // On empêche de charger ou d'uploader une nouvelle donnée
                $('#labelSubmitLocalData').attr('for', '');
                $('#labelSubmitLocalData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');
                $('#labelFileLocalData').attr('for', '');
                $('#labelFileLocalData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');

                // On met à jour les variables globales                
                namespaceGraphLocalData.tmin = startTimeResize;
                namespaceGraphLocalData.tmax = endTimeResize;

                chart.showLoading('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading data...');
                
                // setTimeout pour simuler le temps de réponse du serveur
                setTimeout(function(){
                    try{
                        // Importation des nouvelles données
                        var data = object.get_all_signals_data(startTimeResize, endTimeResize);
                        
                        // Mise à jour du graphique
                        for(var i=0, c=numSignal ; i < c ; ++i){
                            chart.get(legende[i]+'-serie').setData(data[i]);
                        }
                        // Mise à jour de la variable data
                        namespaceGraphLocalData.dataForForm['data'] = data;
                    }
                    catch(e){
                        var response = {
                            'erreurs': '<p>A system error has occurred: '+e+'</p>'
                        };

                        displayInformationsClient(response);
                    }
                    chart.hideLoading();
                    
                    // On permet d'uploader la donnée
                    $('#labelSubmitLocalData').attr('for', 'submitLocalData');
                    $('#labelSubmitLocalData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Load this data');
                    // On permet l'ajout d'une donnée
                    $('#labelFileLocalData').attr('for', 'fileLocalData');
                    $('#labelFileLocalData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Add a new data');

                }, 10);   
            }

            // Mise à jour de la variable globale Data
            namespaceGraphLocalData.dataForForm['minimum'] = startTimeResize;
            namespaceGraphLocalData.dataForForm['maximum'] = endTimeResize;
            
        }


        // Création du graphique
        $(overlay).find('.graphResult').highcharts('StockChart', {
            chart : {
                reflow: false,
                animation: false,
                zoomType: 'x',
                height: parseInt($('html').css('height'))*0.7,
                width: parseInt($('html').css('width'))*0.86
            },
            credits: {
              enabled: false
            },
            tooltip: {
                enabled: false,
                animation: false
            },
            navigator : {
                adaptToUpdatedData: false,
                series : {
                    id: 'navigator',
                    data: object.get_navigator()
                },
                xAxis: {
                    labels: {   
                        formatter: function () {
                            return Math.round(this.value/(1000*sampleRate)*100)/100;
                        }
                    }
                }
                
            },
            legend: {
                enabled: true
            },
            plotOptions: {
                line: {
                    animation: false,
                    stickyTracking: true,
                    shadow: false,
                    dataLabels: {
                        enabled: false,
                        style: { textShadow: false }
                    },
                    enableMouseTracking: false
                },
                series: {
                    animation: false,
                    enableMouseTracking: false,
                    stickyTracking: true,
                    shadow: false,
                    dataLabels: {
                        style: { textShadow: false }
                    },
                    events: {
                        legendItemClick: function(event) {
                           
                            var numberCol = legende.indexOf(this.name);
                            // On met à jour les colonnes sélectionnées pour l'extraction des données
                            if(numberCol != -1){
                                if(this.visible){
                                    var index = rowSelected.indexOf(numberCol);
                                    rowSelected.splice(index, 1);
                                }else{
                                    rowSelected.push(numberCol);
                                    rowSelected.sort(function(a,b){
                                        if(a < b){
                                            return -1;
                                        }else if(a>b){
                                            return 1;
                                        }else{
                                            return 0.
                                        }
                                    });
                                }
                                namespaceGraphLocalData.dataForForm['rowSelected'] = rowSelected;
                            }
                        }
                    },
                    lineWidth: 1
                }
            },
            scrollbar: {
                liveRedraw: false
            },

            title: {
                text: ''
            },

            rangeSelector : {
                buttons: [{
                    type: 'all',
                    text: 'All'
                }],
                inputEnabled: false, // it supports only days
                selected : 1 // all
            },
            loading: {
                style: {
                    position: 'absolute',
                    backgroundColor: 'white',
                    opacity: 0.7,
                    textAlign: 'center'
                },
                labelStyle: {
                    position: 'relative',
                    top: '0',
                    color: 'black',
                    fontSize: '1em',
                    fontWeight: 'normal'
                }
            },
            xAxis : {
                events : {
                    setExtremes : function(e){
                       
                        // On contrôle si on sort du cadre du graphique
                        if(typeof e.min == 'undefined' && typeof e.max == 'undefined'){
                            resize(startTime, endTime);
                        }else if(typeof e.min == 'undefined'){
                            resize(startTime, e.max);
                        }
                        else if(typeof e.max == 'undefined'){
                            resize(e.min, endTime);
                        }
                        else{
                            var startTimeSet = Math.round(e.min/(1000*sampleRate)),
                                endTimeSet = Math.round(e.max/(1000*sampleRate));
                                
                            // On contrôle l'intervalle choisit selon la limite de points de l'objet, sinon on le réinitialise
                            if((endTimeSet-startTimeSet)*sampleRate > object.get_limit_points_display() + 1000){  
                                  
                                var chart = $(overlay).find('.graphResult').highcharts();
                                setTimeout(function(){

                                    chart.xAxis[0].setExtremes(startTimeSet*1000*sampleRate, object.get_limit_points_display()*1000 + startTimeSet*1000*sampleRate);
                                    var response = {
                                      'erreurs': '<p>The data interval has been adjusted automatically for a better display.</p>'
                                    };
                                    displayInformationsClient(response);
                                }, 1);
                                
                            }else{
                                resize(startTimeSet, endTimeSet);
                            }
                        }
                    }
                    
                },
                labels: {   
                    formatter: function () {
                        return Math.round(this.value/(1000*sampleRate)*100)/100;
                    }
                }
            }
        });

        
        // On ajoute les series successives au graph
        var chart = $(overlay).find('.graphResult').highcharts();

        if(numSignal > 4){
            visibleSignal = false;
        }

        for(var i=0, c=numSignal ; i < c ; ++i){
            
            if(i%2 == 0){
                bool = false;
            }else{
                bool = true;
            }

            chart.addAxis({
                id: legende[i] + '-axis',
                gridLineWidth: 0,
                title: {
                    text: legende[i],
                    style: {
                        color: Highcharts.getOptions().colors[i]
                    },
                    enabled: visibleSignal
                },
                labels: {
                    formatter: function () {
                        return this.value;
                    },
                    style: {
                        color: Highcharts.getOptions().colors[i]
                    },
                    enabled: visibleSignal
                },
                opposite: bool

            });

            chart.addSeries({
                name: legende[i],
                yAxis: legende[i] + '-axis',
                id: legende[i]+'-serie',
                visible: visibleLegend,
                data: object.get_series(i)
            });

            visibleLegend = false;

        }
    }else{
        var response = {
            'erreurs': '<p>[JS] No object has been identified.</p>'
        };
        displayInformationsClient(response);
    }
}




/**
* Permet d'afficher une donnée stockée sur le serveur sous forme de graphe
*/
function graphData(graphObjet){

    /**
     * Charger les données selon le minimum et le maximum demandées par l'utilisateur (lors d'un zoom)
    */
    function afterSetExtremes(min, max) {
        
        // Mise à jour des variables globales
        namespaceGraphData.tmin = min/1000;
        namespaceGraphData.tmax = max/1000;

        var min = Math.round(min/1000),
            max = Math.round(max/1000);
       
        if(min < namespaceGraphData.startTime || max > namespaceGraphData.endTime){

            // On empêche de mettre à jour une nouvelle donnée
            $('#labelSubmitUpdateData').attr('for', '');
            $('#labelSubmitUpdateData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');
           
            var chart = $('#overlayAfficherDonnee').find('.graphResult').highcharts();
            
            chart.showLoading('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading data...');

            // Mise à jour des variables globales
            namespaceGraphData.startTime = min;
            namespaceGraphData.endTime = max;

            $.getJSON('/HandleData/SendDataForHighStock/idDonnee='+graphObjet['idDonneeUtilisateur']+'debutDonnee='+min+'finDonnee='+max, function (response) {
                
                var data = response['data'];

                // Mise à jour du graphique
                for(var i=0, c=namespaceGraphData.numSignal ; i < c ; ++i){
                    
                    chart.get(namespaceGraphData.legende[i]+'-serie').setData(data[i]);
                
                }

                chart.hideLoading();

            });

            // On permet de mettre à jour la donnée
            $('#labelSubmitUpdateData').attr('for', 'submitUpdateData');
            $('#labelSubmitUpdateData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Update this data');
        }
    }

    // Les données sont chargées en ajax
    $.getJSON('/HandleData/SendDataForHighStock/idDonnee='+graphObjet['idDonneeUtilisateur'], function (response) {
        
        // On cache l'indicateur de chargement
        $('#image-waiter').hide();

        if(response['erreurs']){         
             displayInformationsClient(response);
        }else{

            var isGraphSelected = $('#overlayAfficherDonnee').find('.graphResult').hasClass('active');
    
            if(isGraphSelected){
                // On permet de sauvegarder la donnée
                $('#labelSubmitUpdateData').attr('for', 'submitUpdateData');
                $('#labelSubmitUpdateData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Update this data');
            }

            // On créé le tableau de données
            var tabSeries = [],
                bool = false,
                visibleLegend = true,
                visibleSignal = true,
                serieNavigator = response['serieNavigator'];

            // Variables globales
            namespaceGraphData.startTime = response['startTime'];
            namespaceGraphData.endTime = response['endTime'];
            namespaceGraphData.legende = response['legende'];
            namespaceGraphData.numSignal = namespaceGraphData.legende.length;
            namespaceGraphData.sampleRate = response['sampleRate'];
            namespaceGraphData.tailleSignal = response['tailleSignal'];
            namespaceGraphData.rowSelected = [];
            namespaceGraphData.tmin = namespaceGraphData.startTime;
            namespaceGraphData.tmax = namespaceGraphData.endTime;

            // Initialisation
            for(var i=0; i<namespaceGraphData.numSignal ; ++i){
                tabSeries[i] = response['data'][i];  
            }
            namespaceGraphData.rowSelected[0] = 0;

            // Création de la table
            var txtReader = new TXTFile();
            txtReader.set_series(tabSeries);
            txtReader.set_legend(response['legende']);
            tableData(txtReader, '#overlayAfficherDonnee');

            // Création du graphique
            $('#overlayAfficherDonnee').find('.graphResult').highcharts('StockChart', {
                chart : {
                    reflow: false,
                    animation: false,
                    zoomType: 'x',
                    height: parseInt($('html').css('height'))*0.7,
                    width: parseInt($('html').css('width'))*0.86
                },
                credits: {
                  enabled: false
                },
                tooltip: {
                    enabled: false,
                    animation: false
                },
                navigator : {
                    adaptToUpdatedData: false,
                    series : {
                        id: 'navigator',
                        data : serieNavigator
                    },
                    xAxis: {
                        labels: {   
                            formatter: function () {
                                return Math.round(this.value/(1000*namespaceGraphData.sampleRate)*100)/100; //(100/100) pour 2 chiffres après la virgule
                            }
                        }
                    }
                    
                },
                legend: {
                    enabled: true
                },
                plotOptions: {
                    line: {
                    animation: false,
                    stickyTracking: true,
                    shadow: false,
                    dataLabels: {
                            enabled: false,
                            style: { textShadow: false }
                        },
                        enableMouseTracking: false
                    },
                    series: {
                        animation: false,
                        enableMouseTracking: false,
                        stickyTracking: true,
                        shadow: false,
                        events: {
                            legendItemClick: function(event) {
                                var numberCol = namespaceGraphData.legende.indexOf(this.name);
                                // On met à jour les colonnes sélectionnées pour l'extraction des données
                                if(numberCol != -1){
                                    if(this.visible){
                                        var index = namespaceGraphData.rowSelected.indexOf(numberCol);
                                        namespaceGraphData.rowSelected.splice(index, 1);
                                    }else{
                                        namespaceGraphData.rowSelected.push(numberCol);
                                        namespaceGraphData.rowSelected.sort(function(a,b){
                                            if(a < b){
                                                return -1;
                                            }else if(a>b){
                                                return 1;
                                            }else{
                                                return 0.
                                            }
                                        });
                                    }
                                }
                            }
                        },
                        lineWidth: 1
                    }
                },
                scrollbar: {
                    liveRedraw: false
                },

                rangeSelector : {
                    buttons: [{
                        type: 'all',
                        text: 'All'
                    }],
                    inputEnabled: false, // it supports only days
                    selected : 1 // all
                },
                loading: {
                    style: {
                        position: 'absolute',
                        backgroundColor: 'white',
                        opacity: 0.6,
                        textAlign: 'center'
                    },
                    labelStyle: {
                        position: 'relative',
                        top: '0',
                        color: 'black',
                        fontSize: '1em',
                        fontWeight: 'normal'
                    }
                },
                xAxis : {
                    events : {
                        setExtremes : function(e){
                             // On contrôle si on sort du cadre du graphique
                            if(typeof e.min == 'undefined' && typeof e.max == 'undefined'){
                                afterSetExtremes(0, 30000);
                            }else if(typeof e.min == 'undefined'){
                                afterSetExtremes(0, e.max);
                            }
                            else if(typeof e.max == 'undefined'){
                                afterSetExtremes(e.min, 30000);
                            }
                            else{
                                afterSetExtremes(e.min, e.max);
                            }
                        }
                    },
                    labels: {   
                        formatter: function () {
                            return Math.round(this.value/(1000*namespaceGraphData.sampleRate)*100)/100;
                        }
                    }
                }
            });
            
            // On ajoute les axes et les series successives au graphe
            var chart = $('#overlayAfficherDonnee').find('.graphResult').highcharts();

            if(namespaceGraphData.numSignal > 4){
                visibleSignal = false;
            }


            for(var i=0, c=namespaceGraphData.numSignal ; i < c ; ++i){
                
                if(i%2 == 0){
                    bool = false;
                }else{
                    bool = true;
                }

                chart.addAxis({
                    id: namespaceGraphData.legende[i] + '-axis',
                    gridLineWidth: 0,
                    title: {
                        text: namespaceGraphData.legende[i],
                        style: {
                            color: Highcharts.getOptions().colors[i]
                        },
                        enabled: visibleSignal
                    },
                    labels: {
                        formatter: function () {
                            return this.value;
                        },
                        style: {
                            color: Highcharts.getOptions().colors[i]
                        },
                        enabled: visibleSignal
                    },
                    opposite: bool

                });

                chart.addSeries({
                    name: namespaceGraphData.legende[i],
                    yAxis: namespaceGraphData.legende[i] + '-axis',
                    id: namespaceGraphData.legende[i]+'-serie',
                    visible: visibleLegend,
                    data: tabSeries[i]
                });

                visibleLegend = false;
            }
        }
    });
}


/**
* Pour ouvrir/fermer le gestionnaire de données
*/
function openGestionnaireDonnees(){
    var boutonShowGestionnaireDonnees = $('#boutonShowGestionnaireDonnees'),
        overlayGestionnaireDonnees = $('#overlayGestionnaireDonnees'),
        decalageInitiale = -parseInt(overlayGestionnaireDonnees.css('width')),
        decalageFinale = 0;

    // Pour fermer
    if(parseInt(overlayGestionnaireDonnees.css('left')) >= decalageFinale){

        var tailleManager = parseInt($('#overlayGestionnaireDonnees').css('width')),
            positionLeftBouton = parseInt($('#boutonShowGestionnaireDonnees').css('left'));
        if(tailleManager > 500){
            $('#overlayGestionnaireDonnees').animate({'width': tailleManager-400}, 1500);
            $('#boutonShowGestionnaireDonnees').animate({'left':positionLeftBouton-400}, 1500);
            $('#laMule').hide();
            $('#formMule').find('.form-group').empty(); // On efface les données précédentes
        }else{
            // On anime le déplacement du div du gestionnaire de données
            overlayGestionnaireDonnees.animate({'left':decalageInitiale}, 1500);
            boutonShowGestionnaireDonnees.animate({'left':decalageFinale}, 1500);
            // On efface les données
            setTimeout(function(){
                 $('#inListeDonneesUser').empty();
            }, 1500);
            dataManagerAlreadyOpened = false;
        }
    }else{ // Pour ouvrir
        // Envoi de la requête HTTP en mode asynchrone
        $.ajax({
            url: '/HandleData/GestionnaireDeDonnees',
            type: 'POST',
            success: function(response) {
                try{
                    response = JSON.parse(response);
                    $('#inListeDonneesUser').html(response['listeDonneeUtilisateur']);
                    
                    dataManagerAlreadyOpened = true;

                    // On traite l'ajout de nouvelles données
                    if(!addDataAlreadyLoaded){
                        addData();
                        addDataAlreadyLoaded = true;
                    }

                    // On traite les données importées
                    var parametres = {
                        'tailleMoDonneesUtilisateur': response['tailleMoDonneesUtilisateur'],
                        'tailleMaxDonneesUtilisateur': response['tailleMaxDonneesUtilisateur'],
                        'progressionPourcent': response['progressionPourcent']
                    };
                    showData(parametres);
                }
                catch(e){
                    var response = {};
                    response['erreurs'] = '<p>A system error has occurred.</p>';
                    displayInformationsClient(response);
                }
            },
            error: function(){
                var response = {};
                response['erreurs'] = '<p>A system error has occurred.</p>';
                displayInformationsClient(response);
            }
        });

        // On anime le déplacement du div du gestionnaire de données
        overlayGestionnaireDonnees.css('display', 'inline-block');
        overlayGestionnaireDonnees.animate({'left':decalageFinale}, 1500);
        boutonShowGestionnaireDonnees.animate({'left':-decalageInitiale}, 1500);
    }
}


/**
* Initialisation des données
*/
function showData(parametres){
	
	var data = $('#sectionGestionnaireDonnees .donneeUser');

	// On affiche les info-bulles
	$('.donneeUser').each(function(index, e){
		var parent = $(e).parent();
		if(!parent.hasClass('dashedBorder')){
			$(e).popover({placement:'bottom', trigger:'hover'});
		}
	});

	//Pour rendre les données présentes dans le gestionnaire draggable par JS
	data.draggable({
		snap: '.dataBox',
		revert: true,
		scroll: false,
		helper: function(){
                $copy = $(this).clone();
                return $copy;},
		drag: function(event, ui){
			$(this).popover('hide');
		}
	});

	if(typeof(parametres) != 'undefined'){
		// On met à jour la barre d'espace disponible
	    $('#progressBarEspaceDisponibleData').attr('value', parametres['tailleMoDonneesUtilisateur']);
	    $('#progressBarEspaceDisponibleData').attr('max', parametres['tailleMaxDonneesUtilisateur']);
	    $('#progressionPourcent').html(parametres['progressionPourcent']+ '%');
	}
	
    // On applique l'évènement du click pour afficher les données
	data.on('click', function(){
		forShowingData($(this));
	});

	forSavingData();
	forDeletingData();
}

/**
* Permet d'afficher une donnée utilisateur
*/
function forShowingData(element){

    var overlayAfficherDonnee = $('#overlayAfficherDonnee');

    // On montre l'indicateur de chargement
    $('#image-waiter').show();

    // On ne permet pas de sauvegarder la donnée
    $('#labelSubmitUpdateData').attr('for', '');
    $('#labelSubmitUpdateData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');

    // On efface la donnée précédente
    overlayAfficherDonnee.find('.tableResult table').empty();
    overlayAfficherDonnee.find('.graphResult').empty();
    overlayAfficherDonnee.find('.row last').empty();
    overlayAfficherDonnee.find('.row first').empty();
    overlayAfficherDonnee.find('.row:nth-child(2)').empty();
    overlayAfficherDonnee.find('.row:first').addClass('hidden');
    overlayAfficherDonnee.find('.row:last').addClass('hidden');

    // On ouvre la fenêtre
    overlayAfficherDonnee.modal('show');

    
    // On lance la requête Ajax
    // Envoi de la requête HTTP en mode asynchrone
    $.ajax({
      url: '/HandleData/ShowData',
      type: 'POST',
      async: true,
      cache: false,
      data: { 
        idDonneeUtilisateur: element.attr('id')
        },
      success: function(response) {
        
        // On récupère la réponse au format JSON
        response = JSON.parse(response);

        if(response['erreurs']){
            displayInformationsClient(response);
            // On ferme la fenêtre
            overlayAfficherDonnee.modal('hide');

        }else{
            // On insère l'id de la donnée affichée
            overlayAfficherDonnee.find('#idDonneeUtilisateur').attr('value', response['idDonneeUtilisateur']);    
            
            // Si c'est une image standard
            if(response['imageStandard']){
                // On cache l'indicateur de chargement
                $('#image-waiter').hide();

                overlayAfficherDonnee.find('.row:nth-child(2)').html(response['imageStandard']);
           
            }else if(response['imageDicom']){  // Si c'est une image DICOM
                // On cache l'indicateur de chargement
                $('#image-waiter').hide();

                var element = overlayAfficherDonnee.find('.row:nth-child(2)').get(0),
                    imageDicom = base64toBlob(response['imageDicom'], 'application/dicom');
                try {
                      cornerstone.enable(element);
                      cornerstone.loadImage(imageDicom).then(function(image) {
                          
                        var viewport = cornerstone.getDefaultViewportForImage(element, image);
                        cornerstone.displayImage(element, image, viewport);
                        cornerstoneTools.mouseInput.enable(element);
                        cornerstoneTools.mouseWheelInput.enable(element);
                        cornerstoneTools.wwwc.activate(element, 1); // ww/wc is the default tool for left mouse button
                        cornerstoneTools.pan.activate(element, 2); // pan is the default tool for middle mouse button
                        cornerstoneTools.zoom.activate(element, 4); // zoom is the default tool for right mouse button
                        cornerstoneTools.zoomWheel.activate(element); // zoom is the default tool for middle mouse wheel
                          
                      }, function(e) {
                          var response = {
                              'erreurs': '<p>[JS] A system error has occured: '+e+'</p>'
                          };

                          displayInformationsClient(response);
                        
                      });
                  }
                  catch(e) {
                    var response = {
                      'erreurs': '<p>[JS] A system error has occured: '+e+'</p>'
                    };

                    displayInformationsClient(response);
                 }
            }else if(response['dataFile'] && response['ext']){// Si c'est un fichier xml
                
                // On cache l'indicateur de chargement
                $('#image-waiter').hide();
                overlayAfficherDonnee.find('.row:last').removeClass('hidden');
                // On crée un éditor de texte
                var element = overlayAfficherDonnee.find('.row:last'),
                    editor = ace.edit(element[0]),
                    fileExt = response['ext'].toLowerCase(),
                    fileData = base64_decode(response['dataFile']);

                editor.$blockScrolling = Infinity; // Remove warning
                editor.setHighlightActiveLine(true); // Underline
                editor.setValue(fileData, 1);
                editor.setTheme('ace/theme/monokai'); // Edit the theme
                editor.getSession().setMode('ace/mode/'+fileExt); // Edit the mode


            }else{ // Si c'est une table ou un graphe
                overlayAfficherDonnee.find('.row:first').removeClass('hidden');
                graphData(response);
            }
        }
        
      },
      error: function(){
        var response = {
          'erreurs': '<p>A system error has occurred.</p>'
        };
        displayInformationsClient(response);

        // On permet de mettre à jour la donnée
        $('#labelSubmitUpdateData').attr('for', 'submitUpdateData');
        $('#labelSubmitUpdateData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Update this data');
      }
    });
}

/**
* Permet de sauvegarder une copie de la donnée utilisateur sur le serveur
*/
function forSavingData(){

    $('#boutonSaveDataOnServer').droppable({

        drop: function(event, ui){
             
            $.post('/HandleData/SaveDataOnServer', {
                idDonneeUtilisateur: ui.draggable.attr('id')
            }, 
            function(response){
                
                // On récupère la réponse au format JSON
                response = JSON.parse(response);

                if(response['erreurs']){

                    displayInformationsClient(response);

                }else if(response['reussites']){

                    $('#inListeDonneesUser').html(response['listeDonneeUtilisateur']);
                    showData();
                    displayInformationsClient(response);
                }
            });

        }
        
    });
}

/**
* Pour supprimer une donnée utilisateur par ajax
*/
function forDeletingData(){
	$('#poubelleDonneeUtilisateur').droppable({

		drop: function(event, ui){
			$.post('/HandleData/DeleteData', {
				idDonneeUtilisateur: ui.draggable.attr('id')
			}, 
			function(response){

				// On récupère la réponse au format JSON
				response = JSON.parse(response);

				if(response['erreurs']){

			    	displayInformationsClient(response);

				}else if(response['reussites']){

			    	displayInformationsClient(response);
			    	
				}

                $('#noospace .donneeUser').each(function(){
                    if($(this).attr('id') === ui.draggable.attr('id')){
                        $(this).remove();
                    }
                });
                ui.draggable.remove();

                 // On met à jour la barre d'espace disponible
                $('#progressBarEspaceDisponibleData').attr('value', response['tailleMoDonneesUtilisateur']);
                $('#progressBarEspaceDisponibleData').attr('max', response['tailleMaxDonneesUtilisateur']);
                $('#progressionPourcent').html(response['progressionPourcent']+ '%');
			});
		}
		
	});
}


/**
* Pour transformer un tableau en CSV
*/
function tableToCSV(legend, table){
    if(Array.isArray(legend) && Array.isArray(table)){
        var string = '',
            sizeTable = table.length,
            sizeLegend = legend.length,
            sizeColumn = table[sizeTable-1].length;

        // On insère la légende
        for(var j=0; j<sizeLegend; ++j){
            if(j===sizeLegend-1){
                string += legend[j];
            }else{
                string += legend[j]+';';
            }
        }
        // On insère les données
        string += '\r\n';
        for(var j=0; j<sizeColumn; ++j){
            for(var i=0; i<sizeTable; ++i){
                if(i===sizeTable-1){
                    string += table[i][j];
                }else{
                    string += table[i][j]+';';
                }
            }  
            string += '\r\n';
        }

        return string;
    }else{
        console.log('tableToCSV::legend or table is not an array.');
    }
}


/**
* Permet d'afficher les données CSV sous forme d'une table
*/
function tableData(data, overlay){
    
    var table = $(overlay).find('.tableResult table');
    table.empty(); // On vide la table

    table.append('<thead><tr></tr></thead><tbody></tbody>');
    var headTable = table.find('thead tr'),
        bodyTable = table.find('tbody'),
        tailleTable, sampleRate, text;

    if(data.get_name() === 'TXTFile'){
        tailleTable = data.get_size_signals();
    }else if(data.get_name() === 'EDFFile'){
        sampleRate = Math.round(data.get_signal_item(0, 'num_samples_in_data_record')/data.get_header_item('duration_of_data_record'));
        tailleTable = Math.round(data.get_header_item('num_data_records')*sampleRate);
    }

    if(tailleTable > 50) tailleTable = 50;

    // On renseigne la légende du tableau
    for(var i=0, c=data.get_legend().length ; i < c ; ++i){
        headTable.append('<th>'+data.get_legend()[i]+'</th>');
    }
    // On renseigne les valeurs du tableau
    for(var i=0 ; i < tailleTable-1 ; ++i){
        text += '<tr>';
        for(var j=0, c=data.get_series().length ; j < c ; ++j){
            text += '<td>'+data.get_series()[j][i][1]+'</td>';
        }
        text += '</tr>';
    }
    text += '<tr>';
    for(var j=0, c=data.get_series().length ; j < c ; ++j){
        text += '<td>...</td>';
    }
    text += '</tr>';
    bodyTable.append(text);
    
}



/**
* Encodage en base64
*/
function base64_decode (encodedData) { // eslint-disable-line camelcase
  var decodeUTF8string = function (str) {
    // Going backwards: from bytestream, to percent-encoding, to original string.
    return decodeURIComponent(str.split('').map(function (c) {
      return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''))
  }
  if (typeof window !== 'undefined') {
    if (typeof window.atob !== 'undefined') {
      return decodeUTF8string(window.atob(encodedData));
    }
  } else {
    return new Buffer(encodedData, 'base64').toString('utf-8');
  }
  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/='
  var o1;
  var o2;
  var o3;
  var h1;
  var h2;
  var h3;
  var h4;
  var bits;
  var i = 0;
  var ac = 0;
  var dec = '';
  var tmpArr = [];
  if (!encodedData) {
    return encodedData;
  }
  encodedData += '';
  do {
    // unpack four hexets into three octets using index points in b64
    h1 = b64.indexOf(encodedData.charAt(i++));
    h2 = b64.indexOf(encodedData.charAt(i++));
    h3 = b64.indexOf(encodedData.charAt(i++));
    h4 = b64.indexOf(encodedData.charAt(i++));
    bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;
    o1 = bits >> 16 & 0xff;
    o2 = bits >> 8 & 0xff;
    o3 = bits & 0xff;
    if (h3 === 64) {
      tmpArr[ac++] = String.fromCharCode(o1);
    } else if (h4 === 64) {
      tmpArr[ac++] = String.fromCharCode(o1, o2);
    } else {
      tmpArr[ac++] = String.fromCharCode(o1, o2, o3);
    }
  } while (i < encodedData.length)
  dec = tmpArr.join('');
  return decodeUTF8string(dec.replace(/\0+$/, ''));
}