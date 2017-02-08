function showData(parametres){
	
	var data = $('#sectionGestionnaireDonnees .donneeUser');

	// On affiche les info-bulles
	$('.donneeUser').each(function(index, e){
		var parent = $(e).parent();
		if(!parent.hasClass('dashedBorder')){
			$(e).popover({placement:'bottom', trigger:'hover'});
		}
	});

	
	
	//Pour rendre les données présentes dans le gestionnaire draggable par JS
	data.draggable({
		snap: '.dataBox',
		revert: true,
		scroll: false,
		helper: function(){
                $copy = $(this).clone();
                return $copy;},
		drag: function(event, ui){
			$(this).popover('hide');
		}
	});

	if(typeof(parametres) != 'undefined'){
		// On met à jour la barre d'espace disponible
	    $('#progressBarEspaceDisponibleData').attr('value', parametres['tailleMoDonneesUtilisateur']);
	    $('#progressBarEspaceDisponibleData').attr('max', parametres['tailleMaxDonneesUtilisateur']);
	    $('#progressionPourcent').html(parametres['progressionPourcent']+ '%');
	}
	
    // On applique l'évènement du click pour afficher les données
	data.on('click', function(){
		forShowingData($(this));
	});

	forSavingData();
	forDeletingData();
}