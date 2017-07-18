(function(){

	var url = document.location.href,
		logIn = /LogIn/;

/****************/
/* 	NAVIGATION  */
/****************/
	
	if($('#menuFilAriane').length != 0){
		$('#menuFilAriane').jBreadCrumb();
	}

/*************************/
/* 	INFORMATIONS CLIENT  */
/*************************/

	displayInformationsClient();

/*************************/
/* 	CONTROLE NAVIGATEUR  */
/*************************/

	validBrowser();

/*****************/
/* 	INFOS BULLE  */
/*****************/

	if(!logIn.test(url)){
	
		// Pour afficher les popOver du header
		$('nav img').popover({placement:'bottom', trigger:'hover'});
		$('.image-upload img').popover({placement:'bottom', trigger:'hover'});

		// Pour afficher les infos bulles Bottom/Right
		$('.infoBulle').tooltip({placement:'bottom', trigger:'hover'});
		$('.infoBulleRight').tooltip({placement:'right', trigger:'hover'});
		
		// Pour afficher les infos bulles Top du menu général
		$('.infoBulleGeneralMenuTop').tooltip({
			delay: {
				show: 800,
				hide: 100
			}, 
			placement:'top', 
			trigger:'hover'
		});

		// Pour afficher les infos bulles Bottom du menu général
		$('.infoBulleGeneralMenuBottom').tooltip({
			delay: {
				show: 800,
				hide: 100
			}, 
			placement:'bottom', 
			trigger:'hover'
		});

		// Pour afficher les infos bulles dans le gestionnaire de données
		$('.infoBulleDataManager').tooltip({
			delay: {
				show: 800,
				hide: 100
			}, 
			placement:'right', 
			trigger:'hover'
		});
	}

/************/
/* 	DESIGN  */
/************/
	
	if(!logIn.test(url)){
		// Pour activer les carrousels
		if($('.carousel').length != 0){
			$('.carousel').carousel('pause');
		}

		// Pour améliorer le design des inputs file
		$('[type="file"][class!="withoutBootstrap"]').filestyle({
			iconName: 'glyphicon glyphicon-download-alt'
		});

		// Pour améliorer le design des select
		$('select[class!="withoutBootstrap"]').selectpicker();
	}

/********************/
/* 	HELPER MANAGER  */
/********************/

	// Pour afficher l'aide à l'utilisateur
	$('#helperButton').click(function(e){
		e.preventDefault();
		var currentUrl = document.location.href;
		currentUrl = currentUrl.substring(currentUrl.indexOf('/', 8)+1);
		if(currentUrl.length == 0){
			currentUrl = 'Frontend/';
		}
		currentUrl = '/Helper/'+currentUrl.substring(0,currentUrl.indexOf('/'));

		$.ajax({
            url: currentUrl,
            type: 'POST',
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
            	if(isJson(response)){
            		var response = JSON.parse(response);
            		if(!response['erreurs']){
	            		var response = {};
                		response['erreurs'] = '<p>A system error has occurred.</p>';
	            	}
	            	displayInformationsClient(response);  
            	}else{
            		$('#helperApplication').find('.modal-body').html(response);
            		$('#helperApplication').modal('show');

            		// On contrôle le formulaire de contact
            		$('#formContact').on('submit', function(e){
		            e.preventDefault();
		            var formData = new FormData(e.target),
		                btn = $(this).find('button');
		            btn.button('loading');
		            // Envoi de la requête HTTP en mode asynchrone
		            $.ajax({
		                url: '/Helper/Contact',
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
		                        'erreurs': '<p>A system error has occurred.</p>'
		                    };
		                    displayInformationsClient(response);
		                }
		            });
		        });
            	}
            },
            error: function(){
            	var response = {};
                response['erreurs'] = '<p>A system error has occurred.</p>';
                displayInformationsClient(response);
            }
        });
	});

