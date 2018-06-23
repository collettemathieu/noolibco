import displayInformationsClient from './displayInformationsClient.js';

function manageUtilisateur(){

    $('.utilisateur').on('click', function(e){
        // On récupère les attributs de l'utilisateur
        var idUser = $(e.target).attr('idUser'),
            nameUser = $(e.target).attr('nameUser'),
            mailUser = $(e.target).attr('mailUser'),
            dateInscription = $(e.target).attr('dateInscriptionUser'),
            newsletter = parseInt($(e.target).attr('newsletter')),
            dateInscriptionFormated = new Date(dateInscription);

        // On remplit les formulaires
        $('#formSupprimerUtilisateur input').val(idUser);
        var  inputs = $('#formModifierUtilisateur input');

        $(inputs[0]).val(idUser);
        $(inputs[1]).val(mailUser);
        $(inputs[2]).val(nameUser);
        $(inputs[3]).val(mailUser);
        $(inputs[4]).val(dateInscriptionFormated.getDate() + '-'+dateInscriptionFormated.getMonth() + '-'+dateInscriptionFormated.getFullYear());
        if(newsletter === 1){
            $(inputs[5]).attr('checked', 'checked');
        }else{
            $(inputs[5]).removeAttr('checked');
        }
        
        // On affiche la fenêtre modale
        $('#afficherProfilUtilisateur').modal('show');
    });
}

// On gère la modification d'un utilisateur
$('#formModifierUtilisateur').on('submit', function(e){
    e.preventDefault();
    var formData = new FormData(e.target),
        btn = $(this).find('button');
    btn.button('loading');
    // Envoi de la requête HTTP en mode asynchrone
    $.ajax({
        url: '/ForAdminOnly/Utilisateurs/ModifierUtilisateur',
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
            $('#afficherProfilUtilisateur').modal('hide');
            if(response['reussites']){
                // On met à jour la liste des utilisateurs
                $('#listeUtilisateurs').empty().append(response['listeUtilisateurs']);
                manageUtilisateur();
            }
        },
        error: function(){
            btn.button('reset');
            var response = {
                'erreurs': '<p>Une erreur système est apparue.</p>'
            };
            displayInformationsClient(response);
            $('#afficherProfilUtilisateur').modal('hide');
        }
    });
});


// On gère la suppression d'un utilisateur
$('#formSupprimerUtilisateur').on('submit', function(e){
    e.preventDefault();
    var formData = new FormData(e.target),
        btn = $(this).find('button');
    btn.button('loading');
    // Envoi de la requête HTTP en mode asynchrone
    $.ajax({
        url: '/ForAdminOnly/Utilisateurs/SupprimerUtilisateur',
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
            $('#afficherProfilUtilisateur').modal('hide');
            if(response['reussites']){
                // On met à jour la liste des utilisateurs
                $('#listeUtilisateurs').empty().append(response['listeUtilisateurs']);
                manageUtilisateur();
            }
        },
        error: function(){
            btn.button('reset');
            var response = {
                'erreurs': '<p>Une erreur système est apparue.</p>'
            };
            displayInformationsClient(response);
            $('#afficherProfilUtilisateur').modal('hide');
        }
    });
});

manageUtilisateur();