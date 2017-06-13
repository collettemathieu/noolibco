//modules requis
var express = require('express');
var router = express.Router();
var bodyParser = require("body-parser");
var multiparty = require('multiparty');
var util = require('util');
var async=require('asyncawait/async');
var await=require('asyncawait/await');
//donnée de l'application
var DonneeUtilisateur= require('../models/DonneeUtilisateur');
var PDODonneeUtilisateur= new DonneeUtilisateur();
var TypeDonneeUtilisateur= require('../models/TypeDonneeUtilisateur');
var PDOTypeDonneeUtilisateur= new TypeDonneeUtilisateur();
var Application= require('../models/Application');
var application= new Application();
var User= require('../models/User');
var user= new User();
var Version=require('../models/Version');
var PDOVersion=new Version();
var Tache= require('../models/Tache');
var tache= new Tache();
var UtilisateurDonneeUtilisateur= require('../models/UtilisateurDonneeUtilisateur');
var InputDonneeUtilisateur= require('./InputDonneeUtilisateur');

//variable de stockage
var abonnement_user=true;


//***************************
async function getFormData(req){
		var promise= await new Promise(function(resolve,reject){
		var form = new multiparty.Form();
		form.parse(req,function(err,fields){
	 		if(err) {
	 			console.log(err);
	 			return err;
	 		}

	 		resolve(fields);
	 	});
	 	});
	 	return promise;
	}

	
//****************************************
async function executeRun(request, applicationRunning,numVersionRunning,tacheDemandee,tabDonneeUtilisateur,filesPaths){
	
		var createur = applicationRunning.getCreateur();
		if(tacheDemandee instanceof Tache){
			var tacheDatas = await(tacheDemandee.getTacheTypeDonneeUtilisateurs());
			
			if(tacheDatas.length === tabDonneeUtilisateur.length){
				var i=0;
				tacheDatas.forEach(function(tacheData){
					var donnee = tabDonneeUtilisateur[i];
					var typeDonnee = donnee.getTypeDonneeUtilisateur().getExtensionTypeDonneeUtilisateur();
					var typeAttendu = tacheData.getTypeDonneeUtilisateur().getExtensionTypeDonneeUtilisateur();
					if(typeAttendu != 'all.image'){
						//config 
						if(typeAttendu != 'all.image.without.dicom'){
							if(typeDonnee != typeAttendu){
								//error
								return false;
							}
						}else{


						}
					}
					++i;
				});
			} else{
				//error
				return false;
			}
			var fonctions = await(tacheDemandee.getFonctions());
			if(fonctions != false){
				fonctions.forEach(function(fonction){
					var args=[];
					var params = [];
					var i=0;

					filesPaths.forEach(function(file){
						if(file != undefined){
							args.push(tabDonneeUtilisateur[i]);
							console.log(tabDonneeUtilisateur[i]); // I m here
						}
					});

				});
			}
			
		}
}
//********Request

