/* Contrôle du navigateur */
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
                    var texte = '<a href="#" class="close" data-dismiss="alert" aria-label="close">x</a>';
                        if(name != 'unknown' && name != 'ie'){
                            texte += 'This version of your browser ' + name+ ' is obsolete and NooLib can present unwanted malfunctions.<br/>';
                            texte += 'We recommmend updating your current browser.';
                        }else if(name == 'ie'){
                            texte += 'All versions of Internet Explorer are not supported by NooLib and the plateform can present unwanted malfunctions.<br/>';
                            texte += 'We recommmend changing to another updated browser.';
                        }else{
                            texte += 'Your browser is unknown and NooLib can present unwanted malfunctions.<br/>';
                            texte += 'We recommmend changing to another updated browser.';
                        }
                        $('#alertBrowser').removeClass('hidden').append(texte);
                }   
            }
        }
    }
}

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