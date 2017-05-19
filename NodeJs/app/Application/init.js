var DB=require('../../config/database.js');

function Application(){
}
Application.prototype.getApplication=function(id){
	return new Promise((resolve,reject)=>{
		var obj={}; 
		DB.query("select * from application where id_application = ?",id,function(rows,err){
			if(err) return reject(err);
			if(rows.length !=0){
				obj.id_createur=rows[0]['id_utilisateur'];
				obj.id_statut=rows[0]['id_statut'];
				return resolve(obj);
			}
			else{
				return resolve("Cette application n'existe pas");
			}
		}); 
	});
}



//Pour chercher les auteurs
Application.prototype.isAuteur=function(id_user,id_app){
	 return new Promise((resolve,reject)=>{
	 	var listeAuteurs=[];
 		DB.query("select * from application_auteur where id_application = ?",id_app,function(rows,err){
 			if(err)
 				throw err;
 			if(rows.length!=0){
 				rows.forEach(function(item){
 					listeAuteurs.push(item['id_auteur']);
 				});
 				
 				for(var i=0;i<listeAuteurs.length;++i){
 					if(listeAuteurs[i]==id_user){
 						return resolve("true");
 					}
 				}
 				return resolve("false");
 			}
 			else{
 				return resolve("false");
 			}
 			
 		});
	 });
}


module.exports= Application;



/* var query=DB.query("select * from application where id_application = ?",id,function(rows,err){
		if(!err){
			if(rows.length !=0){
				var obj={};
				obj.id_createur=rows[0]['id_utilisateur'];
				obj.id_statut=rows[0]['id_statut'];
				callback(obj,null);
			}
			else{
				callback("Cette application n'existe pas");
			}
		}
		else{
			callback(null,err);
		}
	});*/