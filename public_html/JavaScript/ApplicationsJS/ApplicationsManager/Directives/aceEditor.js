// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour créer un éditeur de texte pour les fonctions		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  addData
 * @access: public
 * @version: 1
 */
application.directive('aceEditor', ['$compile', '$http', function($compile, $http){
	return{
		restrict: 'A',
		link: function(scope, element, attrs){

			// On contrôle si le format du fichier est du JAR ou non
			if(typeof scope.textFunction != 'undefined'){
				var editor = ace.edit(element[0]);
				editor.$blockScrolling = Infinity; // Remove warning
				editor.setHighlightActiveLine(true); // Underline
				editor.setValue(scope.textFunction, 1);
				scope.textEditor = editor.getValue();
				editor.setTheme('ace/theme/monokai'); // Edit the theme
				editor.getSession().on('change', function(e) {
				    scope.textEditor = editor.getValue();
				});

				switchMode(scope.extFunction); //Chgt de mode
			}else{
				element.removeClass('editor');
				element.addClass('alert alert-danger');
				element.append('We cannot display this type of file (such as JAR file).');
			}

			// Edit the mode
			function switchMode(ext){
				switch(ext){
					case 'py':
						editor.getSession().setMode('ace/mode/python');
						break;
					case 'jar':
						editor.getSession().setMode('ace/mode/java');
						break;
					case 'java':
						editor.getSession().setMode('ace/mode/java');
						break;
					case 'm':
						editor.getSession().setMode('ace/mode/matlab');
						break;
					case 'js':
						editor.getSession().setMode('ace/mode/javascript');
						break;
					case 'php':
						editor.getSession().setMode('ace/mode/php');
						break;
					default:
						editor.getSession().setMode('ace/mode/javascript');
				}
			}
			
		}
	};
}]);

