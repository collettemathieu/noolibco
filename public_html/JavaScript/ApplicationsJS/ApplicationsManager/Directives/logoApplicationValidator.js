// +----------------------------------------------------------------------+
// | AngularJS Version 1.5.9						                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Directive pour contrôler le logo de l'application.			 		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  logoApplicationValidator
 * @access: public
 * @version: 1
 */

application.directive('logoApplicationValidator', ['$q', '$http', function($q, $http){
	return{
		restrict: 'A',
		require: 'ngModel',
		link: function(scope, element, attrs, ngModelCtrl){

			// On initialise la variable logoApplication
			scope.logoApplication = false;

			// On écoute l'élement input file
			element[0].addEventListener('change', function(e){
				
				var element = e.target;
					files = element.files,
					filesLen = files.length,
					response = {},
					allowedTypes = ['png','jpg'],
					affichageMiniatureLogo = document.getElementById('affichageMiniatureLogo');

				if(affichageMiniatureLogo.hasChildNodes()){
					affichageMiniatureLogo.removeChild(affichageMiniatureLogo.firstChild);
				}

				if(filesLen > 1){
					response['erreurs'] = '<p>Only one icon can be selected !</p>';
					setTimeout(function(){
						displayInformationsClient(response);
					}, 400);
					scope.logoApplication = false;
				}else{
					
					var imgType = files[0].name.split('.');
					imgType = imgType[imgType.length-1].toLowerCase();

					if(allowedTypes.indexOf(imgType) != -1){
						if(files[0].size>12000){
							
							response['erreurs'] = '<p>The icon selected exceeds the limit authorized (12 ko).</p>';
							setTimeout(function(){
								displayInformationsClient(response);
							}, 400);
							scope.logoApplication = false;
						}else{
							
							var reader = new FileReader();
		
							reader.addEventListener('load', function(){
								var img = document.createElement('img');
								img.style.maxWidth = '64px';
								img.style.maxHeight = '64px';
								img.src = this.result;
								img.style.display = 'inline-block';
								affichageMiniatureLogo.appendChild(img);	
								affichageMiniatureLogo.classList.add('img-thumbnail');
							}, false);

							reader.readAsDataURL(files[0]);
							scope.logoApplication = true;
						}
					}else{
						response['erreurs'] = '<p>The icon selected must be in PNG or JPEG format.</p>';
						setTimeout(function(){
							displayInformationsClient(response);
						}, 400);
						scope.logoApplication = false;
					}
				}
				// On force l'actualisation de la variable logoApp pour le validator de ngModel
				scope.$apply(function(){
					ngModelCtrl.$setViewValue(scope.logoApplication);
				});

			}, false);
	
			// Ajout du validateur pour le logo au ngModel
			ngModelCtrl.$validators.logoApplication = function(value){return value;}
		}
	};
}]);

