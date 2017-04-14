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
		
		function isWindow( obj ) {
		    return obj != null && obj === obj.window;
		}
		function getWindow( elem ) {
		    return isWindow( elem ) ? elem : elem.nodeType === 9 && elem.defaultView;
		}
		function offset( elem ) {

		    var docElem, win,
		        box = { top: 0, left: 0 },
		        doc = elem && elem.ownerDocument;

		    docElem = doc.documentElement;

		    if ( typeof elem.getBoundingClientRect !== typeof undefined ) {
		        box = elem.getBoundingClientRect();
		    }
		    win = getWindow( doc );
		    return {
		        top: box.top + win.pageYOffset - docElem.clientTop,
		        left: box.left + win.pageXOffset - docElem.clientLeft
		    };
		};


		applicationService.verifApplication();

		element.droppable({
			//accept: ".ui-draggable",
			drop: function(event, ui){

				var pos = offset(this);
					
		    	var positionSourisX = event.clientX,
			      	positionSourisY = event.clientY,
			      	positionElement = angular.element(ui.draggable).offset(),
			      	positionSourisInAppX = positionSourisX - positionElement.left,
			      	positionSourisInAppY = positionSourisY - positionElement.top,
			      	nouvellePositionElementX = positionSourisX - pos.left - positionSourisInAppX,
			      	nouvellePositionElementY = positionSourisY - pos.top - positionSourisInAppY,
			      	largeurGestionnaireDonnee = parseInt($('#overlayGestionnaireDonnees').css('width'));
			      
             
		      	// Pour insérer un élément dans la noospace  

		       var elt = document.getElementsByClassName('draggableElemnt')[0];
		      // console.log(elt.parentNode);
		       if (typeof elt != 'undefined' ){

			       	//***********************************
			       elt.classList.remove('draggableElemnt');
			     
			      	if(elt.parentNode.getAttribute('id') === 'applicationsInDock'  || elt.className == 'runIt'){
			      		applicationService.deployApplication(elt, this, nouvellePositionElementX, nouvellePositionElementY);
			      
					// Pour insérer une nouvelle donnée dans la noospace
				    }else if(elt.parentNode.getAttribute('id') === 'inListeDonneesUser' && positionSourisX > largeurGestionnaireDonnee){
				    	// Pour n'insérer que les nouvelles données qui ne sont pas positionées dans une dataBox, car c'est le droppable de l'application qui s'en charge
				    	
				    	var isIn = false;
				    	
				    		//myobject=angular.element('#noospace .dataBox');
				    	var myobject=document.querySelectorAll('#noospace .dataBox');
			    		for (var i = 0; i < myobject.length ; ++i) {
			    			
			    			var p = offset(myobject[i]);
							if(positionSourisX >= p.left && positionSourisX <= p.left +64 && positionSourisY >= p.top && positionSourisY <= p.top + 64){
								isIn = true;
							}
			    		};

				    	if(!isIn){
				    		
				    		dataService.initDonneeUtilisateur($(elt).clone(),angular.element(this), positionSourisX - pos.left -34, positionSourisY - pos.top -34);
				    	
				    	}
				    	
						
				    // Pour retirer une donnée d'une dataBox de l'application

				    }else if($(elt).parent().hasClass('dataBox ui-droppable') ){
				    	console.log($(this));
				    	$(this).append($(elt));
						angular.element(elt).css('position','absolute').css('top', nouvellePositionElementY+'px').css('left', nouvellePositionElementX+'px');
						
				    	
				    // Pour ajouter une donnée locale à la noospace
				    }else if(angular.element(elt).hasClass('newTempData')){
				    	
				    	elt.removeClass('newTempData');

				    	dataService.initDonneeUtilisateur(elt, angular.element(this), nouvellePositionElementX, nouvellePositionElementY);

				    }
				    //****************************
				} 
			}
		});
		
	}
}
	}]);


			

