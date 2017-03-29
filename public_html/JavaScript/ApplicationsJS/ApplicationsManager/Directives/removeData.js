// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour supprimer une nouvelle donnée au formulaire des taches|
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  removeData
 * @access: public
 * @version: 1
 */
application.directive('removeData', function(){
	return{
		restrict: 'A',
		link: function(scope, element, attrs){
			element.on('click', function(e){
				e.preventDefault();
                var divNode = $(this).parent().find('div[class="jumbotron"]'),
                	name = $(divNode[divNode.length-1]).find('input').attr('name');
                if(divNode.length > 1){
                    $(divNode[divNode.length-1]).remove();
                    if(divNode.length == 2){
                        $(this).attr('disabled', 'disabled');
                    }
                }

                // On supprime l'élément d'AngularJS
               	console.log(scope.formNewTask);
			});
		}
	};
});

