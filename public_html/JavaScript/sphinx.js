(function(){	
      
      // On récupère le formulaire avec les mots-clés recherchés
	$('#formRechercheApplication').on('submit', function(e){
		e.preventDefault();
		var formData = new FormData(e.target),
                  btn = $(this).find('button');
            btn.button('loading');
		// On charge les résultats par Ajax
		$.ajax({
                  url: '/Sphinx/SearchApplication',
                  type: 'POST',
                  data: formData,
                  async: true,
                  cache: false,
                  contentType: false,
                  processData: false,
                  success: function(response) {
                  	btn.button('reset');
                  	$('#informationRecherche').html(response);
                  	
                  },
                  error: function(){
                        var response = {};
                        response['erreurs'] = '<p>Une erreur système est survenue.</p>';
                        displayInformationsClient(response);
                        btn.button('reset');
                  }
            });
	});	
})();