var DB=require('../../config/database.js');

function User(){
}
User.prototype.getUser=function(id){
	return new Promise((resolve,reject)=>{
		DB.query("select * from utilisateur where id_utilisateur = ?",id,function(rows,err){
			if(err) return reject(err);
			if(rows.length !=0){
				var obj={};
				if(rows[0]['password_admin_utilisateur']!=undefined){
					obj.isAdmin=true;
				}
				else{
					obj.isAdmin=false;
				}
				return resolve(obj);
			}
			else{
				return resolve("pas d'utilisateur");
			}
		});
	});
}


module.exports= User;

/*var query=DB.query("select * from utilisateur where id_utilisateur = ?",id,function(rows,err){
		if(!err){
			if(rows.length !=0){
				var obj={};
				if(rows[0]['password_admin_utilisateur']!=undefined){
					obj.isAdmin=true;
				}
				else{
					obj.isAdmin=false;
				}
				callback(obj,null);
			}
			else{
				callback("pas d'utilisateur");
			}
		}
		else{
			callback(null,err);
		}
	});*/
