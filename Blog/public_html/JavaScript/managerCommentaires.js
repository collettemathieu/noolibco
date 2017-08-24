(function(){
	if( $('#formAddComment').length !=0){
	    // On gère l'ajout d'un commentaire
	    $('#formAddComment').on('submit', function(e){
	        e.preventDefault();
	        var formData = new FormData(e.target),
	            btn = $(this).find('button');
	        btn.button('loading');
	        // Envoi de la requête HTTP en mode asynchrone
	        $.ajax({
	            url: '/Commentaire/AjouterCommentaire',
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
	                    'erreurs': '<p>Une erreur système est apparue.</p>'
	                };
	                displayInformationsClient(response);
	            }
	        });
	    });
	}
})();


   