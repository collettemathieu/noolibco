var DB=require('../config/database.js');
var Tache = require('./Tache');
var async=require('asyncawait/async');
var await=require('asyncawait/await');
var pdoTache= new Tache();

function Version(){
	var idVersion, activeVersion, numVersion, datePublicationVersion, noteMajVersion, application, taches = [];
	
}
Version.prototype.getIdVersion = function (){
	return this.idVersion;
}
Version.prototype.getActiveVersion = function (){
	return this.activeVersion;
}
Version.prototype.getTaches = function(){
	return this.taches;
}
Version.prototype.getNumVersion = function(){
	return this.numVersion;
}
Version.prototype.getVersionById = function(id_version){
	return new Promise((resolve,reject)=>{
	 	DB.query("SELECT * FROM version WHERE id_version = ?",[id_version],async function(rows,err){
	 		if(err)
	 			return err;
		 	if(rows.length!=0){
		 		var version= new Version();
		 		var Application= require('./Application');
		 		var application= new Application();
		 		version.idVersion = rows [0]['id_version'];
				version.activeVersion = rows [0]['active_version'];
				version.numVersion = rows [0]['num_version'];
				version.datePublicationVersion = rows [0]['date_publication_version'];
				version.noteMajVersion = rows [0]['note_maj_version'];
				version.taches =putTachesInVersion(version);
				version.Application = await(application.getApplicationById(rows [0]['id_application']));

				return resolve(version);
		 	}
		 	else{
		 		return  resolve(false);
		 	}
	 	});
   });
}
putTachesInVersion = function(version){
   return new Promise(function (resolve,reject){
	 	DB.query("SELECT id_tache FROM version_tache WHERE id_version =?",[version.idVersion],function(rows,err){
	 		if(err)
	 			return err;
		 	if(rows.length!=0){
		 		
		 		var array = [];
		 		rows.forEach(function(row){
			 		array.push(pdoTache.getTacheById(row['id_tache']));
			 	});
		 		
		 			return resolve(array);
		 		
		 		
		 	}
		 	else{
		 		return  resolve(false);
		 	}
	 	});
   });
}

constructVersion = function(donnee){

}
module.exports= Version;