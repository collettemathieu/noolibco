//modules requis
var express = require('express');
var router = express.Router();
var bodyParser = require("body-parser");
var multiparty = require('multiparty');
var util = require('util');
var async= require("async");
//donnée de l'application
var DonneeUtilisateur= require('./DonneeUtilisateur').init;
var donneeUtilisateur= new DonneeUtilisateur();
var TypeDonneeUtilisateur= require('./TypeDonneeUtilisateur').init;
var typeDonneeUtilisateur= new TypeDonneeUtilisateur();
var Application= require('./Application').init;
var application= new Application();
var User= require('./User').init;
var user= new User();

//variable de stockage
var abonnement_user=true;
router.post('/', function(req, res) { 
	res.header("Access-Control-Allow-Origin","http://172.16.72.47");
	function getFormData(){
		return new Promise(function(resolve,reject){
		var form = new multiparty.Form();
		form.parse(req,function(err,fields){
	 		if(err) {
	 			console.log(err);
	 			return reject(err);
	 		}
	 		return resolve(fields);
	 	});
	 	});
	}
	function getData(fields){
		 var tache_data=[];

		return new Promise(function(resolve,reject){
			var i=0,j=0,test={};
		 	while(fields["tache"+i+"data"+j] != undefined){
		 		var j=0; test.tache=fields["tache"+i][0];test.donnees=[];
		 		while(fields["tache"+i+"data"+j] != undefined){
		 			if(fields["tache"+i+"data"+j][0].indexOf('noolibData_')!= -1){
		 				var idData= fields["tache"+i+"data"+j][0].replace("noolibData_","");
		 				test.donnees.push(donneeUtilisateur.getUtilisateurDonneeUtilisateurById(j,fields['id'][0],idData).then(function(resultat){
		 					return resultat;
		 				}));
		 			}
		 			else{
		 				var idDataInput = fields["tache"+i+"data"+j][0];
		 				test.donnees.push(typeDonneeUtilisateur.getTypeDonneeUtilisateurByExtension(j,idDataInput,'input.txt'));
		 			}
		 			j++;
		 		}
		 	(function(){
		 		var k=i;
		 		tache_data.push(Promise.all(test.donnees).then(function(resultat){
		 			 return {'tache':fields["tache"+k][0],'donnee':resultat};
		 		}));
		 	})();
		 	i++;
		 	}
		 	return resolve(Promise.all(tache_data).then(function(resultat){
		 		return resultat;
		 	}));			
	 	});
	}
					
 function getInformation(fields){
	 	return new Promise(function(resolve,reject){
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
	 			return resolve(obj);
	 		});
	 	});
	 }

function getVersion(fields, isCreateur, isAuteur){
	var idVersion=fields['idVersion'][0];
	return new Promise(function(resolve,reject){
	if(idVersion != 'undefined'){
		application.getVersions(fields['idApplication'][0]).then(function(rows){
			rows.forEach(function(row){
				if(row['id_version']==idVersion){
					return resolve(row['id_version']);
				}
			});
			return resolve(false);
		});
	} else{
		application.getVersions(fields['idApplication'][0]).then(function(rows){
			rows.forEach(function(row){
				if(row['active_version']){
					return resolve(row['id_version']);
				}
			});
			return resolve(false);
		});
	}
});
}	 


 getFormData().then(function(fields){
 	getInformation(fields).then(function(resultat){
 		//on recupere les champs de résultat
 		var isAuteur=resultat['isAuteur'],
 			isCreateur=resultat['isCreateur'],
 			statutApplication=resultat['id_statut'],
 			isAdmin=resultat['isAdmin'];
 		var tabUrlDestinationDonneeUtilisateur=[];
 		var	tabDonneeUtilisateur= getData(fields);

 		if(abonnement_user || isAdmin){
 			if(isCreateur || isAuteur || isAdmin || statutApplication>4){
 				tabDonneeUtilisateur.then(function(donnees){
 					donnees.forEach(function(item){
 						item["donnee"].forEach(function(item){
 								if(item['donneeUtilisateur']!= undefined){
 									tabUrlDestinationDonneeUtilisateur.push(item['donneeUtilisateur']['urlDonneeUtilisateur']);
 								}
 								else{
 									tabUrlDestinationDonneeUtilisateur.push(false);
 								}
 						});
 					});
 				});
 				//**********************************************************************
 				getVersion(fields,isCreateur,isAuteur).then(function(resultat){
 					if(resultat != false){
 						var i=0;
 						var tacheDemandee;
 						var taches= application.getTaches(resultat);
 						taches.then(function(taches){
 							while(fields['tache'+i]!= undefined){
 								var nomTacheApplication = fields["tache"+i][0];
 								taches.forEach(function(tache){
 									application.getNomTache(tache['id_tache']).then(function(nomTache){
 										if(nomTache==nomTacheApplication){
 											tacheDemandee=nomTacheApplication;
 											console.log(tacheDemandee);

 										}
 									});
 								});
 								
 								setTimeout(function(){
 									console.log(tacheDemandee);
 								},50); 
 								
 								++i;
 							}
 						});
 					}
 				});
 				//************************************************************************

 			}
 		}
 		res.send(resultat);
 	},function(){
 		res.send('erreur');
 	});
 	});

 	
	
   					
});


module.exports = router;