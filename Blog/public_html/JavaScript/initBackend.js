(function(){
	// Pour afficher les infos bulles
	$('.infoBulle').tooltip({
		delay: {
			show: 800,
			hide: 100
		}, 
		placement:'right', 
		trigger:'hover'
	});

	$('.infoBulleGeneralMenuTop').tooltip({
		delay: {
			show: 800,
			hide: 100
		}, 
		placement:'top', 
		trigger:'hover'
	});

	$('.infoBulleBottom').tooltip({
		delay: {
			show: 800,
			hide: 100
		}, 
		placement:'bottom', 
		trigger:'hover'
	});
})();