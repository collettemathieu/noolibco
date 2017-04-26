function validerFormulaireApplicationByAjax(formData, url){
        formData.append('idVersion', parseInt($('#selectVersion').val()));
        formData.append('idApp', parseInt($('#containerTreeApplication').attr('idApp')));
        
        $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
				    $('form button').button('reset');
                	var response = JSON.parse(response);
					
                	if(response['reussites']){
					 
                        setTimeout(function(){
                            location.reload();
                        }, 3000);
                		$('#overlayFormApplication').hide('drop', 600);
                	}
					
                	displayInformationsClient(response);
                },
                error: function(){
				    $('form button').button('reset');
                	var response = {};
                    response['erreurs'] = '<p>An error has occurred while displaying the form.</p>';
                    displayInformationsClient(response);
                }
        });
}

function addFunctionToTask(URL){
    // Disabling autoDiscover, otherwise Dropzone will try to attach twice
    Dropzone.autoDiscover = false;
    var DROPZONE_OPTIONS = {
        url: URL,
        paramName: 'urlFonction',  // The name that will be used to transfer the file
        maxFiles: 1,
        maxFilesize: 5,  // MB
        dictDefaultMessage: '',
        createImageThumbnails: false,
        previewsContainer: '#dropzone__hidden',
        acceptedFiles: '.js,.py,.php,.jar'
    }

     // Small wrapper for ProgressBar

    var RotatingProgressBar = function RotatingProgressBar(Shape, container, opts) {
        this._container = document.querySelector(container);
        this.bar = new Shape(container, opts);
    };

    RotatingProgressBar.prototype.rotate = function rotate() {
        addClass(this._container, 'rotating');
    };

    RotatingProgressBar.prototype.stopRotating = function stopRotating() {
        removeClass(this._container, 'rotating');
    };
    
    var rotatingBar = initProgressBar('#file-picker__progress');
    initDropzone(rotatingBar);


    function initProgressBar(container) {
        var Shape = ProgressBar.Circle;

        var rotatingBar = new RotatingProgressBar(Shape, container, {
            color: '#333',
            trailColor: '#eee',
            strokeWidth: 1,
            duration: 500
        });
        rotatingBar.bar.set(1);

        return rotatingBar;
    }

    function initDropzone(rotatingBar) {
        Dropzone.options.dropzone = DROPZONE_OPTIONS;
        var dropzone = new Dropzone('#dropzone');
        var picker = document.querySelector('.file-picker');
        var overlay = document.querySelector('.file-picker__overlay');
        overlay.onclick = function() {
            dropzone.removeAllFiles(true);
        }

        var animateThrottled = _.throttle(
            _.bind(rotatingBar.bar.animate, rotatingBar.bar),
            500
        );

        dropzone.on('sending', function(file, xhr, formData) {
            formData.append('id', $('#id').val());
            formData.append('idVersion', parseInt($('#selectVersion').val()));
            formData.append('idApp', parseInt($('#containerTreeApplication').attr('idApp')));
            addClass(picker, 'uploading');

            rotatingBar.bar.set(0.05);
            rotatingBar.rotate();
        });

        dropzone.on('uploadprogress', function(file, percent) {
            animateThrottled(percent / 100);
        });

        dropzone.on('success', function(file, response) {
            
			response = JSON.parse(response);
                            
            if(response['reussites']){
                setTimeout(function(){
                        location.reload();
                }, 3000);
                $('#overlayFormApplication').hide('drop', 600);
                displayInformationsClient(response);
                uploadFinally(false);

            }else if(response['erreurs']){
                displayInformationsClient(response);

                uploadFinally(true);
            }

            
        });

        dropzone.on('error', function(file, errorMessage) {
            uploadFinally(true);
        });

        function uploadFinally(err) {
            animateThrottled.cancel();

            if (err) {
                rotatingBar.bar.set(1);
                activateFilePicker();
                dropzone.removeAllFiles();
            } else {
                rotatingBar.bar.animate(1, function() {
                    dropzone.removeAllFiles();
                    activateFilePicker();
                });
            }
        }

        function activateFilePicker() {
            removeClass(picker, 'uploading');
            rotatingBar.stopRotating();
        }
    }


    // Utils

    function addClass(element, addName) {
        var classNames = element.className.split(' ');
        if (classNames.indexOf(addName) !== -1) {
            return;
        }

        element.className += ' ' + addName;
    }

    function removeClass(element, removeName) {
        var newClasses = [];
        var classNames = element.className.split(' ');
        for (var i = 0; i < classNames.length; ++i) {
            if (classNames[i] !== removeName) {
                newClasses.push(classNames[i]);
            }
        }

        element.className = newClasses.join(' ');
    }

}


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

// Pour gérer l'affichage des slider dans le panneau des paramètres de l'application
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
