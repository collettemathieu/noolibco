var DB=require('../../config/database.js');

function Application(){
}
Application.prototype.getApplication=function(id, callback){
	var query=DB.query("select * from application where id_application = ?",id,function(rows,err){
		if(!err){
			if(rows.length !=0){
				callback(rows);
			}
			else{
				callback("Cette application n'existe pas");
			}
		}
		else{
			callback(null,err);
		}
	});
}

module.exports= Application;
