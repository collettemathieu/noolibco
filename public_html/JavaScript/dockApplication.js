$(function(){

	if($('#overlayDockApplication').length !=0){
		var hauteurFenetre = window.innerHeight,
			overlayDockApplication = document.getElementById('overlayDockApplication'),
			hauteurInitiale = -100,
			hauteurFinale = 0;

		overlayDockApplication.style.bottom = hauteurInitiale + 'px';
		overlayDockApplication.style.display = 'block';

		//Pour rendre les applications présentes dans le dock draggable par JS
		$('#applicationsInDock .appInDock').draggable({
			containment: '#noospace',
			revert: true
		});

		if($('#overlayDockApplication .appInDock').length !=0){
			// Menu contextuel de l'application
		  	$.contextMenu({
		        selector: '#applicationsInDock .appInDock', 
		        callback: function(key, options) {
		            if(key === 'delete'){
		            	var application = $(this);
		            	
		            	$.post('/HandleApplication/AddRemoveToDock', {
							idApplication: application.attr('id')
						}, 
						function(response){
							$('#boutonAjouterAuDock').html('<i class="glyphicon glyphicon-ok"></i>');
							$('#boutonAjouterAuDock').addClass('btn-success').removeClass('btn-danger');
							application.remove();
						});
		            }else if(key==='about'){
		            	document.location.href="/Library/app="+$(this).attr('id');
		            }else if(key==='tree'){
		            	document.location.href="/ManagerOfApplications/app="+$(this).attr('id');
		            }else if(key==='run'){
		            	document.location.href="/NooSpace/a="+$(this).attr('id');
		            }
		        },
		        items: {
		        	"run":{name:"Run it"},
		        	"about":{name:"About it"},
		        	"tree":{
		        		name:"Open tree", 
		        		disabled: function(){
		        			return false;
		        		}
		        	},
		        	"sep1": "---------",
		            "delete": {name: "Remove from the dock", icon: "delete"}
		        }
		    });
		}

		// Pour afficher le dock application lorsque l'on passe sur le bouton ajouter au dock
		if(document.getElementById('boutonAjouterAuDock')){
			var boutonAjouterAuDock = document.getElementById('boutonAjouterAuDock');

			addEvent(boutonAjouterAuDock, 'mouseover', function(e){
				// On évite les effets de propagation enfant
				var relatedTarget = e.relatedTarget;

				while(relatedTarget != this && relatedTarget.nodeName != 'BODY' && relatedTarget != document){
					
					relatedTarget = relatedTarget.parentNode;
				}

				if(relatedTarget != this){
					animPlus(hauteurFinale, hauteurInitiale);
				}

			});

			$('#formAddApplicationInDock').on('submit', function(e) {
		        e.preventDefault(); // J'empêche le comportement par défaut du navigateur
		 
		        var $this = $(this); // L'objet jQuery du formulaire
		
	            // Envoi de la requête HTTP en mode asynchrone
	            $.ajax({
	                url: $this.attr('action'), // Le nom du fichier indiqué dans le formulaire
	                type: $this.attr('method'), // La méthode indiquée dans le formulaire (get ou post)
	                data: $this.serialize(), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
	                success: function(response) {
	                    $('#applicationsInDock').html(response);

	                    //Pour rendre les nouvelles applications présentes dans le dock draggable par JS
						$('#applicationsInDock .appInDock').draggable({
							containment: '#noospace',
							revert: true
						});

						var boutonAjouterAuDock = $('#boutonAjouterAuDock');
						if(boutonAjouterAuDock.html() === '<i class="glyphicon glyphicon-ok"></i>'){
							boutonAjouterAuDock.html('<i class="glyphicon glyphicon-remove"></i>');
							boutonAjouterAuDock.addClass('btn-danger').removeClass('btn-success');
						}else{
							boutonAjouterAuDock.html('<i class="glyphicon glyphicon-ok"></i>');
							boutonAjouterAuDock.addClass('btn-success').removeClass('btn-danger');
						}

						// On réinitialise la taille des applications en fct de la largeur dock
						initSizeApplications();
	                },
	                error: function(){
	                	var response = {};
	                    response['erreurs'] = '<p>Une erreur système est survenue.</p>';
	                    displayInformationsClient(response);
	                }
	            });
		        
	    	});

		}
		

		// Pour afficher le dock application dans la page web si on ne se trouve pas dans l'arbre de l'application
		addEvent(document, 'mousemove', function(e){
			var url = document.location.href,
				regex = /ManagerOfApplications/;

			if(!regex.test(url)){
				var menuOpenInDock = false;

				$('.context-menu-list').each(function(){
					if($(this).css('display') != 'none'){
						menuOpenInDock = true;
					}
				});

				// On évite les effets de propagation enfant
				var relatedTarget = e.target;

				while(relatedTarget.id != 'formAddApplicationInDock' && relatedTarget.nodeName != 'BODY' && relatedTarget != document){
					relatedTarget = relatedTarget.parentNode;
				}

				if(relatedTarget.id != 'formAddApplicationInDock'){
					if((e.clientY >= hauteurFenetre - 10) && (parseInt(getComputedStyle(overlayDockApplication, null).bottom) == hauteurInitiale)){
					
						animPlus(hauteurFinale, hauteurInitiale);

					}else if(!menuOpenInDock && (e.clientY < hauteurFenetre - 100) && (parseInt(getComputedStyle(overlayDockApplication, null).bottom) == hauteurFinale)){
						animMoins(hauteurInitiale, hauteurFinale);
					}
				}
			}
		});


		// Function pour animer négativement le déplacement du dockApplication
		function animMoins(nouvelleHauteur, hauteurOverlay){
			
			// On modifie la hauteur du div en conséquence
			hauteurOverlay = parseInt(hauteurOverlay) - 20 + 'px';
			
			overlayDockApplication.style.bottom = hauteurOverlay;

			if(parseInt(hauteurOverlay) > nouvelleHauteur){
				setTimeout(function(){
					animMoins(nouvelleHauteur, hauteurOverlay);
				}, 20);
			}
		}


		// Function pour animer positivement le déplacement du dockApplication
		function animPlus(nouvelleHauteur, hauteurOverlay){

			// On modifie la hauteur du div en conséquence
			hauteurOverlay = parseInt(hauteurOverlay) + 20 + 'px';
			
			overlayDockApplication.style.bottom = hauteurOverlay;

			if(parseInt(hauteurOverlay) < nouvelleHauteur){
				setTimeout(function(){
					animPlus(nouvelleHauteur, hauteurOverlay);
				}, 20);
			}
			
		}

		// Pour ajuster automatiquement au dock la taille des icônes des applications
		initSizeApplications();

		/* Version JQuery mais bug dans l'affichage du dock

		var hauteurFenetre = window.innerHeight,
			overlayDockApplication = $('#overlayDockApplication'),
			hauteurInitiale = -100,
			hauteurFinale = 0;

		//Pour rendre les applications présentes dans le dock draggable par JS
		$('#applicationsInDock .appInDock').draggable({
			containment: '#noospace',
			revert: true
		});

		overlayDockApplication.css('bottom', hauteurInitiale + 'px').css('display', 'block');

		// Pour faire apparaître le dock
		$(document).on('mousemove', function(e){
			if(e.target.id != 'boutonAjouterAuDock'){

				if((e.clientY >= hauteurFenetre - 3) && (parseInt(overlayDockApplication.css('bottom')) <= hauteurInitiale+1)){
					
					overlayDockApplication.animate({'bottom':hauteurFinale}, 200);

				}else if((e.clientY < hauteurFenetre - 110) && (parseInt(overlayDockApplication.css('bottom')) >= hauteurFinale-1)){
					
					overlayDockApplication.animate({'bottom':hauteurInitiale}, 200);

				}
			}
		});

		// Pour afficher le dock application lorsque l'on passe sur le bouton ajouter au dock
		if(document.getElementById('boutonAjouterAuDock')){

			$('#boutonAjouterAuDock').on('mouseover', function(){
			
				overlayDockApplication.animate({'bottom':hauteurFinale}, 300);

			});

			$('#formAddApplicationInDock').on('submit', function(e) {
		        e.preventDefault(); // J'empêche le comportement par défaut du navigateur
		 
		        var $this = $(this); // L'objet jQuery du formulaire
		
	            // Envoi de la requête HTTP en mode asynchrone
	            $.ajax({
	                url: $this.attr('action'), // Le nom du fichier indiqué dans le formulaire
	                type: $this.attr('method'), // La méthode indiquée dans le formulaire (get ou post)
	                data: $this.serialize(), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
	                success: function(response) { // Je récupère la réponse du fichier PHP
	                    $('#applicationsInDock').html(response);

	                    //Pour rendre les nouvelles applications présentes dans le dock draggable par JS
						$('#applicationsInDock .appInDock').draggable({
							containment: '#noospace',
							revert: true
						});

						var boutonAjouterAuDock = $('#boutonAjouterAuDock');
						if(boutonAjouterAuDock.attr('value') === 'Ajouter au dock'){
							boutonAjouterAuDock.attr('value', 'Retirer du dock');
						}else{
							boutonAjouterAuDock.attr('value', 'Ajouter au dock');
						}
	                },
	                error: function(){
	                	alert('La requête a échoué !')
	                }
	            });
		        
	    	});

		}
		*/
	}

	// Pour ajuster la largeur des applications au dock
	function initSizeApplications(){
		
		var largeurDock = $('.inHeaderDock').width(),
			largeurTitreDock = $('.titreDock').width(),
			largeurApplicationsInDock = $('#applicationsInDock').width(),
			nombreApplicationsInDock = $('.appInDock').length;

		if((largeurTitreDock+largeurApplicationsInDock) > largeurDock){
			var nouvelleLargeurApplication = Math.round((largeurDock-largeurTitreDock)/(nombreApplicationsInDock+1)-5);

			$('.appInDock').find('.imageApplication').css({
				'width': nouvelleLargeurApplication,
				'height': nouvelleLargeurApplication
			});
		}
	}

	// Une fonction pour gérer les évènements dans tous les navigateurs
	function addEvent(element, event, func){
		
		if(element.attachEvent){
			element.attachEvent('on'+event, func);
		} else{
			element.addEventListener(event, func, false);
		}
	}

});