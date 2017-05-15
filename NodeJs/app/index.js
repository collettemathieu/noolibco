var express = require('express');
var router = express.Router();
var bodyParser = require("body-parser");


/*var Engine=(function(){
	function _runTheMule(request,response){
		var isAjaxRequest=request.xhr;
		if(isAjaxRequest){
			//normalement le travail doit etre effectuer ici
		}
		else{
			
		}
	};
	return{
		runTheMule: _runTheMule
	};
})();
module.exports = Engine;*/

router.get('/test', function(req, res) {    
			var obj={};
			obj.title='test';
			obj.data='hello';

			console.log("req.query=  "+JSON.stringify(req.query.object));

			res.send(req.query.callback+'('+JSON.stringify(obj)+');');
			
			res.end();
		});

module.exports = router;