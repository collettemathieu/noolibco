//modules requis
var express = require('express');
var router = express.Router();
var bodyParser = require("body-parser");
var multiparty = require('multiparty');
var util = require('util');
//donnée de l'application
var Application= require('./Application').init;
var application= new Application();
var User= require('./User').init;
var user= new User();

//variable de stockage
var abonnement_user=true;


router.post('/', function(req, res) { 
	
	res.header("Access-Control-Allow-Origin","http://x.x.x.x");
	
				
	 function getInformation(){
	 	var tache_data=[];
	 	var i=0,j=0;
	 	return new Promise(function(resolve,reject){
	 		//pour lire le formData
	 		var form = new multiparty.Form();
	 		form.parse(req,function(err,fields){
	 			if(err) return reject(err);
	 			
	 			
	 				while(fields["tache"+i+"data"+j] != undefined){
	 					var test={};
	 				j=0; test.tache=fields["tache"+i][0];test.donnees=[];
	 				while(fields["tache"+i+"data"+j] != undefined){
	 					test.donnees.push(fields["tache"+i+"data"+j][0]);
	 					j++;
	 				}
	 				tache_data.push(test);
	 				i++;
	 				}
	 				
	 			var allPromise=Promise.all([application.getApplication(fields['idApplication'][0]),application.isAuteur(fields['id'][0],fields['idApplication'][0])]);
	 			allPromise.then(function(resultat){
	 				var obj={};
	 				if(resultat[0]['id_createur']===fields['id'][0]){
	 					obj.isCreateur="true";
	 				}
	 				else{
	 					obj.isCreateur="false";
	 				}
	 				obj.isAdmin= fields['isAdmin'][0];
	 				obj.id_statut=resultat[0]['id_statut'];
	 				obj.isAuteur=resultat[1];
	 				return resolve(tache_data);
	 			});
	 		});
	 	});
	 }
	 

 	getInformation().then(function(resultat){
 		//on recupere les champs de résultat
 		var isAuteur=resultat['isAuteur'],
 			isCreateur=resultat['isCreateur'],
 			statutApplication=resultat['id_statut'],
 			isAdmin=resultat['isAdmin'];


 		if(abonnement_user || isAdmin){
 			if(isCreateur || isAuteur || isAdmin || statutApplication>4){

 			}
 			
 		
 		}
 		res.send(resultat);
 	},function(){
 		res.send('erreur');
 	});
	
   					
});


module.exports = router;