/***************************/
/* 	DOCK DES APPLICATIONS  */
/***************************/

	if($('#overlayDockApplication').length !=0){
		var hauteurFenetre = window.innerHeight,
			overlayDockApplication = $('#overlayDockApplication'),
			hauteurInitiale = -100,
			hauteurFinale = 0;

		overlayDockApplication.css('bottom', hauteurInitiale + 'px');
		overlayDockApplication.css('display', 'block');

		//Pour rendre les applications présentes dans le dock draggable par JS
		$('#applicationsInDock .appInDock').draggable({
			containment: '#noospace',
			revert: true
		});

		// On initialise le menu contextuel des applications
		if($('#overlayDockApplication .appInDock').length !=0){
			menuApplicationsInDock();
		}

		// Pour afficher le dock application lorsque l'on passe sur le bouton ajouter au dock
		if($('#boutonAjouterAuDock').length != 0){
			
			$('#boutonAjouterAuDock').on('mouseover', function(e){
				// On évite les effets de propagation enfant
				var relatedTarget = e.relatedTarget;

				while(relatedTarget != this && relatedTarget.nodeName != 'BODY' && relatedTarget != document){
					
					relatedTarget = relatedTarget.parentNode;
				}

				if(relatedTarget != this){
					animPlus(hauteurFinale, hauteurInitiale, overlayDockApplication);
				}

			});

			$('#formAddApplicationInDock').on('submit', function(e) {
		        e.preventDefault(); // J'empêche le comportement par défaut du navigateur
		 
		        var $this = $(this); // L'objet jQuery du formulaire
		
	            // Envoi de la requête HTTP en mode asynchrone
	            $.ajax({
	                url: $this.attr('action'), // Le nom du fichier indiqué dans le formulaire
	                type: $this.attr('method'), // La méthode indiquée dans le formulaire (get ou post)
	                data: $this.serialize(), // Je sérialise les données (j'envoie toutes les valeurs présentes dans le formulaire)
	                success: function(response) {
	                    // Mise à jour du dock
	                    $('#applicationsInDock').html(response);

	                    // Menu contextuel des applications
	                    menuApplicationsInDock();

	                    //Draggable des applications
						$('#applicationsInDock .appInDock').draggable({
							containment: '#noospace',
							revert: true
						});

						// Modification de l'aspect du bouton
						var boutonAjouterAuDock = $('#boutonAjouterAuDock');
						if(boutonAjouterAuDock.html() === '<i class="glyphicon glyphicon-ok"></i>'){
							boutonAjouterAuDock.html('<i class="glyphicon glyphicon-remove"></i>');
							boutonAjouterAuDock.addClass('btn-danger').removeClass('btn-success');
						}else{
							boutonAjouterAuDock.html('<i class="glyphicon glyphicon-ok"></i>');
							boutonAjouterAuDock.addClass('btn-success').removeClass('btn-danger');
						}

						// On réinitialise la taille des applications en fct de la largeur dock
						initSizeApplications();
	                },
	                error: function(){
	                	var response = {};
	                    response['erreurs'] = '<p>A system error has occured.</p>';
	                    displayInformationsClient(response);
	                }
	            });
		        
	    	});

		}
		
		// Pour afficher le dock application dans la page web si on ne se trouve pas dans l'arbre de l'application
		$(document).on('mousemove', function(e){
			var url = document.location.href,
				regex = /ManagerOfApplications/,
				relatedTarget = e.target;

			if(!regex.test(url)){
				var menuOpenInDock = false;

				$('.context-menu-list').each(function(){
					if($(this).css('display') != 'none'){
						menuOpenInDock = true;
					}
				});

				// On évite les effets de propagation enfant
				while(relatedTarget.id != 'formAddApplicationInDock' && relatedTarget.nodeName != 'BODY' && relatedTarget != document){
					relatedTarget = relatedTarget.parentNode;
				}

				if(relatedTarget.id != 'formAddApplicationInDock'){
					if((e.clientY >= hauteurFenetre - 10) && (parseInt(overlayDockApplication.css('bottom')) == hauteurInitiale)){
					
						animPlus(hauteurFinale, hauteurInitiale, overlayDockApplication);

					}else if(!menuOpenInDock && (e.clientY < hauteurFenetre - 100) && (parseInt(overlayDockApplication.css('bottom')) == hauteurFinale)){
						animMoins(hauteurInitiale, hauteurFinale, overlayDockApplication);
					}
				}
			}
		});

		// Pour ajuster automatiquement au dock la taille des icônes des applications
		initSizeApplications();
	}
})();

