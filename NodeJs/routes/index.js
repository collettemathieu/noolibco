var express = require('express');
var router = express.Router();
/* GET home page. */
  var Test= require('../app/User').init;
  var t1= new Test();
  var hello= require('../app/index.js')

router.get('/:id', function(req, res, next) {
       hello.runTheMule(req,res);
	var tit=t1.getUser(req.params.id,function(rows,err){
		if(err){
			 res.render('index', { title: "error" });
		}
		else{
			 res.render('index', { title: rows[0]['nom_utilisateur']});
		}
	});;
   
});

module.exports = router;
