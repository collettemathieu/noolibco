var async=require('asyncawait/async');
var await=require('asyncawait/await');
var DB=require('../config/database.js');
var User= require('./User');
var Version= require('./Version');
var Auteur= require('./Auteur');

function Application(){
	var idApplication, createur, auteurs = [], idStatut, nomApplication, utilisateurs = [], versions = [];
}
putVersionsInApplication= function (application){
	return new Promise(async function(resolve,reject){
		DB.query('SELECT id_version FROM version WHERE id_application = ?',[application.idApplication], function(rows,err){
			if(err) return reject(err);
			if(rows.length !=0){
				var array=[];
				rows.forEach( function(row){
					var version = new Version();
					array.push(version.getVersionById(row['id_version']));
				});
				return resolve(array);
			}
			else{
				resolve(false);
			}
		}); 
	});
}
putAuteursInApplication=function(application){
	return new Promise(function(resolve,reject){
		DB.query('SELECT id_auteur FROM application_auteur WHERE id_application = ?',[application.idApplication],function(rows,err){
			if(err) return reject(err);
			if(rows.length !=0){
				var array=[];
				rows.forEach(function(row){
					var auteur = new Auteur();
					array.push (auteur.getAuteurById(row['id_auteur']));
				});
				return resolve(array);
			}
			else{
				return resolve(false);
			}
		}); 
	});
}
Application.prototype.getAuteurs = function(){
 return this.auteurs;
}
Application.prototype.getIdStatut = function(){
 return this.idStatut;
}
Application.prototype.getVersions = function(){
 return this.versions;
}
Application.prototype.getCreateur = function(){
	return this.createur;
}
Application.prototype.getApplicationById = function(id_application){
	return new Promise((resolve,reject)=>{
		DB.query("select * from application where id_application = ?",[id_application],function(rows,err){
			if(err) return reject(err);
			if(rows.length !=0){
				var User= require('./User');
				var user = new User();
				var application= new Application();
				application.idApplication = rows [0]['id_application'];
				application.nomApplication = rows [0]['nom_application'];
				application.createur = user.getUtilisateurById(rows[0]['id_utilisateur']);
				return resolve(application);
			}
			else{
				return resolve(false);
			}
		}); 
	});
}

/*Application.prototype.getApplication=function(id){
	return new Promise((resolve,reject)=>{
		var obj={}; 
		DB.query("select * from application where id_application = ?",[id],function(rows,err){
			if(err) return reject(err);
			if(rows.length !=0){
				obj.id_createur=rows[0]['id_utilisateur'];
				obj.id_statut=rows[0]['id_statut'];
				return resolve(obj);
			}
			else{
				return resolve("Cette application n'existe pas");
			}
		}); 
	});
}*/
Application.prototype.getApplicationByIdWithAllParameters = function(id_application){
	return new Promise(function(resolve,reject){
		DB.query("SELECT * FROM application WHERE id_application = ?",[id_application],async function(rows,err){
			if(err)
				return err;
			if(rows.length != 0){
				var user = new User();
				var application = new Application();
				application.idApplication = rows [0]['id_application'];
				application.nomApplication = rows [0]['nom_application'];
				application.idStatut = rows[0]['id_statut'];
				application.versions=await(putVersionsInApplication(application));
				application.auteurs= await(putAuteursInApplication(application));
				application.createur = await(user.getUtilisateurById(rows [0]['id_utilisateur']));
				return resolve(application);
			}else{
				return resolve("Cette application n'exite pas");
			}
		})
	});
}
function getAuteur(id_app){
   return new Promise((resolve,reject)=>{
	 	var listeAuteurs=[];
 		DB.query("select * from application_auteur where id_application = ?",[id_app],function(rows,err){
 			if(err)
 				return err;
 			if(rows.length!=0){
 				rows.forEach(function(item){
 					listeAuteurs.push(item['id_auteur']);
 				});
 				return resolve(listeAuteurs);
  			}
 			else{
 				return resolve(false);
 			}
 			
 		});
	 });
}
//Pour chercher les auteurs
Application.prototype.isAuteur=function(id_user,id_app){
	 var User= require('./User');
	 var user= new User();
	 return new Promise((resolve,reject)=>{
	 	var listeAuteurs=[];
	 	var userMail= user.getMailUser(id_user);
	 	getAuteur(id_app).then(function(resultat){
	 		if(resultat == false){ return resolve(false);}
	 		resultat.forEach(function(item){

	 			DB.query("select * from auteur where id_auteur = ?",[item],function(rows,err){
	 				if(rows.length != 0){
	 					if(rows[0]['mail_auteur']==userMail){
	 						return resolve(true);
	 					}
	 				}
	 				return resolve(false);
	 			});
	 		});
	 	});
});
}
module.exports= Application;