/***************/
/* 	Fonctions  */
/***************/

/* isJson ? */
function isJson(text){
	try{
		JSON.parse(text);
		return true;
	}
	catch(e){
		return false;
	}
}

/* Créer un cookie */
function setCookie(sName, sValue) {
    var today = new Date(), expires = new Date();
    expires.setTime(today.getTime() + (1*60*60*1000));
    document.cookie = sName + '=' + encodeURIComponent(sValue) + ';expires=' + expires.toGMTString() + ';path=/';
}

/* Récupérer un cookie */
function getCookie(sName) {
    var oRegex = new RegExp("(?:; )?" + sName + "=([^;]*);?");
    if (oRegex.test(document.cookie)) {
		return decodeURIComponent(RegExp["$1"]);
    } else {
            return null;
    }
}

/* Affichage des informations client */
function displayInformationsClient(response){
	if(typeof(response) != 'undefined'){	
		if(response['erreurs'] && response['erreurs'] != ''){		
			$('#informationsClient').show().append('<div class="alert alert-danger alert-dismissable" style="display:none"><button type="button" class="close" data-dismiss="alert">x</button><h3>Warning</h3>'+response['erreurs']+'</div>');		
			$('#informationsClient').find('.alert:last').fadeIn(function(){				
					$(this).delay(6000).fadeOut(800, function(){
						$(this).remove();
					});
			});		
		}
		if(response['reussites'] && response['reussites'] != ''){
			$('#informationsClient').show().append('<div class="alert alert-success alert-dismissable style="display:none"><button type="button" class="close" data-dismiss="alert">x</button><h3>Information</h3>'+response['reussites']+'</div>');
			$('#informationsClient').find('.alert:last').fadeIn(function(){					
					$(this).delay(6000).fadeOut(function(){
						$(this).remove();
					});
			});
		}
	}else{
		if($('#informationExists').length != 0){			
			$('#informationsClient').fadeIn(function(){					
				$('#informationsClient').delay(6000).fadeOut(800, function(){
					$('#informationsClient').html(''); // On réinitialise
				});
			});				
		}
	}
}

/* Contrôle du navigateur */
function validBrowser(){

	var isValid = getCookie('browser');

	if(isValid === null){

	    var isMobile = {
	        Android: function() {
	            return navigator.userAgent.match(/Android/i);
	        },
	        BlackBerry: function() {
	            return navigator.userAgent.match(/BlackBerry/i);
	        },
	        iOS: function() {
	            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	        },
	        Opera: function() {
	            return navigator.userAgent.match(/Opera Mini/i);
	        },
	        Windows: function() {
	            return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
	        },
	        any: function() {
	            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
	        }
	    };

	    if(!isMobile.any()){
	    	// Opera 8.0+
	        var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
	            // Firefox 1.0+
	        var isFirefox = typeof InstallTrigger !== 'undefined';
	            // At least Safari 3+: "[object HTMLElementConstructor]"
	        var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
	            // Internet Explorer 6-11
	        var isIE = /*@cc_on!@*/false || !!document.documentMode;
	            // Edge 20+
	        var isEdge = !isIE && !!window
	            // Chrome 1+
	        var isChrome = !!window.chrome && !!window.chrome.webstore;

	        // Contrôle des versions
	        if(isOpera){
	            var regexp = /OPR\/([0-9]{1,2})/;

	            regexp.test(navigator.userAgent);
	            var name = 'opera',
	                version = RegExp.$1;
	            
	        }else if(isFirefox){
	            var regexp = /Firefox\/([0-9]{1,2})/;

	            regexp.test(navigator.userAgent);
	            var name = 'firefox',
	                version = RegExp.$1;
	        }else if(isSafari){
	            var regexp = /Version\/([0-9]{1,2}).[0-9]{0,1}.[0-9]{0,1} Safari/;

	            regexp.test(navigator.userAgent);
	            var name = 'safari',
	                version = RegExp.$1;
	        }else if(isChrome){
	            var regexp = /Chrome\/([0-9]{1,2})/;

	            regexp.test(navigator.userAgent);
	            var name = 'chrome',
	                version = RegExp.$1;
	        }else if(isIE){
	            var name = 'ie',
	                version = '';
	        }else if(isEdge){
	            var regexp = /Edge\/([0-9]{1,2})/;
	            regexp.test(navigator.userAgent);
	            var name = 'edge',
	                version = RegExp.$1;
	        }else{
	            var name = 'unknown',
	                version = '';
	        }

	        // Envoi de la requête HTTP en mode asynchrone
	        xhr = new XMLHttpRequest();
	        xhr.open('POST', '/LogIn/BrowserIsValid/');
	        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	        xhr.send('name='+name+'&version='+parseInt(version));     
	        xhr.onreadystatechange = function(){
	            if(xhr.readyState == 4 && xhr.status == 200){
	                var response = JSON.parse(xhr.responseText);
	                if(response['isValid'] === 1){
	                	setCookie('browser', 1);
	                }else{
	                    var texte = '';
	                        if(name != 'unknown' && name != 'ie'){
	                            texte += 'This version of your browser ' + name+ ' is obsolete and NooLib can present unwanted malfunctions.<br/>';
	                            texte += 'We recommend updating your current browser.';
	                        }else if(name == 'ie'){
	                            texte += 'All versions of Internet Explorer are not supported by NooLib and the plateform can present unwanted malfunctions.<br/>';
	                            texte += 'We recommend changing to another updated browser.';
	                        }else{
	                            texte += 'Your browser is unknown and NooLib can present unwanted malfunctions.<br/>';
	                            texte += 'We recommend changing to another updated browser.';
	                        }
	                        $('#alertBrowser').removeClass('hidden').append(texte);
	                }   
	            }
	        }
	    }
	}
}

