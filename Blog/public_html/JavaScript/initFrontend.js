(function(){

	// Info bulle
	var largeur = screen.width; // On supprime pour les smartphones
	if(largeur >= 768){
		$('.infoBulleBottom').tooltip({
			delay: {
				show: 600,
				hide: 100
			}, 
			placement:'bottom', 
			trigger:'hover'
		});
	}

	// Pour le carrousel des actualités
	numeroActualite = 0;
	$('.rightArrow').on('click', function(e){
		numeroActualite += 1;
		getActualite(1);
	});
	$('.leftArrow').on('click', function(e){
		numeroActualite -= 1;
		getActualite(-1);
	});

	function getActualite(side){
		var formData = new FormData();
		formData.append('numeroActualite', numeroActualite);

		// Envoi de la requête HTTP en mode asynchrone
		$.ajax({
            url: '/GetActualite/',
            type: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                var response = JSON.parse(response);
                if(response['erreurs']){
                	displayInformationsClient(response);
                	if(side < 0){
                		numeroActualite += 1;
                	}else{
                		numeroActualite -= 1;
                	}
                }else{
                	if(numeroActualite <= 0){
                		$('.leftArrow').addClass('hidden');
                	}else if(numeroActualite > 0 && numeroActualite < response['nbreActualites']-1){
                		$('.leftArrow').removeClass('hidden');
                		$('.rightArrow').removeClass('hidden');
                	}else if(numeroActualite >= response['nbreActualites']-1){
                		$('.rightArrow').addClass('hidden');
                	}
                	var caseActualite = $('.caseActualite');
                	$('.actualite').fadeOut('slow', function(){
                		caseActualite.find('h2').empty().append(response['titre']);
                		caseActualite.find('p').empty().append(response['texte']);
                		caseActualite.find('a').attr('href',response['lien']);
                		$(this).css('background-image', 'url('+response['urlBackground']+')');
                	});
                	$('.actualite').fadeIn('slow');
                }
            },
            error: function(){
                var response = {
                    'erreurs': '<p>Une erreur système est apparue.</p>'
                };
                displayInformationsClient(response);
            }
        });
	}

	// Pour faire apparaître l'édito
	$('.edito').on('click', function(e){
		var target = $(e.target),
			edito = $('.maxWidthEditorial'),
			heightEdito = edito[0].scrollHeight;
		// On déplie l'édito
		edito.animate({'height':heightEdito+'px'}, 1000, function(){
			if(target.attr('class') != 'edito'){
				target = target.parent();
			}
			// On cache le bouton
			target.fadeOut('slow');
		});
	});
	
	// Pour le carrousel des éditos
	numeroEdito = 0;
	$('.rightEditoArrow').on('click', function(e){
		numeroEdito += 1;
		getEdito(1);
	});
	$('.leftEditoArrow').on('click', function(e){
		numeroEdito -= 1;
		getEdito(-1);
	});

	function getEdito(side){
		var formData = new FormData();
		formData.append('numeroEdito', numeroEdito);

		// Envoi de la requête HTTP en mode asynchrone
		$.ajax({
            url: '/GetEdito/',
            type: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
            	var response = JSON.parse(response);
                
                if(response['erreurs']){
                	displayInformationsClient(response);
                	if(side < 0){
                		numeroEdito += 1;
                	}else{
                		numeroEdito -= 1;
                	}
                }else{
                	if(numeroEdito <= 0){
                		$('.leftEditoArrow').addClass('hidden');
                		$('.rightEditoArrow').removeClass('hidden');
                	}else if(numeroEdito > 0 && numeroEdito < response['nbreEditos']-1){
                		$('.leftEditoArrow').removeClass('hidden');
                		$('.rightEditoArrow').removeClass('hidden');
                	}else if(numeroEdito >= response['nbreEditos']-1){
                		$('.leftEditoArrow').removeClass('hidden');
                		$('.rightEditoArrow').addClass('hidden');
                	}

                	$('#number').empty().append(numeroEdito+1);
                	$('html,body').animate({scrollTop: $("#beginEdito").offset().top}, 1000);
                	
                	var blockquote = $('blockquote');
            		blockquote.find('h3').empty().append('Editorial du '+response['date']);
            		blockquote.find('p').empty().append(response['texte']);

            		var edito = $('.maxWidthEditorial'),
                		blockquote = $('blockquote'),
						heightBlockquote = blockquote.outerHeight();
					// On ajuste la hauteur de l'édito
					edito.animate({'height':heightBlockquote+120+'px'}, 1000);
                }
            },
            error: function(){
                var response = {
                    'erreurs': '<p>Une erreur système est apparue.</p>'
                };
                displayInformationsClient(response);
            }
        });
	}


	// Pour afficher les statistiques avec des circles progress
	// InView plugIn
    $('.statistics').one('inview', function(event, isInView) {
		if (isInView) {
			// Envoi de la requête HTTP en mode asynchrone
			$.ajax({
	            url: '/Statistiques/',
	            type: 'POST',
	            async: true,
	            cache: false,
	            contentType: false,
	            processData: false,
	            success: function(response) {
	                response = JSON.parse(response);
	                if(response['nbreUsers']){
	                	var valeurMax = Math.max(response['nbreUsers'], response['nbreCours'], response['nbreCommentaires'], response['nbreArticles'])+1,
	                		statUtilisateurs  = $('#statUtilisateurs'),
	                		statCours = $('#statCours'),
	                		statCommentaires = $('#statCommentaires'),
	                		statArticles = $('#statArticles');

	                	statUtilisateurs.circleProgress({
							value: 1,
							startAngle: -Math.PI / 4 * 3,
							size: 150,
							fill: {
								color: '#F7BE81'
							},
							lineCap: 'round'
						}).on('circle-animation-progress', function(event, progress, stepValue) {
						  $(this).find('strong').text(String(Math.round(stepValue.toFixed(1)*valeurMax)));
						});
						setTimeout(function() { 
							statUtilisateurs.circleProgress('value', response['nbreUsers']/valeurMax); 
						}, 1000);
						
						statCours.circleProgress({
							value: 1,
							startAngle: -Math.PI / 4 * 3,
							size: 150,
							fill: {
								color: '#0681c4'
							},
							lineCap: 'round'
						}).on('circle-animation-progress', function(event, progress, stepValue) {
						  $(this).find('strong').text(String(Math.round(stepValue.toFixed(1)*valeurMax)));
						});
						setTimeout(function() { 
							statCours.circleProgress('value', response['nbreCours']/valeurMax); 
						}, 1000);

						statCommentaires.circleProgress({
						  	value: 1,
							startAngle: -Math.PI / 4 * 3,
							size: 150,
							fill: {
								color: '#4ac5f8'
							},
							lineCap: 'round'
						}).on('circle-animation-progress', function(event, progress, stepValue) {
						 	$(this).find('strong').text(String(Math.round(stepValue.toFixed(1)*valeurMax)));
						});
						setTimeout(function() { 
							statCommentaires.circleProgress('value', response['nbreCommentaires']/valeurMax); 
						}, 1000);

						statArticles.circleProgress({
						  value: 1,
							startAngle: -Math.PI / 4 * 3,
							size: 150,
							fill: {
								color: '#F78181'
							},
							lineCap: 'round'
						}).on('circle-animation-progress', function(event, progress, stepValue) {
						  $(this).find('strong').text(String(Math.round(stepValue.toFixed(1)*valeurMax)));
						});
						setTimeout(function() { 
							statArticles.circleProgress('value', response['nbreArticles']/valeurMax); 
						}, 1000);
	                }else{
	                	displayInformationsClient(response);
	                }
	            },
	            error: function(){
	                var response = {
	                    'erreurs': '<p>Une erreur système est apparue.</p>'
	                };
	                displayInformationsClient(response);
	            }
	        });
		} else {
		// element has gone out of viewport
		}
	});
})();