var mysql=require('mysql');

var pool= mysql.createPool({
		connevtionLimit : 10,
		host:"xxx",
		user:"xxx",
		password:"xxx",
		database:"xxx"
	});

var DB=(function(){
	function _query(query,params,callback){
		pool.getConnection(function(err,connection){
			if(err){
				connection.release();
				callback(null,err);
				throw err;
			}
			else{
				connection.query(query,params,function(err,rows){
					connection.release();
					if(!err){
						callback(rows);
					}else{
						callback(null,err);
					}
				});
				connection.on('error',function(err){
					connection.release();
					callback(null,err);
					throw err;
				});
			}
		});
	};
	return{
		query: _query
	};
})();
module.exports = DB;