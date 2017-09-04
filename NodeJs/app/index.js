//modules requis
var express = require('express');
var router = express.Router();
var bodyParser = require("body-parser");
var multiparty = require('multiparty');
var util = require('util');
var exec = require('child_process').exec;//Sync;
var async=require('asyncawait/async');
var await=require('asyncawait/await');
var fs=require('fs');
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
var UtilisateurDonneeUtilisateur= require('../models/UtilisateurDonneeUtilisateur');
var InputDonneeUtilisateur= require('./InputDonneeUtilisateur');
var Fonction=require('../models/Fonction');
var Config=require('../models/Config');
var DonneeResultat= require('../models/DonneeResultat');
var client = require('redis').createClient();
var limiter = require('express-limiter')(router, client);
limiter({
	path:'/',
	method:'post',
	lookup:['connection.remoteAddress'],
	total:5,// 5 requests per minute
	expire: 1000*60 // in ms
});



//variable de stockage
var abonnement_user=true;
var numRequest=0;
//***Const
const ENGINE_NO_MATCH_DATA = 'Data entered does not match with the task called. Please verify data loaded on the mule.';
const TREE_VERSION_NOT_ACTIVATED = 'Sorry, any version of this application is activated. Please, stay tuned!';
const FORMAT_TACHE = "The parameter passed to the function is not in /Library/Entities/Tache format.";
const APPLICATION_NOT_ACTIVATED = 'This application is not activated. Please stay tuned!';
const ENGINE_NO_DATA_MULE = 'Please, load all data on the mule before running a task.';
const ENGINE_NO_DATA = 'No data has been found in the data box of the application.';
const DENY_USE_APPLICATION = 'You are not authorized to use this application.';
const ENGINE_NO_ACTION_FOR_TASK = 'No action is associated to this task.';
const DENY_HANDLE_DATA = 'You are not authorized to edit this data.';
const NO_APPLICATION = 'The application asked does not exist.';
const NO_TASK = 'The task asked does not exist.';
//***************************
async function getFormData(req){
		var promise= await new Promise(function(resolve,reject){
		var form = new multiparty.Form();
		form.parse(req,function(err,fields){
	 		if(err) {
	 			//console.log(err);
	 			return err;
	 		}

	 		resolve(fields);
	 	});
	 	});
	 	return promise;
	}
