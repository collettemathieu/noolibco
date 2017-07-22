var DB=require('../config/database.js');
function Auteur(){
	var idAuteur, nomAuteur, prenomAuteur, mailAuteur;
}
Auteur.prototype.getMailAuteur = function(){
	 return this.mailAuteur;
}
Auteur.prototype.getAuteurById = function(id_auteur){
	return new Promise(function(resolve,reject){
			DB.query('SELECT * FROM auteur WHERE id_auteur = ?',[id_auteur], function(rows,err){
				if(err) return reject(err);
				if(rows.length !=0){
					var auteur= new Auteur();
					auteur.IdAuteur = rows [0]['id_auteur'];
					auteur.NomAuteur = rows [0]['nom_auteur'];
					auteur.PrenomAuteur = rows [0]['prenom_auteur'];
					auteur.mailAuteur = rows [0]['mail_auteur'];
					return resolve(auteur);
				}
				else{
					return resolve(false);
				}
			}); 
		});
}

module.exports= Auteur;