router.post('/', function(req, res) { 
	res.header("Access-Control-Allow-Origin","http://**.**.**.**");
	async (function(){
		var fields = await(getFormData(req));
		var currentUtilisateur=await(user.getUtilisateurById(fields['id'][0]));
		var currentApplication=await(application.getApplicationByIdWithAllParameters(fields['idApplication'][0]));
		if(currentApplication != false){

			var idAuteurs = [];
			var auteurs=currentApplication.getAuteurs();

			if(auteurs != false){
				auteurs.forEach(function(itemAuteur){
				var mailAuteur= itemAuteur.getMailAuteur();
				if(mailAuteur != false){
					var utilisateurAuteur=await(user.getUtilisateurByMail(mailAuteur));

					if(utilisateurAuteur != false){
						idAuteurs.push(utilisateurAuteur.getIdUtilisateur());
					}
				
				}
			});
			}
			
			var abonnenementUser = true;
			if(abonnenementUser || fields['isAdmin'][0] ){
				//console.log(idAuteurs);
				if(currentApplication.getIdStatut() > 4 || fields['isAdmin'][0] || idAuteurs.indexOf(fields['id'][0]) ){
					var i=0, j=0, tabDonneeUtilisateur=[], noError= true;

					while(fields["tache"+i+"data"+j] != undefined){
		 				j=0;
		 				while(fields["tache"+i+"data"+j] != undefined){
					 			if(fields["tache"+i+"data"+j][0].indexOf('noolibData_')!= -1 ){
					 				var idData = fields["tache"+i+"data"+j][0].replace("noolibData_","");
					 				var donneeUtilisateur = await(PDODonneeUtilisateur.getDonneeUtilisateurById(idData));
					 				
					 				if(donneeUtilisateur != false){
					 					var managerUtilisateurDonneeUtilisateur = new UtilisateurDonneeUtilisateur();
					 					var utilisateurDonneeUtilisateur = await(managerUtilisateurDonneeUtilisateur.getUtilisateurDonneeUtilisateurById(fields['id'][0],donneeUtilisateur.getIdDonneeUtilisateur()));
		 								
					 					if(utilisateurDonneeUtilisateur != false){
					 						tabDonneeUtilisateur.push(donneeUtilisateur);
					 					}else{
					 						noError = false;
					 					}
					 				}else{
					 					noError = false;
					 				}
			 					}
					 			else{
					 			
					 				var idDataInput = fields["tache"+i+"data"+j][0];
					 				var typeDonneeUtilisateur=await(PDOTypeDonneeUtilisateur.getTypeDonneeUtilisateurByExtension('input.txt'));
					 				var inputDonneeUtilisateur=new InputDonneeUtilisateur( idDataInput, typeDonneeUtilisateur);
					 				tabDonneeUtilisateur.push(inputDonneeUtilisateur);
					 				
					 			}
		 					
		 				
		 					j++;
		 			}
		 	
		 		i++;j=0;
		 	}

		 	if(noError){

		 		var tabUrlDestinationDonneeUtilisateur = [];
		 		tabDonneeUtilisateur.forEach(function(donneeUtilisateur){
		 			if(donneeUtilisateur instanceof DonneeUtilisateur && !(donneeUtilisateur instanceof InputDonneeUtilisateur)){
		 				tabUrlDestinationDonneeUtilisateur.push(donneeUtilisateur.getUrlDonneeUtilisateur());
		 			} else {
		 				tabUrlDestinationDonneeUtilisateur.push(false);
		 			}
		 		});
		 		// On récupère la version demandée si admin/auteurs ou la dernière version active de l'application
		 		var idVersion=fields['idVersion'][0];
		 		var version;
		 		var versions = currentApplication.getVersions();

		 		if(idVersion != 'undefined' && idAuteurs.indexOf(fields['id'][0]) ){
		 			for(var i=0; i<versions.length ; ++i){
		 				if(versions[i].getIdVersion() == idVersion){
		 					version = versions[i];
		 					break;
		 				}
		 			}
		 			
		 			

		 		} else{
		 			for(var i=0; i<versions.length ; ++i){
		 				if(versions[i].getActiveVersion()){
		 					version = versions[i];
		 					break;
		 				}
		 			}
		 		}
		 		if(version != 'undefined' && version != null){
		 			var i=0, tabTaches= [], noError=true , offset= 0, tacheDemandee;
		 			var taches= await(version.getTaches());
		 			while(fields['tache'+i] != undefined){
		 				var nomTacheApplication = fields['tache'+i][0];
		 				
		 				for(var j=0; j<taches.length;++j){
		 					if(taches[j].getNomTache() == nomTacheApplication){
		 						tacheDemandee = taches[j];
		 						break;
		 					}
		 				}
		 				if(tacheDemandee != 'undefined' && tacheDemandee != null){
		 					var nombreDeDonnee = await(tacheDemandee.getTacheTypeDonneeUtilisateurs()).length;
		 					var outputData = executeRun(fields, currentApplication, version.getNumVersion(), tacheDemandee, tabDonneeUtilisateur.slice(offset,nombreDeDonnee),tabUrlDestinationDonneeUtilisateur.slice( offset, nombreDeDonnee));
		 					offset = offset + nombreDeDonnee;
		 				}
		 				++i;
		 			}

		 			
		 		}
		 	}
		 	
		 	res.send(tabDonneeUtilisateur);
					
				}
			}
			
		}
	})();
});


module.exports = router;