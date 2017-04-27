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
			
			var editor = ace.edit(element[0]);
			editor.$blockScrolling = Infinity; // Remove warning
			editor.setHighlightActiveLine(true); // Underline
			if(typeof scope.textFunction != 'undefined'){
				editor.setValue(scope.textFunction, 1);
			}
			scope.textEditor = editor.getValue();
			editor.setTheme('ace/theme/monokai'); // Edit the theme
			editor.getSession().on('change', function(e) {
			    scope.textEditor = editor.getValue();
			});

			switchMode(scope.extFunction); //Chgt de mode

			// On s'abonne à l'évènement de la dropZone
			scope.$on('dropEnded', function(evt, value){
				if(value){
					// On récupère le texte de la fonction
					$http({
						method: 'POST',
						url: '/HandleApplication/GetTextFunction',
						headers: {'Content-Type': 'application/x-www-form-urlencoded'},
						transformRequest: function(obj) {
					        var str = [];
					        for(var p in obj)
					        	str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
					        return str.join("&");
					    },
						data: {
							idFunction: scope.idFunction,
							idApp: scope.application.id,
							idVersion: scope.idVersion
						}
					})
					.success(function(response){
						editor.setValue(response['text'], 1);
						switchMode(response['ext']);
					})
					.error(function(error){
						var response = {
							'erreurs': '<p>A system error has occurred: '+error+'</p>'
						};
						displayInformationsClient(response);
					});
				}
			});

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

