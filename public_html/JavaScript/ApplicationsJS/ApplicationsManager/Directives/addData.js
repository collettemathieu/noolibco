// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour créer une nouvelle donnée au formulaire des taches	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  addData
 * @access: public
 * @version: 1
 */
application.directive('addData', ['$compile', function($compile){
	return{
		restrict: 'A',
		link: function(scope, element, attrs){
			element.on('click', function(e){
				e.preventDefault();
				// Création du nouvel élément
				var divNode = $(this).parent().find('div[class="jumbotron"]:last'),
		            num_data = $(this).parent().find('div[class="jumbotron"]').length,
		            divClone = divNode.clone();
		        divClone.find('select:first').attr('name', 'typeDonneeUtilisateur'+num_data).val('');
		        divClone.find('select:last').attr('name', 'uniteDonneeUtilisateur'+num_data).val('').removeAttr('disabled');
		        divClone.find('select:first').attr('ng-model', 'typeDonneeUtilisateur'+num_data);
		        divClone.find('select:last').attr('ng-model', 'uniteDonneeUtilisateur'+num_data);
		        divClone.find('input').attr('name', 'description'+num_data).val('');
		        divClone.find('input').attr('ng-model', 'description'+num_data);
		        divClone.find('div:first').attr('ng-class', '{"has-error":formNewTask.description'+num_data+'.$invalid && formNewTask.description'+num_data+'.$dirty, "has-success":formNewTask.description'+num_data+'.$valid}');
		        divClone.find('span:first').attr('ng-class', '{"glyphicon form-control-feedback glyphicon-remove":formNewTask.description'+num_data+'.$invalid && formNewTask.description'+num_data+'.$dirty, "glyphicon form-control-feedback glyphicon-ok":formNewTask.description'+num_data+'.$valid}');
		        divClone.find('span:last').attr('ng-show', 'formNewTask.description'+num_data+'.$invalid && formNewTask.description'+num_data+'.$dirty');
		        
		        // Insertion du nouvel élément
		        divClone.insertAfter(divNode);
		        if(num_data == 1){
		            $(this).parent().find('button:first').removeAttr('disabled');
		        }

		        // Mettre à jour les mécanismes d'Angular sur le nouvel élément
		        $compile(divClone)(scope);
			});
		}
	};
}]);

