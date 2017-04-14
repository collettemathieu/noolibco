// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Application ApplicationsManager									  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Application ApplicationsManager
 * @access: public
 * @version: 1
 */
application.factory('dataService', function(){
	return{
	  initDonneeUtilisateur : function(donneeUtilisateur, drop, nouvellePositionElementX, nouvellePositionElementY){
	  	donneeUtilisateur.children('img').on({
				click: function(){
					// Pour éviter le click durant le drag
					if($(this).hasClass('noClick')){
						$(this).removeClass('noClick');
					}else{
						forShowingData(donneeUtilisateur);
					}
					
				}
			});
	  		//console.log(drop);
	    	donneeUtilisateur.appendTo(drop);
	  		donneeUtilisateur.css('position','absolute').css('top', nouvellePositionElementY+'px').css('left', nouvellePositionElementX+'px');

			// On affiche les info-bulles
			donneeUtilisateur.popover({placement:'bottom', trigger:'hover'});

	  		donneeUtilisateur.contextMenu({
		    	selector: 'img',
		        callback: function(key, options) {
		            if(key === 'delete'){
		            	$(this).parent().remove();
		            }
		        },
		        autoHide: true,
		        items: {
		            "delete": {
		            	name: "Remove from the NooSpace", 
		            	icon: "delete"
		            }
		        }
		    });
	 		donneeUtilisateur.draggable({
				revert: false,
				containment: '#noospace',
				snap: '.dataBox',
				start: function(){
						$(this).children('img').addClass('noClick');
						$(this).addClass('draggableElemnt');
					},
				drag: function(event, ui){
						$(this).popover('hide');
					}
	      	});		
	  },
	  // Pour initialiser la mule
			   initMule:function(nomTache, listTypeDonnee, formGroup){
			   	$('#formMule').find('button:first').click(function(e){
                e.preventDefault();
                var divNode = $(this).parent().find('.task:last'),
                    num_data = $(this).parent().find('.task').length,
                    divClone = divNode.clone();
                // On met à jour le nom de la tâche
                divClone.find('select').attr('name', 'tache'+num_data);
                // On insert la nouvelle tâche
                divClone.insertAfter(divNode);
                if(num_data == 1){
                	$('#formMule').find('button:odd').removeAttr('disabled');
                }
                
                 this.initMule(nomFirstTache, listTypeDonnee);
                // Pour contrôler le changement des select
																	$('#formMule').find('.listeTache').change(function(){
																		this.initMule($(this).val(), listTypeDonnee, $(this).parent().next());
																	});
            });

            // Pour supprimer un type de paramètre à la tâche - le dernier n'est pas supprimé
            $('#formMule').find('button:odd').click(function(e){
                e.preventDefault();
                var divNode = $(this).parent().find('.task');
                if(divNode.length > 1){
                    divNode[divNode.length-1].remove();
                    if(divNode.length == 2){
                    	$(this).attr('disabled', 'disabled');
                    }
                }
            });
           }
							}
			});

