var express = require('express');
var router = express.Router();

//Initialisation
  var Application= require('../app/Application').init;
  var application= new Application();
/* GET users listing. */
router.get('/:id', function(req, res, next) {
  application.getApplication(req.params.id,function(rows,err){
		if(err){
			 res.render('index', { title: "error" });
		}
		else{
			console.log(rows[0]);
			 res.render('index', { title: rows[0]['nom_application']});
		}
	});;
});

module.exports = router;
