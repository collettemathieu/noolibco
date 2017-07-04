var DB=require('../config/database.js');
var Parametre= require('./Parametre');
var PDOTache = require('./PDOTache');
var async=require('asyncawait/async');
var await=require('asyncawait/await');

function Fonction(){
	var idFonction, nomFonction, urlFonction, extensionFonction, parametres = [], taches = [];
	return this;

}

Fonction.prototype.getParametres = function(){
	return this.parametres;
}
Fonction.prototype.getUrlFonction = function(){
	return this.urlFonction;
}
Fonction.prototype.getFonctionById = function(id){
	return new Promise((resolve,reject)=>{
	 	DB.query("SELECT * FROM fonction WHERE id_fonction = ?",[id],async function(rows,err){
	 		if(err)
	 			return err;
		 	if(rows.length!=0){
		 		var fonction = new Fonction();
		 		fonction.idFonction = rows[0]['id_fonction'];
				fonction.nomFonction = rows[0]['nom_fonction'];
				fonction.urlFonction = rows[0]['url_fonction'];
				fonction.extensionFonction = rows[0]['extension_fonction'];
				fonction.parametres=await(putParametresInFonction(fonction));
				//fonction.taches = putTachesInFonction(fonction);
		 		return resolve(fonction);
		 	}
		 	else{
		 		return  resolve(false);
		 	}
	 	});
   });
}

putParametresInFonction = function(fonction){
	 return new Promise((resolve,reject)=>{
	 	DB.query("SELECT id_parametre FROM fonction_parametre WHERE id_fonction = ?  ORDER BY id_ordre",[fonction.idFonction],function(rows,err){
	 		if(err)
	 			return err;
		 	if(rows.length!=0){
		 		var parametre = new Parametre();
		 		var array = [];
		 		rows.forEach(function(row){
		 			array.push(parametre.getParametreById(row['id_parametre']));
		 		});
		 		return resolve(array);
		 	}
		 	else{
		 		return  resolve(false);
		 	}
	 	});
   });
}

putTachesInFonction = function(fonction){
	return new Promise((resolve,reject)=>{
	 	DB.query("SELECT id_tache FROM tache_fonction WHERE id_fonction = ? ORDER BY id_ordre ",[fonction.idFonction],function(rows,err){
	 		if(err)
	 			return err;
		 	if(rows.length!=0){
		 		var tache = new PDOTache();
		 		var array = [];
		 		rows.forEach(function(row){
		 			array.push(tache.getTacheByIdLimited(row['id_tache']));
		 		});
		 		return resolve(array);
		 	}
		 	else{
		 		return  resolve(false);
		 	}
	 	});
   });
}

module.exports= Fonction;