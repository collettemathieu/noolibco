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
            	try{
            		var response = JSON.parse(response);
            		if(!response['erreurs']){
	            		var response = {};
                		response['erreurs'] = '<p>A system error has occurred.</p>';
	            	}
	            	displayInformationsClient(response);  
            	}
            	catch(e){
            		$('#helperApplication').find('.modal-body').html(response);
            		$('#helperApplication').modal('show');
            	}
				
            },
            error: function(){
            	var response = {};
                response['erreurs'] = '<p>A system error has occurred.</p>';
                displayInformationsClient(response);
            }
        });
	});
})();