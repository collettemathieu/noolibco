var DB=require('../../config/database.js');

function User(){
}
User.prototype.getUser=function(id, callback){
	var query=DB.query("select * from utilisateur where id_utilisateur = ?",id,function(rows,err){
		if(!err){
			if(rows.length !=0){
				callback(rows);
			}
			else{
				callback("pas d'utilisateur");
			}
		}
		else{
			callback(null,err);
		}
	});
}

module.exports= User;


