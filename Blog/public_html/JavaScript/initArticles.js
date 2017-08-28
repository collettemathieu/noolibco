(function(){
	if($('.caseArticle').length != 0){
		
		setTimeout(function(){
			displayArticles();
		}, 300);
		

		$(window).resize(function(){
			$(document).ready(function(){
				setTimeout(function(){
					displayArticles();
				}, 300);
			});
			
		});

		function displayArticles(){
			var elems = $('.caseArticle'),
				marginBottom = parseInt($(elems[0]).css('marginBottom'));

				var parentWidth = $(elems[0]).parent().width()-40,
					sonWidth = $(elems[0]).outerWidth(),
					nbreCase = Math.floor(parentWidth/sonWidth);

			for(var i=nbreCase, c=elems.length; i<c; ++i){
				var elem = $(elems[i]),
					positionArticle = elem.offset(),
					topArticle = $(elems[i-nbreCase]),
					positionTopArticle = topArticle.offset(),
					heightTopArticle = topArticle.outerHeight(),
					difference = positionArticle.top - (positionTopArticle.top+heightTopArticle);
				
				if(difference>marginBottom+2){
					elem.offset({
						top: positionArticle.top - difference + marginBottom,
						left: positionArticle.left
					});
				}else if(difference<0){
					elem.offset({
						top: positionTopArticle.top + heightTopArticle + marginBottom,
						left: positionArticle.left
					});
				}
			}
		}

		// Pour afficher les infos bulles
		$('.infoBulleBottom').tooltip({
			delay: {
				show: 800,
				hide: 100
			}, 
			placement:'bottom', 
			trigger:'hover'
		});
	}
})();