import dropZoneFunc from './dropZoneFunction.js';
if($('#dropzoneImageCours').length != 0){
    dropZoneFunc('imageCours', '/ForAdminOnly/Cours/ModifierImageCours', '.png,.PNG,.jpg,.jpeg,.JPG,.JPEG');

    
    // Permet de sauvegarder le cours entier avec CMD+S
    var body = document.querySelector('body');
    body.onkeydown = function (e) {
        if(e.metaKey && e.keyCode == 'S'.charCodeAt(0)){
            e.preventDefault();
            var formData = new FormData(),
                texteCours = $('#nouveauTexte').val(),
                referencesCours = $('#referencesCours').val(),
                idCours = $('#idCours').val();

            formData.append('idCours', idCours);
            formData.append('nouveauTexte', texteCours);
            formData.append('nouvellesReferences', referencesCours);

            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: '/ForAdminOnly/Cours/SaveJSCours',
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    try{
                        response = JSON.parse(response);
                        displayInformationsClient(response);
                    }
                    catch(e){
                        var response = {
                            'erreurs': '<p>Une erreur système est apparue.</p>'
                        };
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
        }

    };


    // Permet d'effectuer une requête vers CrossRef
    $('#searchPublicationForm').on('submit', function(e){
        e.preventDefault();
        var formData = new FormData(e.target),
            btn = $(this).find('button');
        btn.button('loading');
        // Envoi de la requête HTTP en mode asynchrone
        $.ajax({
            url: '/ForAdminOnly/Cours/SeekReference',
            type: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                btn.button('reset');
                try{
                    response = JSON.parse(response);
                    if(response['erreurs']){
                        displayInformationsClient(response);
                    }else if(response['reussites']){
                        var results = response['reussites'],
                            inputFormPublication = $('#addPublicationForm').find('input');
                        // On change d'onglet
                        $('#addReference').find('.nav-pills a[href="#manuel"]').tab('show');
                        
                        // On remplit le tableau du formulaire des publications
                        inputFormPublication[0].value = results['titleArticle'];
                        inputFormPublication[1].value = results['listeAuteurs'].substr(0,results['listeAuteurs'].length-2);
                        inputFormPublication[2].value = results['yearPublication'];
                        inputFormPublication[3].value = results['titleJournal'];
                        inputFormPublication[4].value = results['urlRessource'];
                    }
                }
                catch(e){
                    var response = {
                        'erreurs': '<p>Une erreur système est apparue.</p>'
                    };
                    displayInformationsClient(response);
                }
            },
            error: function(){
                btn.button('reset');
                var response = {
                    'erreurs': '<p>Une erreur système est apparue.</p>'
                };
                displayInformationsClient(response);
                $('#addReference').modal('hide');
            }
        });
    });

	// Permet d'ajouter une référence au champ texte de l'article
    $('#addPublicationForm').on('submit', function(e){
        e.preventDefault();
        var referencesCours = $('#referencesCours').val(),
        	count = 0,
        	pos = referencesCours.indexOf("[");

		while ( pos != -1 ) {
		   count++;
		   pos = referencesCours.indexOf( "[",pos + 1 );
		}

		var newReference = '['+(count+1)+'] '+$('#auteursPubli').val()+'. '+$('#titrePubli').val()+'. '+$('#journalPubli').val()+'. '+$('#anneePubli').val()+'. '+'{L}'+$('#urlPubli').val()+'{/L}\n';
		referencesCours = referencesCours + newReference;
		$('#referencesCours').val(referencesCours);
		$('#addReference').modal('hide');
    });



    // Pour gérer l'affichage du gestionnaire de données
    var overlayGestionnaireDonnees = $('#overlayGestionnaireDonnees'),
        decalageInitiale = -parseInt(overlayGestionnaireDonnees.css('width'));

    overlayGestionnaireDonnees.css('display', 'inline-block').css('left', decalageInitiale+'px');
    $('#boutonShowGestionnaireDonnees').on('click', function(){
        var boutonShowGestionnaireDonnees = $('#boutonShowGestionnaireDonnees'),
            overlayGestionnaireDonnees = $('#overlayGestionnaireDonnees'),
            decalageInitiale = -parseInt(overlayGestionnaireDonnees.css('width')),
            decalageFinale = 0;

        // Pour fermer
        if(parseInt(overlayGestionnaireDonnees.css('left')) >= decalageFinale){

            var tailleManager = parseInt($('#overlayGestionnaireDonnees').css('width')),
                positionLeftBouton = parseInt($('#boutonShowGestionnaireDonnees').css('left'));
            if(tailleManager > 500){
                $('#overlayGestionnaireDonnees').animate({'width': tailleManager-400}, 1500);
                $('#boutonShowGestionnaireDonnees').animate({'left':positionLeftBouton-400}, 1500);
            }else{
                // On anime le déplacement du div du gestionnaire de données
                overlayGestionnaireDonnees.animate({'left':decalageInitiale}, 1500);
                boutonShowGestionnaireDonnees.animate({'left':decalageFinale}, 1500);
            }
        }else{ // Pour ouvrir
            // Envoi de la requête HTTP en mode asynchrone
            if($('#inSectionGestionnaireDonnees').empty()){
                $.ajax({
                    url: '/ForAdminOnly/Medias/PickUpMedias',
                    type: 'POST',
                    success: function(response) {
                        try{
                            $('.image-dataManager-waiter').addClass('hidden');
                            response = JSON.parse(response);
                            $('#inSectionGestionnaireDonnees').append(response['listeMedias']);

                            // Pour la copie dans le presse papier
                            $('.media').on('click', function(e){
                                var text = $(e.target).next();
                                text.removeClass('hidden');
                                text.select();
                                if(document.execCommand( 'copy' )){
                                    var response = {
                                        'reussites': '<p>L\'url a été copiée dans le presse papier</p>'
                                    };
                                }else{
                                    var response = {
                                        'reussites': '<p>L\'url n\'a pas été copiée dans le presse papier</p>'
                                    };
                                }
                                text.addClass('hidden');
                                displayInformationsClient(response);
                            });
                        }
                        catch(e){
                            var response = {};
                            response['erreurs'] = '<p>Une erreur système est apparue.</p>';
                            displayInformationsClient(response);
                        }
                    },
                    error: function(){
                        var response = {};
                        response['erreurs'] = '<p>Une erreur système est apparue.</p>';
                        displayInformationsClient(response);
                    }
                });
            }

            // On anime le déplacement du div du gestionnaire de données
            overlayGestionnaireDonnees.animate({'left':decalageFinale}, 1500);
            boutonShowGestionnaireDonnees.animate({'left':-decalageInitiale}, 1500);
        }
    });      
}

   