var DB=require('../config/database.js');
var User= require('./User');
var PDODonneeUtilisateur = require('./DonneeUtilisateur');

function UtilisateurDonneeUtilisateur(){
	var donneeUtilisateur, utilisateur;
}

UtilisateurDonneeUtilisateur.prototype.getUtilisateurDonneeUtilisateurById = function(idUtilisateur, idDonneeUtilisateur){
		return new Promise(async function(resolve,reject){
		DB.query('SELECT * FROM utilisateur_donnee_utilisateur WHERE id_donnee_utilisateur = ? and id_utilisateur = ?',[idDonneeUtilisateur, idUtilisateur], function(rows,err){
			if(err) return reject(err);
			if(rows.length !=0){
				var utilisateurDonneeUtilisateur= new UtilisateurDonneeUtilisateur();
				var user = new User();
				var pdoDonneeUtilisateur = new PDODonneeUtilisateur();
				utilisateurDonneeUtilisateur.utilisateur = user.getUtilisateurById(rows [0]['id_utilisateur']);
				utilisateurDonneeUtilisateur.donneeUtilisateur = pdoDonneeUtilisateur.getDonneeUtilisateurById(rows [0]['id_donnee_utilisateur'])
				return resolve(utilisateurDonneeUtilisateur);
			}
			else{
				resolve(false);
			}
		}); 
	});
}
module.exports= UtilisateurDonneeUtilisateur;