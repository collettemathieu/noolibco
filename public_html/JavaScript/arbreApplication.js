(function(){
    if($('#containerTreeApplication').length != 0){
    	// Pour ajouter une tâche
    	$('#boutonAjouterTache').click(function(){
    		// Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: '/HandleApplication/FormTache',
                type:'POST',
                data:{
                    idApp:parseInt($('#containerTreeApplication').attr('idApp'))
                },
                async: true,
                cache: true,
                success: function(response) {
                	
    				$('#contenuForm').html(response);

    				$('form').on('submit', function(e){
    					e.preventDefault();
    					var formData = new FormData(e.target);
    					$(this).find('button').button('loading');
    					validerFormulaireApplicationByAjax(formData, '/HandleApplication/ValidFormTache');
    				});

                    manageTypeDonneeUtilisateur();
                    initTypeDonneeUtilisateur();
                },
                error: function(){
                	var response = {
                		'erreurs': '<p>A system error has occurred.</p>'
                	};
    	            displayInformationsClient(response);
                }
            });
    	});

        // Pour initialiser les nouveaux types de données utilisateurs
        function initTypeDonneeUtilisateur(){
            $('.selectUniteDonneeUtilisateur').each(function(){
                if($(this).parent().find('select:first').val() != 'Input Text'){
                    $(this).attr('disabled', 'disabled');
                }
            });

            if($('.selectTypeDonneeUtilisateur').length > 1){
                $('#deleteTypeDonneeUtilisateur').removeAttr('disabled');
            }

            $('.selectTypeDonneeUtilisateur').change(function(){
                if($(this).val() != 'Input Text'){
                    $(this).parent().find('select:last').attr('disabled', 'disabled');
                }else{
                    $(this).parent().find('select:last').removeAttr('disabled');
                }
            });
        }

        // Pour ajouter un nouveau type de donnée utilisateur à une tâche ou en supprimer un
        function manageTypeDonneeUtilisateur(){
            $('#addNewTypeDonneeUtilisateur').click(function(e){
                e.preventDefault();
                var divNode = $(this).parent().find('div:last'),
                    num_data = $(this).parent().find('div').length,
                    divClone = divNode.clone();
                divClone.find('select:first').attr('name', 'typeDonneeUtilisateur'+num_data).val('');
                divClone.find('select:last').attr('name', 'uniteDonneeUtilisateur'+num_data).val('').removeAttr('disabled');
                divClone.find('input').attr('name', 'description'+num_data).val('');
                divClone.insertAfter(divNode);
                if(num_data == 1){
                    $('#deleteTypeDonneeUtilisateur').removeAttr('disabled');
                }
                initTypeDonneeUtilisateur();
            });

            // Pour supprimer un type de paramètre à la tâche - le dernier n'est pas supprimé
            $('#deleteTypeDonneeUtilisateur').click(function(e){
                e.preventDefault();
                var divNode = $(this).parent().find('div');
                if(divNode.length > 1){
                    divNode[divNode.length-1].remove();
                    if(divNode.length == 2){
                        $(this).attr('disabled', 'disabled');
                    }
                }
            });
        }


            
    }
})();


   