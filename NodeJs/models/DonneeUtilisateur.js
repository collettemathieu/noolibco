var DB=require('../config/database.js');
var TypeDonneeUtilisateur = require('./TypeDonneeUtilisateur');

function DonneeUtilisateur(){
	 var idDonneeUtilisateur, urlDonneeUtilisateur, nomDonneeUtilisateur, sampleRateDonneeUtilisateur, tailleDonneeUtilisateur, tempsMinimumDonneeUtilisateur, datePublicationDonneeUtilisateur, typeDonneeUtilisateur, isInCache, utilisateurs = [];
}

DonneeUtilisateur.prototype.getIdDonneeUtilisateur = function(){
	return this.idDonneeUtilisateur;
}
DonneeUtilisateur.prototype.getUrlDonneeUtilisateur = function(){
	return this.urlDonneeUtilisateur;
}
DonneeUtilisateur.prototype.getTypeDonneeUtilisateur = function(){
	return this.typeDonneeUtilisateur;
}
DonneeUtilisateur.prototype.getDonneeUtilisateurById = function (id){
		return new Promise((resolve,reject)=>{
		DB.query("SELECT * FROM donnee_utilisateur WHERE id_donnee_utilisateur = ?",[id],function(rows,err){
			if(err) return reject("erreurgetDonneeUtilisateurById ");
			if(rows.length !=0){
				var donneeUtilisateur= new DonneeUtilisateur();
				var typeDonneeUtilisateur = new TypeDonneeUtilisateur(); 
				typeDonneeUtilisateur.getTypeDonneeUtilisateurById(rows[0]['id_type_donnee_utilisateur']).then(function(resultat){
				
					donneeUtilisateur.idDonneeUtilisateur = rows[0]['id_donnee_utilisateur'];
					donneeUtilisateur.urlDonneeUtilisateur = rows[0]['url_donnee_utilisateur'];
					donneeUtilisateur.urlMiniatureDonneeUtilisateur =  rows[0]['url_miniature_donnee_utilisateur'];
					donneeUtilisateur.nomDonneeUtilisateur = rows[0]['nom_donnee_utilisateur'];
					donneeUtilisateur.sampleRateDonneeUtilisateur = rows[0]['sampleRate_donnee_utilisateur'];
					donneeUtilisateur.tailleDonneeUtilisateur = rows[0]['taille_donnee_utilisateur'];
					donneeUtilisateur.tempsMinimumDonneeUtilisateur = rows[0]['temps_minimum_donnee_utilisateur'];
					donneeUtilisateur.datePublicationDonneeUtilisateur = rows[0]['date_publication_donnee_utilisateur'];
					donneeUtilisateur.typeDonneeUtilisateur = resultat;
					donneeUtilisateur.isInCache = rows[0]['is_in_cache'];
				
				
				return resolve(donneeUtilisateur);
				});
			}
			else{
				return resolve(false);
			}
		});
	});
}


DonneeUtilisateur.prototype.getUtilisateurDonneeUtilisateurById= function(index,idUtilisateur, idDonneeUtilisateur){
		return new Promise((resolve,reject)=>{
		DB.query("SELECT * FROM utilisateur_donnee_utilisateur WHERE id_donnee_utilisateur = ? and id_utilisateur = ?",[idDonneeUtilisateur,idUtilisateur],function(rows,err){
			if(err) return reject("err getUtilisateurDonneeUtilisateurById");

			if(rows.length !=0){
				getDonneeUtilisateurById(rows[0]['id_donnee_utilisateur']).then(function(resultat){
					resultat.ordre=index;
				 var data={
					'utilisateur': rows[0]['id_utilisateur'],
					'donneeUtilisateur': resultat
				};
				return resolve(data);
			});
				   
			}
			else{
				return resolve(false);
			}
		
	});
  });
}


module.exports= DonneeUtilisateur;