/* Function pour animer négativement le déplacement du dockApplication */
function animMoins(nouvelleHauteur, hauteurOverlay, element){
	
	// On modifie la hauteur du div en conséquence
	hauteurOverlay = parseInt(hauteurOverlay) - 20 + 'px';
	
	element.css('bottom', hauteurOverlay);

	if(parseInt(hauteurOverlay) > nouvelleHauteur){
		setTimeout(function(){
			animMoins(nouvelleHauteur, hauteurOverlay, element);
		}, 20);
	}
}


/* Function pour animer positivement le déplacement du dockApplication */
function animPlus(nouvelleHauteur, hauteurOverlay, element){

	// On modifie la hauteur du div en conséquence
	hauteurOverlay = parseInt(hauteurOverlay) + 20 + 'px';
	
	element.css('bottom', hauteurOverlay);

	if(parseInt(hauteurOverlay) < nouvelleHauteur){
		setTimeout(function(){
			animPlus(nouvelleHauteur, hauteurOverlay, element);
		}, 20);
	}
	
}

/* Pour ajuster la largeur des applications au dock */
function initSizeApplications(){
	
	var largeurDock = $('.inHeaderDock').width(),
		largeurTitreDock = $('.titreDock').width(),
		largeurApplicationsInDock = $('#applicationsInDock').width(),
		nombreApplicationsInDock = $('.appInDock').length;

	if((largeurTitreDock+largeurApplicationsInDock) > largeurDock){
		var nouvelleLargeurApplication = Math.round((largeurDock-largeurTitreDock)/(nombreApplicationsInDock+1)-5);

		$('.appInDock').find('.imageApplication').css({
			'width': nouvelleLargeurApplication,
			'height': nouvelleLargeurApplication
		});
	}
}

