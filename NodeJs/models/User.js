var DB=require('../config/database.js');

function User(){
	var idUtilisateur, variableFixeUtilisateur, nomUtilisateur, prenomUtilisateur, mailUtilisateur, passwordUtilisateur, UtilisateurActive;
}
User.prototype.getIdUtilisateur = function(){
	return this.idUtilisateur;
}
User.prototype.getVariableFixeUtilisateur = function(){
	return this.variableFixeUtilisateur;
}
User.prototype.getMailUser=function(id){
	return new Promise((resolve,reject)=>{
		DB.query("select mail_utilisateur from utilisateur where id_utilisateur = ?",[id],function(rows,err){
			if(err) return reject(err);
			if(rows.length !=0){
				return resolve(rows[0]['mail_utilisateur']);
			}
			else{
				return resolve({"erreurs": "<p>A system error has occurred.</p>"});
			}
		});
	});
}
User.prototype.getUtilisateurByMail= function(mail){
	return new Promise((resolve,reject)=>{
		DB.query("SELECT * FROM utilisateur WHERE mail_utilisateur = ?",[mail],function(rows,err){
			//console.log(mail);
			if(err) return err;
			if(rows.length != 0){
				var user=new User();
				user.idUtilisateur = rows [0]['id_utilisateur'];
				user.variableFixeUtilisateur= rows [0]['variable_fixe_utilisateur'];
				user.nomUtilisateur  = rows [0]['nom_utilisateur'];
				user.prenomUtilisateur = rows [0]['prenom_utilisateur'];
				user.mailUtilisateur  = rows [0]['mail_utilisateur'];
				user.passwordUtilisateur = rows [0]['password_utilisateur'];
				user.UtilisateurActive = rows [0]['utilisateur_active'];
				return resolve(user);
			}
			else{
				return resolve(false);
			}

		});
	});	
}
User.prototype.getUtilisateurById=function(id_user){
	return new Promise((resolve,reject)=>{
		DB.query("SELECT * FROM utilisateur WHERE id_utilisateur = ?",[id_user],function(rows,err){
			if(err) return err;
			if(rows.length != 0){
				var user=new User();
				user.idUtilisateur = rows [0]['id_utilisateur'];
				user.variableFixeUtilisateur= rows [0]['variable_fixe_utilisateur'];
				user.nomUtilisateur  = rows [0]['nom_utilisateur'];
				user.prenomUtilisateur = rows [0]['prenom_utilisateur'];
				user.mailUtilisateur  = rows [0]['mail_utilisateur'];
				user.passwordUtilisateur = rows [0]['password_utilisateur'];
				user.UtilisateurActive = rows [0]['utilisateur_active'];
				return resolve(user);
			}
			else{
				return resolve(false);
			}

		});
	});	
}


module.exports= User;

