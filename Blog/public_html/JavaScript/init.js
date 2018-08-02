import displayInformationsClient from './displayInformationsClient.js';

(function(){
    /**************************/
    /* Contrôle du navigateur */
    /**************************/
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
            var xhr = new XMLHttpRequest();
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
                                texte += 'La version de votre navigateur ' + name+ ' est obsolète et NooLib The Blog risque de présenter des dysfonctionnements.<br/>';
                                texte += 'Nous vous recommandons de mettre à jour votre navigateur ou de changer pour un autre navigateur.';
                            }else if(name == 'ie'){
                                texte += 'Aucune des versions d\'Internet Explorer n\'est supportée par NooLib The Blog et le site risque de présenter des dysfonctionnements.<br/>';
                                texte += 'Nous vous recommandons de changer pour un autre navigateur.';
                            }else{
                                texte += 'Votre navigateur est inconnu et NooLib The Blog risque de présenter des dysfonctionnements.<br/>';
                                texte += 'Nous vous recommandons de changer pour un autre navigateur.';
                            }
                            $('#alertBrowser').removeClass('hidden').append(texte);
                    }   
                }
            }
        }
    }

    /******************/
    /* Message Client */
    /******************/
    displayInformationsClient();


    /************/
    /* Frontend */
    /************/
    // IdisplayInformationsClientnfo bulle
    var largeur = screen.width; // On supprime pour les smartphones
    if(largeur >= 768){
        $('.infoBulleBottom').tooltip({
            delay: {
                show: 600,
                hide: 100
            }, 
            placement:'bottom', 
            trigger:'hover'
        });
    }

    // Pour le carrousel des actualités
    var numeroActualite = 0;
    $('.rightArrow').on('click', function(e){
        numeroActualite += 1;
        getActualite(1);
    });
    $('.leftArrow').on('click', function(e){
        numeroActualite -= 1;
        getActualite(-1);
    });

    function getActualite(side){
        var formData = new FormData();
        formData.append('numeroActualite', numeroActualite);

        // Envoi de la requête HTTP en mode asynchrone
        $.ajax({
            url: '/GetActualite/',
            type: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                var response = JSON.parse(response);
                if(response['erreurs']){
                    displayInformationsClient(response);
                    if(side < 0){
                        numeroActualite += 1;
                    }else{
                        numeroActualite -= 1;
                    }
                }else{
                    if(numeroActualite <= 0){
                        $('.leftArrow').addClass('hidden');
                    }else if(numeroActualite > 0 && numeroActualite < response['nbreActualites']-1){
                        $('.leftArrow').removeClass('hidden');
                        $('.rightArrow').removeClass('hidden');
                    }else if(numeroActualite >= response['nbreActualites']-1){
                        $('.rightArrow').addClass('hidden');
                    }
                    var caseActualite = $('.caseActualite');
                    $('.actualite').fadeOut('slow', function(){
                        caseActualite.find('h2').empty().append(response['titre']);
                        caseActualite.find('p').empty().append(response['texte']);
                        caseActualite.find('a').attr('href',response['lien']);
                        $(this).css('background-image', 'url('+response['urlBackground']+')');
                    });
                    $('.actualite').fadeIn('slow');
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

    // Pour faire apparaître l'édito
    $('.edito').on('click', function(e){
        var target = $(e.target),
            edito = $('.maxWidthEditorial'),
            heightEdito = edito[0].scrollHeight;
        // On déplie l'édito
        edito.animate({'height':heightEdito+'px'}, 1000, function(){
            if(target.attr('class') != 'edito'){
                target = target.parent();
            }
            // On cache le bouton
            target.fadeOut('slow');
        });
    });
    
    // Pour le carrousel des éditos
    var numeroEdito = 0;
    $('.rightEditoArrow').on('click', function(e){
        numeroEdito += 1;
        getEdito(1);
    });
    $('.leftEditoArrow').on('click', function(e){
        numeroEdito -= 1;
        getEdito(-1);
    });

    function getEdito(side){
        var formData = new FormData();
        formData.append('numeroEdito', numeroEdito);

        // Envoi de la requête HTTP en mode asynchrone
        $.ajax({
            url: '/GetEdito/',
            type: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                var response = JSON.parse(response);
                
                if(response['erreurs']){
                    displayInformationsClient(response);
                    if(side < 0){
                        numeroEdito += 1;
                    }else{
                        numeroEdito -= 1;
                    }
                }else{
                    if(numeroEdito <= 0){
                        $('.leftEditoArrow').addClass('hidden');
                        $('.rightEditoArrow').removeClass('hidden');
                    }else if(numeroEdito > 0 && numeroEdito < response['nbreEditos']-1){
                        $('.leftEditoArrow').removeClass('hidden');
                        $('.rightEditoArrow').removeClass('hidden');
                    }else if(numeroEdito >= response['nbreEditos']-1){
                        $('.leftEditoArrow').removeClass('hidden');
                        $('.rightEditoArrow').addClass('hidden');
                    }

                    $('#number').empty().append(numeroEdito+1);
                    $('html,body').animate({scrollTop: $("#beginEdito").offset().top}, 1000);
                    
                    var blockquote = $('blockquote');
                    blockquote.find('h3').empty().append('Editorial du '+response['date']);
                    blockquote.find('p').empty().append(response['texte']);

                    var edito = $('.maxWidthEditorial'),
                        blockquote = $('blockquote'),
                        heightBlockquote = blockquote.outerHeight();
                    // On ajuste la hauteur de l'édito
                    edito.animate({'height':heightBlockquote+120+'px'}, 1000);
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


    // Pour afficher les statistiques avec des circles progress
    // InView plugIn
    $('.statistics').one('inview', function(event, isInView) {
        if (isInView) {
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: '/Statistiques/',
                type: 'POST',
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    response = JSON.parse(response);
                    if(response['nbreUsers']){
                        var valeurMaxArticles = response['nbreArticles']+2,
                            valeurMaxVues = response['nbreVues']+1,
                            ValeurMaxAutres = Math.max(response['nbreUsers'], response['nbreCommentaires'])+1,
                            statUtilisateurs  = $('#statUtilisateurs'),
                            statVues = $('#statVues'),
                            statCommentaires = $('#statCommentaires'),
                            statArticles = $('#statArticles');
                        statUtilisateurs.circleProgress({
                            value: 1,
                            startAngle: -Math.PI / 4 * 3,
                            size: 150,
                            fill: {
                                color: '#F7BE81'
                            },
                            lineCap: 'round'
                        }).on('circle-animation-progress', function(event, progress, stepValue) {
                          $(this).find('strong').text(String(Math.round(stepValue.toFixed(1)*ValeurMaxAutres)));
                        });
                        setTimeout(function() { 
                            statUtilisateurs.circleProgress('value', response['nbreUsers']/ValeurMaxAutres); 
                        }, 1000);
                        
                        statVues.circleProgress({
                            value: 1,
                            startAngle: -Math.PI / 4 * 3,
                            size: 150,
                            fill: {
                                color: '#F78181'
                            },
                            lineCap: 'round'
                        }).on('circle-animation-progress', function(event, progress, stepValue) {
                          $(this).find('strong').text(String(Math.round(stepValue.toFixed(1)*valeurMaxVues)));
                        });
                        setTimeout(function() { 
                            statVues.circleProgress('value', response['nbreVues']/valeurMaxVues); 
                        }, 1000);

                        statCommentaires.circleProgress({
                            value: 1,
                            startAngle: -Math.PI / 4 * 3,
                            size: 150,
                            fill: {
                                color: '#4ac5f8'
                            },
                            lineCap: 'round'
                        }).on('circle-animation-progress', function(event, progress, stepValue) {
                            $(this).find('strong').text(String(Math.round(stepValue.toFixed(1)*ValeurMaxAutres)));
                        });
                        setTimeout(function() { 
                            statCommentaires.circleProgress('value', response['nbreCommentaires']/ValeurMaxAutres); 
                        }, 1000);

                        statArticles.circleProgress({
                          value: 1,
                            startAngle: -Math.PI / 4 * 3,
                            size: 150,
                            fill: {
                                color: '#0681c4'
                            },
                            lineCap: 'round'
                        }).on('circle-animation-progress', function(event, progress, stepValue) {
                          $(this).find('strong').text(String(Math.round(stepValue.toFixed(1)*valeurMaxArticles)));
                        });
                        setTimeout(function() { 
                            statArticles.circleProgress('value', response['nbreArticles']/valeurMaxArticles); 
                        }, 1000);
                    }else{
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
        } else {
        // element has gone out of viewport
        }
    });


    /*********************/
    /* Position Articles */
    /*********************/
    if($('.caseArticle').length != 0){
        
        setTimeout(function(){
            displayArticles();
        }, 300);
        

        $(window).resize(function(){
            $(document).ready(function(){
                setTimeout(function(){
                    displayArticles();
                }, 300);
            });
            
        });

        function displayArticles(){
            var elems = $('.caseArticle'),
                marginBottom = parseInt($(elems[0]).css('marginBottom')),
                parentWidth = $(elems[0]).parent().width()-40,
                sonWidth = $(elems[0]).outerWidth(),
                nbreCase = Math.floor(parentWidth/sonWidth);

            for(var i=nbreCase, c=elems.length; i<c; ++i){
                var elem = $(elems[i]),
                    positionArticle = elem.offset(),
                    topArticle = $(elems[i-nbreCase]),
                    positionTopArticle = topArticle.offset(),
                    heightTopArticle = topArticle.outerHeight(),
                    difference = positionArticle.top - (positionTopArticle.top+heightTopArticle);
                
                if(difference>marginBottom+2){
                    elem.offset({
                        top: positionArticle.top - difference + marginBottom,
                        left: positionArticle.left
                    });
                }else if(difference<0){
                    elem.offset({
                        top: positionTopArticle.top + heightTopArticle + marginBottom,
                        left: positionArticle.left
                    });
                }
            }
        }

        // Pour afficher les infos bulles
        $('.infoBulleBottom').tooltip({
            delay: {
                show: 800,
                hide: 100
            }, 
            placement:'bottom', 
            trigger:'hover'
        });
    }


    /************************/
    /* Manager Commentaires */
    /************************/
    if( $('#formAddComment').length !=0){
        // On gère l'ajout d'un commentaire
        $('#formAddComment').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData(e.target),
                btn = $(this).find('button');
            btn.button('loading');
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: '/Commentaire/AjouterCommentaire',
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
                        'erreurs': '<p>Une erreur système est apparue.</p>'
                    };
                    displayInformationsClient(response);
                }
            });
        });
    }

    /*******************/
    /* Chargement page */
    /*******************/
    window.onload=function(){
        $('.overlay').fadeOut();
    };
})();

/*************/
/* Fonctions */
/*************/

function setCookie(sName, sValue) {
    var today = new Date(), expires = new Date();
    expires.setTime(today.getTime() + (1*60*60*1000));
    document.cookie = sName + '=' + encodeURIComponent(sValue) + ';expires=' + expires.toGMTString() + ';path=/';
}

function getCookie(sName) {
    var oRegex = new RegExp("(?:; )?" + sName + "=([^;]*);?");
    if (oRegex.test(document.cookie)) {
		return decodeURIComponent(RegExp["$1"]);
    } else {
            return null;
    }
}

/********************/
/* Google Analytics */
/********************/
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-105148822-1', 'auto');
ga('send', 'pageview');