var DB=require('../config/database.js');
var Tache1 = require('./Tache');
var TypeDonneeUtilisateur = require('./TypeDonneeUtilisateur');
var async=require('asyncawait/async');
var await=require('asyncawait/await');

function TacheTypeDonneeUtilisateur(){
	var tache, typeDonneeUtilisateur, ordre, description, uniteDonneeUtilisateur;
}
TacheTypeDonneeUtilisateur.prototype.getTypeDonneeUtilisateur = function(){
	return this.typeDonneeUtilisateur;
}
TacheTypeDonneeUtilisateur.prototype.getTacheTypeDonneeUtilisateurById = function(idTache, idTypeDonneeUtilisateur, idOrdre){
	 return new Promise((resolve,reject)=>{
	 	DB.query("SELECT * FROM tache_type_donnee_utilisateur  WHERE id_tache = ? and id_type_donnee_utilisateur = ? and id_ordre = ?",[idTache, idTypeDonneeUtilisateur, idOrdre], async function(rows,err){
	 		if(err)
	 			return err;
		 	if(rows.length!=0){
		 		var tacheTypeDonneeUtilisateur = new TacheTypeDonneeUtilisateur();
		 		var pdoTache = new (require('./Tache'))();
		 		var pdoTypeDonneeUtilisateur = new TypeDonneeUtilisateur();
		 		

		 		tacheTypeDonneeUtilisateur.typeDonneeUtilisateur = await(pdoTypeDonneeUtilisateur.getTypeDonneeUtilisateurById(rows[0]['id_type_donnee_utilisateur']));
				tacheTypeDonneeUtilisateur.tache = pdoTache.getTacheByIdLimited(rows[0]['id_tache']);
				tacheTypeDonneeUtilisateur.ordre = rows[0]['id_ordre'];
				tacheTypeDonneeUtilisateur.description = rows[0]['description_tache_type_donnee_utilisateur'];
				//tacheTypeDonneeUtilisateur.uniteDonneeUtilisateur => $pdoUniteDonneeUtilisateur->getUniteDonneeUtilisateurById($donnee['id_unite_donnee_utilisateur'])
		 		return resolve(tacheTypeDonneeUtilisateur);
		 	}
		 	else{
		 		return  resolve(false);
		 	}
	 	});
   });
}
module.exports= TacheTypeDonneeUtilisateur;