var Application = require('./Application');

function PDOApplication(){

}
putVersionsInApplication= function (application){
	return new Promise(async function(resolve,reject){
		DB.query('SELECT id_version FROM version WHERE id_application = ?',[application.idApplication], function(rows,err){
			if(err) return reject(err);
			if(rows.length !=0){
				var array=[];
				rows.forEach( function(row){
					var version = new Version();
					array.push(version.getVersionById(row['id_version']));
				});
				return resolve(array);
			}
			else{
				resolve(false);
			}
		}); 
	});
}
putAuteursInApplication=function(application){
	return new Promise(function(resolve,reject){
		DB.query('SELECT id_auteur FROM application_auteur WHERE id_application = ?',[application.idApplication],function(rows,err){
			if(err) return reject(err);
			if(rows.length !=0){
				var array=[];
				rows.forEach(function(row){
					var auteur = new Auteur();
					array.push (auteur.getAuteurById(row['id_auteur']));
				});
				return resolve(array);
			}
			else{
				return resolve(false);
			}
		}); 
	});
}
PDOApplication.prototype.getApplicationByIdWithAllParameters = function(id_application){
	return new Promise(function(resolve,reject){
		DB.query("SELECT * FROM application WHERE id_application = ?",[id_application],async function(rows,err){
			if(err)
				return err;
			if(rows.length != 0){
				var user = new User();
				var application = new Application();
				application.idApplication = rows [0]['id_application'];
				application.variableFixeApplication= rows[0]['variable_fixe_application'];
				application.nomApplication = rows [0]['nom_application'];
				application.idStatut = rows[0]['id_statut'];
				application.versions=await(putVersionsInApplication(application));
				application.auteurs= await(putAuteursInApplication(application));
				application.createur = await(user.getUtilisateurById(rows [0]['id_utilisateur']));
				return resolve(application);
			}else{
				return resolve("Cette application n'exite pas");
			}
		})
	});
}
module.exports = PDOApplication;