/* Menu contextuel des applications */
function menuApplicationsInDock(){
  	$.contextMenu({
        selector: '#applicationsInDock .appInDock', 
        callback: function(key, options) {
            if(key === 'remove'){
            	var application = $(this);
            	
            	$.post('/HandleApplication/AddRemoveToDock', {
					idApplication: application.attr('id')
				}, 
				function(response){
					$('#boutonAjouterAuDock').html('<i class="glyphicon glyphicon-ok"></i>');
					$('#boutonAjouterAuDock').addClass('btn-success').removeClass('btn-danger');
					application.remove();
				});
            }else if(key==='about'){
            	document.location.href="/Library/app="+$(this).attr('id');
            }else if(key==='tree'){
            	document.location.href="/ManagerOfApplications/app="+$(this).attr('id');
            }else if(key==='run'){
            	document.location.href="/NooSpace/a="+$(this).attr('id')+"v=";
            }
        },
        items: {
        	"run":{name:"Run it"},
        	"about":{name:"About it"},
        	"tree":{
        		name:"Open tree", 
        		disabled: function(){
        			return false;
        		}
        	},
        	"sep1": "---------",
            "remove": {name: "Remove from the dock", icon: "remove"}
        }
    });
}

// A déplacer dans la noospace
/* Pour transformer un fichier base64 en blob */
function base64toBlob(base64Data, contentType){

    contentType = contentType || '';
    var sliceSize = 1024;
    var byteCharacters = atob(base64Data);
    var bytesLength = byteCharacters.length;
    var slicesCount = Math.ceil(bytesLength / sliceSize);
    var byteArrays = new Array(slicesCount);

    for (var sliceIndex = 0; sliceIndex < slicesCount; ++sliceIndex) {
       
        var begin = sliceIndex * sliceSize;
        var end = Math.min(begin + sliceSize, bytesLength);

        var bytes = new Array(end - begin);
        for (var offset = begin, i = 0 ; offset < end; ++i, ++offset) {
            bytes[i] = byteCharacters[offset].charCodeAt(0);
        }
        byteArrays[sliceIndex] = new Uint8Array(bytes);
    }

    return new Blob(byteArrays, { type: contentType });
    
}

/* Pour gérer l'affichage des slider dans le panneau des paramètres de l'application */
function sliderParametreApplication(cloneContenuPanel){

    cloneContenuPanel.find('.sliderParametreApplication').each(function(){
       
        var valeurDefautParametre = parseInt($(this).parent().find('.valeurDefautParametre').attr('value')),
            valeurMinParametre = parseInt($(this).parent().find('.valeurMinParametre').attr('value')),
            valeurMaxParametre = parseInt($(this).parent().find('.valeurMaxParametre').attr('value')),
            valeurPasParametre = parseInt($(this).parent().find('.valeurPasParametre').attr('value'));
        
        $(this).slider({
            range: 'min',
            min: valeurMinParametre,
            max: valeurMaxParametre,
            value: valeurDefautParametre,
            step: valeurPasParametre,
            slide: function( event, ui ) {
                $(this).parent().find('.valeurDefautParametre').attr('value', ui.value);
            }
        });
    });    
}

//test by Naoures 
$.fn.nearest = function(selector, radius) {
    var position = this.offset();

    if (!radius) radius = 10; // pixels

    var positions = [];
    var elements = [];

    // so, move up and left by the value of radius variable (lets say its 10)
    // start from the -10 -10 px corner of the element and move all the way to 
    // +10 +10 coordinats to the right bottom corner of the element
    var startX = position.left - radius;
    var startY = position.top - radius;
    var endX = position.left + this.outerWidth(true) + radius;
    var endY = position.top + this.outerHeight(true) + radius;

    var positions = [];

    // create horizontal lines
    // --------------
    //  your element
    // --------------
    for (var x = startX; x < endX; x++) {
        // creates upper line on the startY coordinate
        positions.push([x, startY]);
        // creates bottom line on the endY coordinate
        positions.push([x, endY]);
    }

    // create the vertical positions
    // |              |
    // | your element |
    // |              |
    for (var y = startY; y < endY; y++) {
        // creates the left line on the startX coordinate
        positions.push([startX, y]);
        // creates the right line on the endX coordinate
        positions.push([endX, y]);
    }

    // so now the positions array contains all the positions around your element with a radius that you provided
    for (var i = 0; i < positions.length; i++) {
        // just loop over the positions, and get every element
        var position = positions[i];
        var x = position[0];
        var y = position[1];

        var element = document.elementFromPoint(x, y);
        // if element matches with selector, save it for the returned array
        if ($(element).is(selector) && elements.indexOf(element) === -1) elements.push(element);
    }

    return $(elements);
}