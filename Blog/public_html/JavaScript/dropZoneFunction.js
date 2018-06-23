export default function dropZoneFunc(nameOfParameter, URL, extensionFiles){

    // Disabling autoDiscover, otherwise Dropzone will try to attach twice
    Dropzone.autoDiscover = false;
    var DROPZONE_OPTIONS = {
        url: URL,
        paramName: nameOfParameter,  // The name that will be used to transfer the file
        maxFiles: 1,
        maxFilesize: 5,  // MB
        dictDefaultMessage: '',
        createImageThumbnails: false,
        previewsContainer: '#dropzone__hidden',
        acceptedFiles: extensionFiles
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