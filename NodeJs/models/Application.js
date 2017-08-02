var async=require('asyncawait/async');
var await=require('asyncawait/await');
var DB=require('../config/database.js');
var User= require('./User');
var Version= require('./Version');
var Auteur= require('./Auteur');

function Application(){
	var idApplication,variableFixeApplication, createur, auteurs = [], idStatut, nomApplication, utilisateurs = [], versions = [];
}
Application.prototype.getVariableFixeApplication = function(){
	return this.variableFixeApplication;
}
putVersionsInApplication= function (application){
	return new Promise( function(resolve,reject){
		DB.query('SELECT id_version FROM version WHERE id_application = ?',[application.idApplication],async function(rows,err){
			if(err) return reject(err);
			if(rows.length !=0){
				var array=[];
				rows.forEach(async function(row){
					var version = new Version();
					array.push(await(version.getVersionById(row['id_version'])));
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


Application.prototype.getApplicationByIdWithAllParameters = function(id_application){
	return new Promise(function(resolve,reject){
		DB.query("SELECT * FROM application WHERE id_application = ?",[id_application],async function(rows,err){
			if(err)
				return err;
			if(rows.length != 0){
				var user = new User();
				var application = new Application();
				application.idApplication = rows [0]['id_application'];
				application.variableFixeApplication= rows[0]['variable_fixe_application'];
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

module.exports= Application;