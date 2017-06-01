var DB=require('../../config/database.js');

function User(){
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
			if(err) return err;
			if(rows.length != 0){
				return resolve(rows[0]);
			}
			else{
				return resolve({"erreurs": "<p>A system error has occurred.</p>"});
			}

		});
	});	
}


module.exports= User;

