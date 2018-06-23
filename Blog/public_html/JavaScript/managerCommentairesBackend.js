import displayInformationsClient from './displayInformationsClient.js';

function manageCommentaire(){
	$('.commentaire').on('click', function(e){
        
        // On récupère les attributs de l'utilisateur
        var idComment = $(e.target).attr('idComment'),
			texteComment = $(e.target).attr('texteComment'),
            titreArticle = $(e.target).attr('titreArticle'),
            titreCours = $(e.target).attr('titreCours'),
			auteurComment = $(e.target).attr('auteurComment'),
            enLigneComment = parseInt($(e.target).attr('enLigneComment'));

        // On remplit les formulaires
		$('#formAccepterCommentaire input').val(idComment);
        $('#formSupprimerCommentaire input').val(idComment);
        if(titreArticle != 'undefined'){
            $('.alert h3').empty().append(titreArticle);
        }else{
            $('.alert h3').empty().append(titreCours);
        }
        $('.alert h4').empty().append('Commentaire de '+auteurComment);
        $('.alert p').empty().append(texteComment);

        if(enLigneComment === 1){
            $('#formAccepterCommentaire').addClass('hidden');
        }else{
            $('#formAccepterCommentaire').removeClass('hidden');
        }

        // On affiche la fenêtre modale
        $('#afficherCommentaire').modal('show');
    });
}

// On gère la suppression d'un commentaire
$('#formSupprimerCommentaire').on('submit', function(e){
    e.preventDefault();
    var formData = new FormData(e.target),
        btn = $(this).find('button');
    btn.button('loading');
    // Envoi de la requête HTTP en mode asynchrone
    $.ajax({
        url: '/ForAdminOnly/Commentaires/SupprimerCommentaire',
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
            $('#afficherCommentaire').modal('hide');
            if(response['reussites']){
                // On met à jour la liste des commentaires
                $('.listeCommentairesEnAttente').empty().append(response['listeCommentairesEnAttente']);
                $('.listeCommentairesEnAttenteValidationAuteur').empty().append(response['listeCommentairesEnAttenteValidationAuteur']);
                $('.listeCommentairesEnLigne').empty().append(response['listeCommentairesEnLigne']);
                manageCommentaire();
            }
        },
        error: function(){
            btn.button('reset');
            var response = {
                'erreurs': '<p>Une erreur système est apparue.</p>'
            };
            displayInformationsClient(response);
            $('#afficherCommentaire').modal('hide');
        }
    });
});

// On gère l'acceptation d'un commentaire
$('#formAccepterCommentaire').on('submit', function(e){
    e.preventDefault();
    var formData = new FormData(e.target),
        btn = $(this).find('button');
    btn.button('loading');
    // Envoi de la requête HTTP en mode asynchrone
    $.ajax({
        url: '/ForAdminOnly/Commentaires/ValidationAdminCommentaire',
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
            $('#afficherCommentaire').modal('hide');
            if(response['reussites']){
                // On met à jour la liste des commentaires
                $('.listeCommentairesEnAttente').empty().append(response['listeCommentairesEnAttente']);
                $('.listeCommentairesEnAttenteValidationAuteur').empty().append(response['listeCommentairesEnAttenteValidationAuteur']);
                $('.listeCommentairesEnLigne').empty().append(response['listeCommentairesEnLigne']);
                manageCommentaire();
            }
        },
        error: function(){
            btn.button('reset');
            var response = {
                'erreurs': '<p>Une erreur système est apparue.</p>'
            };
            displayInformationsClient(response);
            $('#afficherCommentaire').modal('hide');
        }
    });
});

manageCommentaire();



   