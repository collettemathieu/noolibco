var Engine=(function(){
	function _runTheMule(request,response){
		var isAjaxRequest=request.xhr;
		if(isAjaxRequest){
			console.log('I im an ajax request');
		}
		else{
			console.log("i m not an ajax request")
		}
	};
	return{
		runTheMule: _runTheMule
	};
})();
module.exports = Engine;