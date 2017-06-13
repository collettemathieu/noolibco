var DB=require('../../config/database.js');

function Application(){
}


Application.prototype.getApplication=function(id){
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
}


Application.prototype.getVersions=function(id_app){
   return new Promise((resolve,reject)=>{
 	DB.query("select * from version where id_application=?",[id_app],function(rows,err){
 		if(err)
 			return err;
 	if(rows.length!=0){
 		return resolve(rows);
 	}
 	else{
 		return  reject('pas de version');
 	}
 	});
   });
}

Application.prototype.getTaches=function(id_version){
   return new Promise((resolve,reject)=>{
 	DB.query("select id_tache from version_tache where id_version=?",[id_version],function(rows,err){
 		if(err)
 			return err;
 	if(rows.length!=0){
 		return resolve(rows);
 	}
 	else{
 		return  reject('pas de taches');
 	}
 	});
   });
}

Application.prototype.getNomTache=function(id_tache){
   return new Promise((resolve,reject)=>{
 	DB.query("select nom_tache from tache where id_tache=?",[id_tache],function(rows,err){
 		if(err)
 			return err;
 	if(rows.length!=0){
 		return resolve(rows[0]['nom_tache']);
 	}
 	else{
 		return  reject('pas de Nom de tache');
 	}
 	});
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
	 var User= require('../User').init;
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