(function(){

	// Pour démarrer le fil d'Ariane
	$('#menuFilAriane').jBreadCrumb();

	// Pour afficher les popOver du header
	$('nav img').popover({placement:'bottom', trigger:'hover'});
	$('.image-upload img').popover({placement:'bottom', trigger:'hover'});

	// Pour afficher les infos bulles
	$('.infoBulle').tooltip({placement:'bottom', trigger:'hover'});
	$('.infoBulleRight').tooltip({placement:'right', trigger:'hover'});
	// Pour afficher les infos bulles Top/Bottom du menu général
	$('.infoBulleGeneralMenuTop').tooltip({
		delay: {
			show: 800,
			hide: 100
		}, 
		placement:'top', 
		trigger:'hover'
	});
	// Pour afficher les infos bulles Top/Bottom du menu général
	$('.infoBulleGeneralMenuBottom').tooltip({
		delay: {
			show: 800,
			hide: 100
		}, 
		placement:'bottom', 
		trigger:'hover'
	});
	// Pour afficher les infos bulles dans le gestionnaire de données
	$('.infoBulleDataManager').tooltip({
		delay: {
			show: 800,
			hide: 100
		}, 
		placement:'right', 
		trigger:'hover'
	});
	
	// Pour activer les carrousels
	$('.carousel').carousel('pause');

	// Pour modifier le design des inputs file
	$('[type="file"]').filestyle({
		iconName: 'glyphicon glyphicon-download-alt'
	});

	// Pour modifier le design des select
	$('select').selectpicker({
	});

	// Pour afficher l'aide à l'utilisateur
	$('#helperButton').click(function(e){
		e.preventDefault();
		var currentUrl = document.location.href;
		currentUrl = currentUrl.substring(currentUrl.indexOf('/', 8)+1);
		if(currentUrl.length == 0){
			currentUrl = 'Frontend/';
		}
		currentUrl = '/Helper/'+currentUrl.substring(0,currentUrl.indexOf('/'));

		$.ajax({
            url: currentUrl,
            type: 'POST',
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
            	if(isJson(response)){
            		var response = JSON.parse(response);
            		if(!response['erreurs']){
	            		var response = {};
                		response['erreurs'] = '<p>A system error has occurred.</p>';
	            	}
	            	displayInformationsClient(response);  
            	}else{
            		$('#helperApplication').find('.modal-body').html(response);
            		$('#helperApplication').modal('show');

            		// On contrôle le formulaire de contact
            		$('#formContact').on('submit', function(e){
		            e.preventDefault();
		            var formData = new FormData(e.target),
		                btn = $(this).find('button');
		            btn.button('loading');
		            // Envoi de la requête HTTP en mode asynchrone
		            $.ajax({
		                url: '/Helper/Contact',
		                type: 'POST',
		                data: formData,
		                async: true,
		                cache: false,
		                contentType: false,
		                processData: false,
		                success: function(response) {
		                    btn.button('reset');
		                    response = JSON.parse(response);
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
            	}
            },
            error: function(){
            	var response = {};
                response['erreurs'] = '<p>A system error has occurred.</p>';
                displayInformationsClient(response);
            }
        });
	});

	/* Fonctions */
	function isJson(text){
		try{
			JSON.parse(text);
			return true;
		}
		catch(e){
			return false;
		}
	}
})();