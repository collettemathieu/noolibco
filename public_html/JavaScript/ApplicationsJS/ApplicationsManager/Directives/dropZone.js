// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour créer une dropZone d'upload							  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  dropZone
 * @access: public
 * @version: 1
 */

application.directive('dropZone', function(){
	return{
		restrict: 'EA',
		scope: false,
		replace: true,
		priority: 1,
		templateUrl: '/JavaScript/ApplicationsJS/ApplicationsManager/Directives/Templates/dropZoneTemplate.html',
		link: function(scope, element, attrs){

			// Disabling autoDiscover, otherwise Dropzone will try to attach twice
		    Dropzone.autoDiscover = false;
		    var DROPZONE_OPTIONS = {
		        url: '/HandleApplication/ValidFormFonction',
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
		            // Initialisation des variables du formulaire
		            formData.append('idTache', scope.idTache);
		            formData.append('idVersion', scope.idVersion);
		            formData.append('idApp', scope.application.id);
		            addClass(picker, 'uploading');

		            rotatingBar.bar.set(0.05);
		            rotatingBar.rotate();
		        });

		        dropzone.on('uploadprogress', function(file, percent) {
		            animateThrottled(percent / 100);
		        });

		        dropzone.on('success', function(file, response) {
		            
		            // Récupération de la réponse
					response = JSON.parse(response);
		                            
		            if(response['reussites']){
		                
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
	};
});

