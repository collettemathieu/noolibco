var DB=require('../config/database.js');

function TypeDonneeUtilisateur(){
	var idTypeDonneeUtilisateur, nomTypeDonneeUtilisateur, extensionTypeDonneeUtilisateur;
}
TypeDonneeUtilisateur.prototype.getExtensionTypeDonneeUtilisateur = function(){
	return this.extensionTypeDonneeUtilisateur;
}
TypeDonneeUtilisateur.prototype.getTypeDonneeUtilisateurByExtension = function(extension){
	return new Promise((resolve,reject)=>{
		DB.query("SELECT * FROM type_donnee_utilisateur WHERE extension_type_donnee_utilisateur = ?",[extension],function(rows,err){
			if(err) return reject(err);
			if(rows.length!=0){
				var typeDonneeUtilisateur = new TypeDonneeUtilisateur();
					typeDonneeUtilisateur.idTypeDonneeUtilisateur = rows[0]['id_type_donnee_utilisateur'];
					typeDonneeUtilisateur.nomTypeDonneeUtilisateur = rows[0]['nom_type_donnee_utilisateur'];
					typeDonneeUtilisateur.extensionTypeDonneeUtilisateur = rows[0]['extension_type_donnee_utilisateur'];
					
				
				return resolve(typeDonneeUtilisateur);
			}
			else{
				return resolve(false);
			}
		});
	});
}

TypeDonneeUtilisateur.prototype.getTypeDonneeUtilisateurById = function(id){
	return new Promise((resolve,reject)=>{
		DB.query("SELECT * FROM type_donnee_utilisateur WHERE id_type_donnee_utilisateur = ?",[id],function(rows,err){
			if(err) return reject("erreurTypeDonneeUtilisateur");
			if(rows.length !=0){
				typeDonneeUtilisateur = new TypeDonneeUtilisateur();
					typeDonneeUtilisateur.idTypeDonneeUtilisateur  = rows[0]['id_type_donnee_utilisateur'];
					typeDonneeUtilisateur.nomTypeDonneeUtilisateur = rows[0]['nom_type_donnee_utilisateur'];
					typeDonneeUtilisateur.extensionTypeDonneeUtilisateur = rows[0]['extension_type_donnee_utilisateur'];
				
				return resolve(typeDonneeUtilisateur);
				   
			}
			else{
				return resolve(false);
			}
		
		});
	});
}

TypeDonneeUtilisateur.prototype.getTacheTypeDonneeUtilisateur= function(tache){
	return new Promise((resolve,reject)=>{
		DB.query("SELECT id_type_donnee_utilisateur, id_ordre FROM tache_type_donnee_utilisateur WHERE id_tache =? ORDER BY id_ordre",[tache],function(rows,err){
			if(err) return err;
			if(rows.length != 0){
				return resolve(rows);
			} 
			else{
				return reject("erreur");
			}
		});
	});
}

module.exports= TypeDonneeUtilisateur;