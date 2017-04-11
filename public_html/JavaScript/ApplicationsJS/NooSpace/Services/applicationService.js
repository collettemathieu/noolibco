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
 * @name:  Application Noospace
 * @access: public
 * @version: 1
 */
application.factory('applicationService',['dataService','muleService', function(dataService,muleService){
	return{
		deployApplication: function(app, element, nouvellePositionElementX, nouvellePositionElementY){
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

	      				dataService.initDonneeUtilisateur(ui.draggable.clone(), $(this), 2, 2);

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

							muleService.runTheMule(formData, cloneApplication);
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

										dataService.initMule(nomFirstTache, listTypeDonnee);

										// On ajoute les boutons
										$('<button type="submit" data-loading-text="<span class=\'glyphicon glyphicon-refresh spinning\'></span> Running..." class="btn btn-default pull-right">Go forward</button>').insertAfter('.task:last');
										$('<button class="btn btn-default pull-left" disabled>&ndash;</button>').insertAfter('.task:last');
										$('<button class="btn btn-default pull-left">+</button>').insertAfter('.task:last');

										// Pour contrôler le changement des select
										$('#formMule').find('.listeTache').change(function(){
											dataService.initMule($(this).val(), listTypeDonnee, $(this).parent().next());
										});

								      	// Pour gérer les formulaires de la mule
							      		muleService.manageTaskMule(nomFirstTache, listTypeDonnee);


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
								      		muleService.runTheMule(formData, cloneApplication);

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
		},
		verifApplication: function(){
			if($('#noospace .runIt').length !=0){
			var widthNoospace = parseInt($('#noospace').css('width'));
			$('#noospace').css('width', widthNoospace-50+'px'); // Bidouille pour éviter un agrandissement de la noospace
			
			var appRunIt = $('#noospace .runIt');
			this.deployApplication(appRunIt, $('#noospace'));
			appRunIt.remove();
		}
		}
}
}]);
