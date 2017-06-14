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

function strrchr(haystack, needle){
	var pos = 0;
	if(typeof needle !== 'string')
		needle = String.fromCharCode(parseInt(needle,10));

	needle = needle.charAt(0);
	pos = haystack.lastIndexOf(needle);

	if(pos === -1)
		return false;
	return haystack.substr(pos);
}
function escapeShell(cmd){
	cmd.replace(/\s+/g, " ");
	cmd.replace(/^\s+|\s+$/g, " ");
	return "'"+ cmd.replace(/(['\\])/g, '\\$1')+"'";
}
//****************************************
function executeRun(fields, applicationRunning,numVersionRunning,tacheDemandee,tabDonneeUtilisateur,filesPaths){
	
		var createur = applicationRunning.getCreateur();
		if(tacheDemandee instanceof Tache){
			
			var tacheDatas = tacheDemandee.getTacheTypeDonneeUtilisateurs().then(function(tacheDatas){
				//console.log(tacheDatas.length);
				//console.log(tabDonneeUtilisateur);
				if(tacheDatas.length === tabDonneeUtilisateur.length){
			
				var i=-1;
				
				tacheDatas.forEach(function(tacheData){
					i++;
					var donnee = tabDonneeUtilisateur[i];
					var typeDonnee = donnee.getTypeDonneeUtilisateur().getExtensionTypeDonneeUtilisateur();
					var typeAttendu = tacheData.getTypeDonneeUtilisateur().getExtensionTypeDonneeUtilisateur();
					
					if(typeAttendu != 'all'){
						//config //typeImageAutorises //extensionsImageAutorisees
						if(typeAttendu != 'all.image'){
							if(typeAttendu != 'all.image.without.dicom'){
								if(typeDonnee != typeAttendu){
									console.log("return 1 false");
									//error
									return false;
								}
								}else{
									// On retire l'élément 'dcm' du tableau des extensions

								}
							}else {
								// all.image
							}
						
					}
					
				});
			} else{
				//error
				
				console.log("return 2 false");
				return false;
			}
			});
			
			

			var fonctions = await(tacheDemandee.getFonctions());
			
			if(fonctions.length != 0){
				fonctions.forEach(function(fonction){
					var args=[];
					var params = [];
					var j=0;

					filesPaths.forEach(function(file){
						if(file == false){
							args.push(tabDonneeUtilisateur[j].getValeurInputDonneeUtilisateur());
						} else{
							args.push(strrchr(file, '/').substr(1));
						}
						++j;
					});		
						
						/*if(outputData != false || outputData = null){
							outputData = 'undefined';
						}
						else{
							outputData= escapeShell(outputData);
							console.log(outputData);
						}*/

					var parametres = await(fonction.getParametres());
					if(parametres!= false){
						parametres.forEach(function(parametre){
							if(fields[parametre.getIdParametre()] != undefined || parametre.getStatutPublicParametre()){
								var valueParam = fields[parametre.getIdParametre()][0];
								if(valueParam <= parametre.getValeurMaxParametre() && valueParam >= parametre.getValeurMinParametre()){
									params[parametre.getNomParametre()] = valueParam;
								}else{
									params[parametre.getNomParametre()]= parametre.getValeurDefautParametre;
								}
							} else{
								params[parametre.getNomParametre()]= parametre.getValeurDefautParametre();
							}
						});
				}
					//Execution du script bash
				});

			}
			
		}
}
//********Request

router.post('/', function(req, res) { 
	res.header("Access-Control-Allow-Origin","http://172.16.72.47");
	async (function(){
		var fields = await(getFormData(req));
		var currentUtilisateur=await(user.getUtilisateurById(fields['id'][0]));
		var currentApplication=await(application.getApplicationByIdWithAllParameters(fields['idApplication'][0]));
		if(currentApplication != false){

			var idAuteurs = [];
			var auteurs=await(currentApplication.getAuteurs());
			
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
					 				var inputDonneeUtilisateur=new InputDonneeUtilisateur( {'valeurInputDonneUtilisateur':idDataInput, 'typeDonneUtilisateur':typeDonneeUtilisateur});
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
		 		var versions = await(currentApplication.getVersions());

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
		 					var task = await(taches[j]);
		 					if(task.getNomTache() == nomTacheApplication){
		 						tacheDemandee = task;
		 						break;
		 					}
		 				}
		 
		 				if(tacheDemandee != 'undefined' && tacheDemandee != false){
		 					var nombreDeDonnee = await(tacheDemandee.getTacheTypeDonneeUtilisateurs()).length;
		 					
		 					var outputData = executeRun(fields, currentApplication, version.getNumVersion(), tacheDemandee, tabDonneeUtilisateur.slice(offset,offset+nombreDeDonnee),tabUrlDestinationDonneeUtilisateur.slice( offset, offset+nombreDeDonnee));
		 					offset = offset + nombreDeDonnee;
		 				}
		 				tacheDemandee = false;
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