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
application.directive('aceEditor', ['$compile', function($compile){
	return{
		restrict: 'A',
		link: function(scope, element, attrs){
			
			var editor = ace.edit(element[0]);
			editor.$blockScrolling = Infinity; // Remove warning
			editor.setHighlightActiveLine(false);
			editor.setValue(scope.textFunction, 1);
			scope.textEditor = editor.getValue();
			editor.setTheme('ace/theme/monokai'); // Edit the theme
			editor.getSession().on('change', function(e) {
			    scope.textEditor = editor.getValue();
			});
			// Edit the mode
			switch(scope.extFunction){
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
	};
}]);

