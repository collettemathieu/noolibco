var DB=require('../../config/database.js');

function DonneeUtilisateur(){
}
function getTypeDonneeUtilisateurById(id){
	return new Promise((resolve,reject)=>{
		DB.query("SELECT * FROM type_donnee_utilisateur WHERE id_type_donnee_utilisateur = ?",[id],function(rows,err){
			if(err) return reject("erreurTypeDonneeUtilisateur");
			if(rows.length !=0){
				var data={
					'idTypeDonneeUtilisateur': rows[0]['id_type_donnee_utilisateur'],
					'nomTypeDonneeUtilisateur': rows[0]['nom_type_donnee_utilisateur'],
					'extensionTypeDonneeUtilisateur': rows[0]['extension_type_donnee_utilisateur']
				}
				return resolve(data);
				   
			}
			else{
				return resolve(false);
			}
		
	});
  });
}
function getDonneeUtilisateurById (id){
		return new Promise((resolve,reject)=>{
		DB.query("SELECT * FROM donnee_utilisateur WHERE id_donnee_utilisateur = ?",[id],function(rows,err){
			if(err) return reject("erreurgetDonneeUtilisateurById ");
			if(rows.length !=0){
				getTypeDonneeUtilisateurById(rows[0]['id_type_donnee_utilisateur']).then(function(resultat){
				var data={
					'idDonneeUtilisateur': rows[0]['id_donnee_utilisateur'],
					'urlDonneeUtilisateur': rows[0]['url_donnee_utilisateur'],
					'urlMiniatureDonneeUtilisateur':  rows[0]['url_miniature_donnee_utilisateur'],
					'nomDonneeUtilisateur':  rows[0]['nom_donnee_utilisateur'],
					'sampleRateDonneeUtilisateur': rows[0]['sampleRate_donnee_utilisateur'],
					'tailleDonneeUtilisateur' : rows[0]['taille_donnee_utilisateur'],
					'tempsMinimumDonneeUtilisateur': rows[0]['temps_minimum_donnee_utilisateur'],
					'datePublicationDonneeUtilisateur': rows[0]['date_publication_donnee_utilisateur'],
					'typeDonneeUtilisateur': resultat,
					'isInCache': rows[0]['is_in_cache']
				};
				
				return resolve(data);
				});
			}
			else{
				return resolve("erreur pas de donnée trouvées");
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
				return resolve("pas de donnees");
			}
		
	});
  });
}


module.exports= DonneeUtilisateur;