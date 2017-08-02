var DB=require('../config/database.js');
var TacheTypeDonneeUtilisateur= require('./TacheTypeDonneeUtilisateur');
var Fonction = require('./Fonction');
var async=require('asyncawait/async');
var await=require('asyncawait/await');



function Tache(){
	var idTache, nomTache, tacheTypeDonneeUtilisateurs = [], versions = [], fonctions = [];
	//return this;
}
Tache.prototype.getNomTache = function(){
	return this.nomTache;
}
Tache.prototype.getTacheTypeDonneeUtilisateurs = function(){
	return this.tacheTypeDonneeUtilisateurs;
}
Tache.prototype.getFonctions = function (){
	return this.fonctions;
}
Tache.prototype.getTacheById = function(id_tache){
	return new Promise((resolve,reject)=>{
	 	DB.query("SELECT * FROM tache WHERE id_tache = ?",[id_tache],async function(rows,err){
	 		if(err)
	 			return err;
		 	if(rows.length!=0){
		 		var tache = new Tache();
		 		tache.idTache = rows [0]['id_tache'];
				tache.nomTache = rows [0]['nom_tache'];
				tache.descriptionTache = rows [0]['description_tache'];
				tache.fonctions = await(putFonctionsInTache(rows [0]['id_tache']));
				tache.tacheTypeDonneeUtilisateurs =await(putTacheTypeDonneeUtilisateursInTache(rows [0]['id_tache']));
				return resolve(tache);

		 	}
		 	else{
		 		return  resolve(false);
		 	}
	 	});
   });
}

Tache.prototype.getTacheByIdLimited = function(id_tache){
	return new Promise((resolve,reject)=>{
	 	DB.query("SELECT * FROM tache WHERE id_tache = ?",[id_tache],function(rows,err){
	 		if(err)
	 			return err;
		 	if(rows.length!=0){
		 		var tache = new Tache();
		 		tache.idTache = rows [0]['id_tache'];
				tache.nomTache = rows [0]['nom_tache'];
				tache.descriptionTache = rows [0]['description_tache'];
				return resolve(tache);
		 	}
		 	else{
		 		return  resolve(false);
		 	}
	 	});
   });
}
putFonctionsInTache = function(idTache){
	return new Promise((resolve,reject)=>{
	 	DB.query("SELECT id_fonction FROM tache_fonction WHERE id_tache = ? ORDER BY id_ordre",[idTache],function(rows,err){
	 		if(err)
	 			return err;
		 	if(rows.length!=0){
		 		var fonction = new Fonction();
		 		var array = [];
		 		rows.forEach(function(row){	 			
		 			array.push((fonction.getFonctionById(row['id_fonction'])));
		 		});
		 		
		 		return resolve(array);
		 	}
		 	else{
		 		return  resolve(false);
		 	}
	 	});
   });
}

putTacheTypeDonneeUtilisateursInTache = function(idTache){
	return new Promise((resolve,reject)=>{
	 	DB.query("SELECT id_type_donnee_utilisateur, id_ordre FROM tache_type_donnee_utilisateur WHERE id_tache = ? ORDER BY id_ordre",[idTache],function(rows,err){
	 		if(err)
	 			return err;
		 	if(rows.length!=0){
		 		var tacheTypeDonneeUtilisateur = new TacheTypeDonneeUtilisateur();
		 		var array = [];
		 		rows.forEach(async function(row){
		 			array.push(await(tacheTypeDonneeUtilisateur.getTacheTypeDonneeUtilisateurById(idTache, row['id_type_donnee_utilisateur'], row['id_ordre'])));
		 		});
		 		return resolve(array);
		 	}
		 	else{
		 		return  resolve(false);
		 	}
	 	});
   });
}



module.exports= Tache;