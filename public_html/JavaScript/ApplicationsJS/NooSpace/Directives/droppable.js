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


application.directive('droppable',['applicationService','dataService',function(applicationService,dataService){
	return{
	restrict :'A',
	link : function(scope,element,attrs){
		applicationService.verifApplication();
		element.droppable({
			accept: ".ui-draggable",
			drop: function(event, ui){

				var pos = element.offset();
					
		    	var positionSourisX = event.clientX,
			      	positionSourisY = event.clientY,
			      	positionElement = angular.element(ui.draggable).offset(),
			      	positionSourisInAppX = positionSourisX - positionElement.left,
			      	positionSourisInAppY = positionSourisY - positionElement.top,
			      	nouvellePositionElementX = positionSourisX - pos.left - positionSourisInAppX,
			      	nouvellePositionElementY = positionSourisY - pos.top - positionSourisInAppY,
			      	largeurGestionnaireDonnee = parseInt($('#overlayGestionnaireDonnees').css('width'));


		      	// Pour insérer une application dans la noospace
		      	if(ui.draggable.parent().attr('id') === 'applicationsInDock' || angular.element(ui.draggable).hasClass('runIt')){

		      		applicationService.deployApplication(angular.element(ui.draggable), $(this), nouvellePositionElementX, nouvellePositionElementY);
		      	//applicationService.deployApplication();

				// Pour insérer une nouvelle donnée dans la noospace
			    }else if(angular.element(ui.draggable).parent().attr('id') === 'inListeDonneesUser' && positionSourisX > largeurGestionnaireDonnee){

			    	// Pour n'insérer que les nouvelles données qui ne sont pas positionées dans une dataBox, car c'est le droppable de l'application qui s'en charge
			    	var isIn = false;
			    	$('#noospace .dataBox').each(function(index){
			      		var p = $(this).offset();
						if(positionSourisX >= p.left && positionSourisX <= p.left +64 && positionSourisY >= p.top && positionSourisY <= p.top + 64){
							isIn = true;
						}
					});

			    	if(!isIn){
			    		dataService.initDonneeUtilisateur(angular.element(ui.draggable).clone(), $(this), positionSourisX - pos.left -34, positionSourisY - pos.top -34);
			    	}
					
			    // Pour retirer une donnée d'une dataBox de l'application
			    }else if(ui.draggable.parent().hasClass('dataBox ui-droppable')){
			    	
			    	$(this).append(angular.element(ui.draggable));
					angular.element(ui.draggable).css('position','absolute').css('top', nouvellePositionElementY+'px').css('left', nouvellePositionElementX+'px');
			    	
			    // Pour ajouter une donnée locale à la noospace
			    }else if(angular.element(ui.draggable).hasClass('newTempData')){
			    	ui.draggable.removeClass('newTempData');

			    	dataService.initDonneeUtilisateur(ui.draggable, $(this), nouvellePositionElementX, nouvellePositionElementY);
			    }
			}
		});
		
	}
}
	}]);

			

