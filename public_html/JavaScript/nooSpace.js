$(function(){
	if($('#noospace').length !=0){

		// Pour gérer l'affichage du gestionnaire de données
		var overlayGestionnaireDonnees = $('#overlayGestionnaireDonnees'),
			decalageInitiale = -parseInt(overlayGestionnaireDonnees.css('width')),
			numberApp = 0, // Compteur du nombre d'application dans la noospace
			tabImage = [],
			tabGraph = [],
			tabComments = [],
			tabTableOfResults = [],
			tabFileResults = [];
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
      		$.ajax({
				url: '/HandleApplication/RunTheMule',
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
						var templateItemReportApplication = $('#templateItemReportApplication');

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

								var images = tableauReponse['image'];

								for(var i=0, lenImages = images.length; i<lenImages; ++i){
									var image = new Image(),
										randomNumberImage = Math.floor(Math.random()*100);
									
									image.src = 'data:image/'+tableauReponse['image'][i][ext]+';base64,'+tableauReponse['image'][i][data];
									nav.append('<li><a href="#image'+numeroApp+randomNumberImage+'" data-toggle="tab">'+tableauReponse['image'][i][name]+'.'+tableauReponse['image'][i][ext]+'</a></li>');
									content.append('<div class="tab-pane results imageResult centering" id="image'+numeroApp+randomNumberImage+'">'+image+'</div>');
									
									// On enregistre la donnée image
									tabImage[numeroApp].push({
										ext: tableauReponse['image'][i][ext],
										name: tableauReponse['image'][i][name],
										rawData: tableauReponse['image'][i][data],
										dataJson: 'data:image/'+tableauReponse['image'][i][ext]+';base64,'+tableauReponse['image'],
										data: 'data:image/'+tableauReponse['image'][i][ext]+';base64,'+tableauReponse['image'],
										sample: 1,
										min: 1,
										size: 1
									});
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

            			// On gère l'affichage par la fenêtre modale
            			cloneApplication.find('.resultBox img').click(function(){
            				
            				// On affiche la fenêtre
            				$('#resultReportApplication').modal();
            				
            				//On ajoute le numéro de l'application dont sont issues les résultats
            				$('#resultReportApplication').attr('numApp', cloneApplication.attr('numApp'));
            			
        					// On insert les résultats de l'application
        					cloneApplication.find('.applicationReports').removeClass('hidden');
            				cloneApplication.find('.applicationReports').appendTo($('#carouselApplicationReport'));
            				
        					// Traitement des données du caroussel
            				// Pour sauvegarder l'image sur ordinateur
            				if(tabImage[numeroApp].length != 0){
	            				/*
	            				$('#carouselApplicationReport').find('.imageResult').each(function(index, e){
		            				
		            				$(e).click(function(){
		            					if(tabImage[numeroApp][index]){
			            					var blob = base64toBlob(tabImage[numeroApp][index]['rawData'], 'image/png'),
												nombre = Math.floor(Math.random()*1000+1),
												fileName = 'Picture_generated_by_NooLib_'+nombre+'.png';
											saveAs(blob, fileName);
										}
		            				});
		            			});
		            			*/
            				}
            				
	            			if(tabGraph[numeroApp].length != 0){
	            				
						        // Pour sauvegarder la table sur ordinateur au format csv
            					/*
            					$(e).click(function(){

	            					var stringCSV = tableToCSV(tabGraph[numeroApp][index]['legend'], tabGraph[numeroApp][index]['rawData']),
	            						blob =  new Blob([stringCSV], {type: "text/csv;charset=utf-8"}),
										nombre = Math.floor(Math.random()*1000+1),
										fileName = 'Table_generated_by_NooLib_'+nombre+'.csv';
									saveAs(blob, fileName);
	            				});
	            				*/
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
	}
});

function base64_decode (encodedData) { // eslint-disable-line camelcase
  //  discuss at: http://locutus.io/php/base64_decode/
  // original by: Tyler Akins (http://rumkin.com)
  // improved by: Thunder.m
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Kevin van Zonneveld (http://kvz.io)
  //    input by: Aman Gupta
  //    input by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
  // bugfixed by: Pellentesque Malesuada
  // bugfixed by: Kevin van Zonneveld (http://kvz.io)
  // improved by: Indigo744
  //   example 1: base64_decode('S2V2aW4gdmFuIFpvbm5ldmVsZA==')
  //   returns 1: 'Kevin van Zonneveld'
  //   example 2: base64_decode('YQ==')
  //   returns 2: 'a'
  //   example 3: base64_decode('4pyTIMOgIGxhIG1vZGU=')
  //   returns 3: '✓ à la mode'
  // decodeUTF8string()
  // Internal function to decode properly UTF8 string
  // Adapted from Solution #1 at https://developer.mozilla.org/en-US/docs/Web/API/WindowBase64/Base64_encoding_and_decoding
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