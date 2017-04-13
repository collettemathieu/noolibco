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
			//accept: ".ui-draggable",
			drop: function(event, ui){

				var pos = element.offset();
					
		    	var positionSourisX = event.clientX,
			      	positionSourisY = event.clientY,
			      	positionElement = angular.element(ui.draggable).offset(),
			      	positionSourisInAppX = positionSourisX - positionElement.left,
			      	positionSourisInAppY = positionSourisY - positionElement.top,
			      	nouvellePositionElementX = positionSourisX - pos.left - positionSourisInAppX,
			      	nouvellePositionElementY = positionSourisY - pos.top - positionSourisInAppY,
			      	largeurGestionnaireDonnee = parseInt(angular.element(document.querySelector('#overlayGestionnaireDonnees')).css('width'));
			      
             
		      	// Pour insérer une application dans la noospace  

		       var elt = document.getElementsByClassName('draggableElemnt')[0];
		       if (typeof elt != 'undefined' ){
		       elt.classList.remove('draggableElemnt');
		       //removeClass(elt, 'draggableElemnt')
		       console.log(elt.parentNode);
		       
		      	if(elt.parentNode.getAttribute('id') === 'applicationsInDock'  || angular.element(ui.draggable).className =='runIt'){
		      		console.log(this);
		      		applicationService.deployApplication( elt, this, nouvellePositionElementX, nouvellePositionElementY);
		      
				// Pour insérer une nouvelle donnée dans la noospace
			    }else if(elt.parentNode.getAttribute('id') === 'inListeDonneesUser' && positionSourisX > largeurGestionnaireDonnee){

			    	// Pour n'insérer que les nouvelles données qui ne sont pas positionées dans une dataBox, car c'est le droppable de l'application qui s'en charge
			    	var isIn = false;
			    	
			    		myobject=angular.element('#noospace .dataBox');
			    		Object.keys(myobject).forEach(key => {
			      		var p = angular.element(this).offset();
						if(positionSourisX >= p.left && positionSourisX <= p.left +64 && positionSourisY >= p.top && positionSourisY <= p.top + 64){
							isIn = true;
						}
					});

			    	if(!isIn){
			    		//dataService.initDonneeUtilisateur(angular.element(ui.draggable).clone(), $(this), positionSourisX - pos.left -34, positionSourisY - pos.top -34);
			    		dataService.initDonneeUtilisateur(angular.copy(angular.element(ui.draggable)),angular.element(this), positionSourisX - pos.left -34, positionSourisY - pos.top -34);
			    	
			    	}
					
			    // Pour retirer une donnée d'une dataBox de l'application
			    }else if(ui.draggable.parent().hasClass('dataBox ui-droppable')){
			    	
			    	angular.element(this).append(angular.element(ui.draggable));
					angular.element(ui.draggable).css('position','absolute').css('top', nouvellePositionElementY+'px').css('left', nouvellePositionElementX+'px');
			    	
			    // Pour ajouter une donnée locale à la noospace
			    }else if(angular.element(ui.draggable).hasClass('newTempData')){
			    	ui.draggable.removeClass('newTempData');

			    	dataService.initDonneeUtilisateur(ui.draggable, angular.element(this), nouvellePositionElementX, nouvellePositionElementY);
			    }
			} // if elt not null
			}
		});
		
	}
}
	}]);


			

