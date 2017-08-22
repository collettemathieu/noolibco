(function(){
	if($('.caseCours').length != 0){
		displayCours('.caseCours');

		function displayCours(elems){
			var elems = $(elems),
				marginBottom = parseInt($(elems[0]).css('marginBottom'));

				var parentWidth = $(elems[0]).parent().width()-40,
					sonWidth = $(elems[0]).outerWidth(),
					nbreCase = Math.floor(parentWidth/sonWidth);

			for(var i=nbreCase, c=elems.length; i<c; ++i){
				var elem = $(elems[i]),
					positionArticle = elem.offset(),
					topArticle = $(elems[i-nbreCase]),
					positionTopArticle = topArticle.offset(),
					heightTopArticle = topArticle.outerHeight(),
					difference = positionArticle.top - (positionTopArticle.top+heightTopArticle);
				
				if(difference>marginBottom+2){
					elem.offset({
						top: positionArticle.top - difference + marginBottom,
						left: positionArticle.left
					});
				}else if(difference<0){
					elem.offset({
						top: positionTopArticle.top + heightTopArticle + marginBottom,
						left: positionArticle.left
					});
				}
			}
		}

		// Pour afficher les cours des cours globaux
		$('.coursGlobal').on('click', function(e){
			e.preventDefault();
			var relatedTarget = e.target,
				showAllCours = $('#showAllCours');
			showAllCours.find('h2').empty();
			showAllCours.find('.allCours').empty();
			showAllCours.modal('show');
			$('.loader').removeClass('hidden');
			while (relatedTarget.nodeName != 'A') {
				relatedTarget = relatedTarget.parentNode;
			}
			var id = parseInt($(relatedTarget).attr('idCoursGlobal')),
				formData = new FormData();
			formData.append('idCoursGlobal', id);
			// Envoi de la requête HTTP en mode asynchrone
	        $.ajax({
	            url: '/Cours/CoursFromCoursGlobal',
	            type: 'POST',
	            data: formData,
	            async: true,
	            cache: false,
	            contentType: false,
	            processData: false,
	            success: function(response) {
	                response = JSON.parse(response);
                	 $('.loader').addClass('hidden');
                	showAllCours.find('h2').append(response['titreCours']);
	                showAllCours.find('.allCours').append(response['coursAAfficher']);
	                setTimeout(function(){
	                	displayCours('#showAllCours .caseCours');
	                }, 300);
	                
	            },
	            error: function(){
	                var response = {
	                    'erreurs': '<p>Une erreur système est apparue.</p>'
	                };
	                displayInformationsClient(response);
	            }
	        });
		});

		// Pour afficher les infos bulles
		$('.infoBulleBottom').tooltip({
			delay: {
				show: 800,
				hide: 100
			}, 
			placement:'bottom', 
			trigger:'hover'
		});
	}
})();