function utf8Encode(unicodeString){
	const utf8String = unicodeString.replace(
		/[\u0080-\u07ff]/g, 
		function(c){
			var cc = c.charCodeAt(0);
			return String.fromCharCode(0xc0 | cc>>6, 0x80 | cc&0x3f);
		}).replace(
		/[\u0800-\uffff]/g,
		function(c){
			var cc = c.charCodeAt(0);
			return String.fromCharCode(0xe0 | cc>>12, 0x80 | cc>>6&0x3F, 0x80 | cc&0x3f);
			} 
		);
		return utf8String;
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
	cmd=cmd.toString();
	cmd.replace(/\s+/g, " ");
	cmd.replace(/^\s+|\s+$/g, " ");
	return "'"+ cmd.replace(/(['\\])/g, '\\$1')+"'";
}
//****************************************
function executeRun(nbReq,fields,currentUtilisateur, applicationRunning,numVersionRunning,tacheDemandee,tabDonneeUtilisateur,filesPaths,messageClient){
	
		var createur = applicationRunning.getCreateur();
		var outputData;
		var error = false;
		if(tacheDemandee instanceof Tache){
			
			var tacheDatas = await(tacheDemandee.getTacheTypeDonneeUtilisateurs());

			if(tacheDatas.length === tabDonneeUtilisateur.length){
				var i=-1;
				
				tacheDatas.forEach(function(tacheData){
					i++;
					var donnee = tabDonneeUtilisateur[i];
					var typeDonnee ;
					var typeAttendu = tacheData.getTypeDonneeUtilisateur().getExtensionTypeDonneeUtilisateur();
					if(donnee instanceof DonneeResultat ){
						console.log(typeAttendu);
							typeDonnee = typeAttendu;
							if(typeDonnee == 'csv'){

							}
							if(typeDonnee == 'all.image'){
								console.log("here");
								var resultFile = fs.readFile(donnee.getUrlDonneeUtilisateur(),'utf8',function(err){});
								console.log(resultFile);
							}
					}else{
						 typeDonnee = donnee.getTypeDonneeUtilisateur().getExtensionTypeDonneeUtilisateur();
					}
					if(typeAttendu != 'all'){
						// On charge le fichier de configuration
						var config = new Config();
						var varConfig =await(config.getVar('donneeUtilisateur','typeImage','type'));
						// On récupère les extensions autorisées
						var extensionsImageAutorisees  = varConfig.split(',');

						if(typeAttendu != 'all.image'){
							
							if(typeAttendu != 'all.image.without.dicom'){
								if(typeDonnee != typeAttendu){
									messageClient.addErreur(tacheDemandee.getNomTache() +': '+ ENGINE_NO_MATCH_DATA);
									error = true;							
								}
							}else{
								extensionsImageAutorisees.splice(extensionsImageAutorisees.indexOf('dcm'),1);
								extensionsImageAutorisees.splice(extensionsImageAutorisees.indexOf('dcm'),1);
									if(extensionsImageAutorisees.indexOf(typeDonnee)<0){
										messageClient.addErreur(tacheDemandee.getNomTache() + ': '+ENGINE_NO_MATCH_DATA);
										error = true;
									}														
							}
						}else {
							if(extensionsImageAutorisees.indexOf(typeDonnee)<0){
								messageClient.addErreur(tacheDemandee.getNomTache() + ': '+ENGINE_NO_MATCH_DATA);
								error = true;
							}
						}
					}
				});
			} else{
				messageClient.addErreur(tacheDemandee.getNomTache()+ ': '+ENGINE_NO_MATCH_DATA);
				error = true;
			}
			if(!error){
			var fonctions = await(tacheDemandee.getFonctions());
			if(fonctions != false){
			 var nbrFunction=0;
				fonctions.forEach(function(fonction){
					var args=[];
					var params = [];
					var j=0;
					nbrFunction +=1;

					filesPaths.forEach(function(file){

						if(file == false){
							args.push(tabDonneeUtilisateur[j].getValeurInputDonneeUtilisateur());
						} else{
							args.push(strrchr(file, '/').substr(1));
						}
						++j;
					});		
						

						if(outputData != undefined ) {
							outputData= escapeShell(outputData);
						}

					// On récupère les paramètres de la fonction modifiés (ou non) par l'utilisateur
					var parametres = await(fonction.getParametres());
					if(parametres!= false){
						parametres.forEach(function(parametre){
							
							if(fields[parametre.getIdParametre()] != undefined && parametre.getStatutPublicParametre()){
								var valueParam = fields[parametre.getIdParametre()];
								if(valueParam <= parametre.getValeurMaxParametre() && valueParam >= parametre.getValeurMinParametre()){
									//params[parametre.getNomParametre()] = valueParam;
									params.push(valueParam);

								}else{
									params.push(parametre.getValeurDefautParametre);
								}
							} else{
								params.push(parametre.getValeurDefautParametre());
							}
						});
					}
					// On transforme le tableau d'arguments en chaine de caratères
					args = args.join('§');
					params = params.join('§');

			        if(params != ''){
			        	args = args +'§'+ params + '§' + outputData;
			        }else{
			        	args= args+ '§'+ outputData;
			        }
			      
			        // Execution du script Bash pour executer une fonction de l'application
			        outputData=await(execFct(nbReq,createur,currentUtilisateur, applicationRunning, numVersionRunning, fonction, args));	
			      	var result = outputData.toString().trim();
			        result= result.split('<br>').join('').split('<br />').join('').split("\n").join('').split("\r" ).join('');
			       	result=unescape(encodeURIComponent(result));
			       	
			    	try{
			       		result= JSON.parse(result);	
			       		 if(outputData != undefined && outputData != ""){
			         	     	outputData = outputData;
				         }else{
				         	outputData=false;
				         }
			       	}catch (e){
			       		outputData = '{"erreurs": "'+outputData+'"}';
			       	}  

				});
			 await(delFolderInProd(currentUtilisateur,nbReq));
			}
			else{
				messageClient.addErreur(ENGINE_NO_ACTION_FOR_TASK);
			}
			return outputData;
			}else{
				return false;
			}
			//****************
			
		}else{
			messageClient.addErreur(FORMAT_TACHE);
		}
			
			
		
}


execFct = function(nbReq,createur, utilisateur, application, numVersion,fonction, args){
	return new Promise(function(resolve,reject){
		if(createur instanceof User && utilisateur instanceof User && application instanceof Application && fonction instanceof Fonction){
			var nomUtilisateur = utilisateur.getVariableFixeUtilisateur();
			var nomCreateur = createur.getVariableFixeUtilisateur();
			var nomApplication = application.getVariableFixeApplication();
			var nameFunction = strrchr(fonction.getUrlFonction(),'/').substr(1);
			var instructions = '/home/noolibco/Library/ScriptsBash/Debian/LancementApplicationServeurProd '+nomCreateur+' '+nomUtilisateur+' '+nomApplication+' '+numVersion+' '+nameFunction+' '+nbReq+' '+args;
				console.log(instructions);
				var resultat=exec(instructions + '2>&1', {maxBuffer: 1024*50000} ,async function(err,stdout,stderr){
					if(err)  return resolve(err);
					if(stderr){
						return resolve(stderr);
					}
					if(stdout){
						return resolve(stdout);
					}
					
				});
		}else{
			return reject("erreur exec fct");
		}
	});	
}	

delFolderInProd = function (utilisateur,nbReq){
	return new Promise(function(resolve,reject){
	 	if(utilisateur instanceof User){
	 			var instructions = '/home/noolibco/Library/ScriptsBash/Debian/SuppressionUtilisateurInProd '+utilisateur.getVariableFixeUtilisateur()+' '+nbReq;
	 			//console.log(instructions);
				exec(instructions,function(err){
					if(err) return resolve(err);
					return resolve("done");
					
				});
			
			
		}else{
			return reject("error delFolderInProd");
		}
	});
}

//*********************************** Combine Apllication 
GetDataUrl = function (fields, idUser, messageClient){
	var i=0, j=0;
	var noError=true;
	var currentUtilisateur=await(user.getUtilisateurById(idUser));
	var tabDonneeUtilisateur=[];
	while(fields["tache"+i+"data"+j] != undefined){
		 			while(fields["tache"+i+"data"+j] != undefined){
		 					if(typeof fields["tache"+i+"data"+j] == 'object'){
		 						var outputData =GetDataUrl(fields["tache"+i+"data"+j],idUser,messageClient);
		 						outputData = outputData.toString().trim();
										outputData = outputData.split('<br>').join('');
										outputData=outputData.split('<br />').join('');
									    outputData= outputData.split("\n").join('');
									    outputData= outputData.split("\r" ).join('');
										// Pour supprimer tout ce qu'il y a avant et après les {} du résultat // Eviter les headers par exemple de php
										outputData = outputData.split('/^(.*?){/').join('{'); // Avant
										outputData = outputData.split('/(.*)}.*$/').join('$1}'); // Après
										//resultat=resultat.split('[').join('{').split(']').join('}');					
									    outputData = unescape(encodeURIComponent(outputData));// Pour un encodage en UTF8
										 // Retire les noms des chemin du serveur NooLib
										 outputData = outputData.split('/(\/home\/noolibco\/.+)/').join('');
										// Pour les failles de type scripts
										outputData = escapeHtml(outputData);
										/*if(outputData.indexOf('[')== 0 && outputData.lastIndexOf(']')==outputData.length-1){
												outputData = outputData.substr(1);
												outputData = outputData.substr(0,outputData.length-1);
										}*/
									//outputData= JSON.parse(outputData);
									var nombreAlea = Math.floor(Math.random()*1000+1);
									fs.writeFile('/home/noolibco/SafeWorkSpace/Utilisateurs/'+currentUtilisateur.getVariableFixeUtilisateur()+'/temp/File_Result_generated_by_NooLib_'+nombreAlea+'.txt', outputData,function(err){});
					 				var donneeResultat=new DonneeResultat( {'urlDonneeUtilisateur':'/home/noolibco/SafeWorkSpace/Utilisateurs'+currentUtilisateur.getVariableFixeUtilisateur()+'/temp/Picture_Result_generated_by_NooLib_'+nombreAlea+'.jpg', 'typeDonneUtilisateur':typeDonneeUtilisateur});
									tabDonneeUtilisateur.push(donneeResultat);
		 					}else{
					 			if(fields["tache"+i+"data"+j].indexOf('noolibData_')!= -1 ){
					 				var idData = fields["tache"+i+"data"+j].replace("noolibData_","");
					 				var donneeUtilisateur = await(PDODonneeUtilisateur.getDonneeUtilisateurById(idData));
					 				if(donneeUtilisateur != false){
					 						tabDonneeUtilisateur.push(donneeUtilisateur);
					 				}else{
					 					noError = false;
					 					messageClient.addErreur(DENY_HANDLE_DATA);
					 				}
					 			}else{
					 				var idDataInput = fields["tache"+i+"data"+j];
					 				var typeDonneeUtilisateur=await(PDOTypeDonneeUtilisateur.getTypeDonneeUtilisateurByExtension('input.txt'));
					 				var inputDonneeUtilisateur=new InputDonneeUtilisateur( {'valeurInputDonneUtilisateur':idDataInput, 'typeDonneUtilisateur':typeDonneeUtilisateur});
					 				tabDonneeUtilisateur.push(inputDonneeUtilisateur);
					 		 	}
			 				}
					 			j++;
					 		}
		 					i++;j=0;
				 		if(noError){
				 			return runApplication(fields,currentUtilisateur,tabDonneeUtilisateur,messageClient);
				 		}else{
						messageClient.addErreur(ENGINE_NO_DATA_MULE);
						}
			 		}				 		
}

runApplication = function (fields,currentUtilisateur,tabDonneeUtilisateur,messageClient){
			var currentApplication=await(application.getApplicationByIdWithAllParameters(fields['idApplication']));
			var idVersion=fields['idVersion'];
			var tabUrlDestinationDonneeUtilisateur= [];
			var version;
			var versions = await(currentApplication.getVersions());
			tabDonneeUtilisateur.forEach(function(donneeUtilisateur){
				if(donneeUtilisateur instanceof DonneeUtilisateur && !(donneeUtilisateur instanceof InputDonneeUtilisateur)){
				 	tabUrlDestinationDonneeUtilisateur.push(donneeUtilisateur.getUrlDonneeUtilisateur());
				} else {
				 	tabUrlDestinationDonneeUtilisateur.push(false);
				}
			});

	 		if(idVersion != undefined ){ //&& idAuteurs.indexOf(fields['id']) 
				 			
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
				 				
				 				var nomTacheApplication = fields['tache'+i];
				 				for(var j=0; j<taches.length;++j){
				 					var task = await(taches[j]);
				 					if(task.getNomTache() == nomTacheApplication){
				 						tacheDemandee = task;
				 						break;
				 					}
				 				}
				 				
				 				if(tacheDemandee != undefined && tacheDemandee != false){

				 					var nombreDeDonnee = await(tacheDemandee.getTacheTypeDonneeUtilisateurs()).length;
				 				
				 					outputData =await(executeRun(numRequest,fields,currentUtilisateur, currentApplication, version.getNumVersion(), tacheDemandee, tabDonneeUtilisateur.slice(offset,offset+nombreDeDonnee),tabUrlDestinationDonneeUtilisateur.slice( offset, offset+nombreDeDonnee),messageClient));
				 					offset = offset + nombreDeDonnee;
				 					if(outputData != false){
				 						return outputData;
				 						
				 					}else {
				 						messageClient.addErreur("erreur 1"); //A voir ???
				 					}
				 					tacheDemandee = false;
				 				}else{
				 					messageClient.addErreur(NO_TASK);
				 				}
				 				++i;
				 			}
				 		}else{
							messageClient.addErreur(TREE_VERSION_NOT_ACTIVATED);
						}
						return false; //return une donneeUtilisateur qui contien l'url et le nom 
						
					
}
//****************************

//********Request********
router.post('/', function(req, res) { 
	res.header("Access-Control-Allow-Origin",req.get('origin'));
	var messageClient = new(require('../models/MessageClient'));
	async (function(){
		numRequest +=1;
		var outputData = undefined;
		var fields = JSON.parse(req.body['jsonData']);

		var currentUtilisateur=await(user.getUtilisateurById(fields['id']));
		var currentApplication=await(application.getApplicationByIdWithAllParameters(fields['idApplication']));
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
			if(abonnenementUser || fields['isAdmin']){
				if(currentApplication.getIdStatut() > 4 || fields['isAdmin'] || idAuteurs.indexOf(fields['id']) ){
					
					var resulatFromRun= GetDataUrl(fields,fields['id'],messageClient);
					messageClient.addReussite(resulatFromRun);
				 	
				}else{
						messageClient.addErreur(APPLICATION_NOT_ACTIVATED);
					}
			}else{
				messageClient.addErreur(DENY_USE_APPLICATION);
			}	
		}else{
			messageClient.addErreur(NO_APPLICATION);
		}
		 	
		 	var response = {};
			if(messageClient.hasErreur()){
				var texte = '';
					messageClient.getErreurs().forEach(function(erreur){
					texte += '<p>'+erreur+'</p>';
				});
				response['erreurs'] = texte;
			}
			if(messageClient.hasReussite()){
				
				var resultatsApplication = [];
				var i=0;
				messageClient.getReussites().forEach(function(resultat){
					// Pour supprimer les espaces en début/fin de chaîne ainsi que les retours chariots
					resultat = resultat.toString().trim();
					
					resultat = resultat.split('<br>').join('');
					resultat=resultat.split('<br />').join('');
				    resultat= resultat.split("\n").join('');
				    resultat= resultat.split("\r" ).join('');
					// Pour supprimer tout ce qu'il y a avant et après les {} du résultat // Eviter les headers par exemple de php
					resultat = resultat.split('/^(.*?){/').join('{'); // Avant
					resultat = resultat.split('/(.*)}.*$/').join('$1}'); // Après
					//resultat=resultat.split('[').join('{').split(']').join('}');					
				    resultat = unescape(encodeURIComponent(resultat));// Pour un encodage en UTF8
					 // Retire les noms des chemin du serveur NooLib
					 resultat = resultat.split('/(\/home\/noolibco\/.+)/').join('');
					// Pour les failles de type scripts
					resultat = escapeHtml(resultat);
					if(resultat.indexOf('[')== 0 && resultat.lastIndexOf(']')==resultat.length-1){
							resultat = resultat.substr(1);
							resultat = resultat.substr(0,resultat.length-1);
					}
					resultatsApplication.push(resultat);
				});
				
				response['resultat'] = resultatsApplication;
			}
			response = JSON.stringify(response);
		 	res.send(response);	
	})();

});

function escapeHtml(text){
	return text.split('/&/g').join('&amp;').split('/</g').join('&lt;').split('/>/g').join('&gt;');
	//.split('/"/g').join('&quot;').split("/'/g").join('&#39;')
}

module.exports = router;
