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
				console.log("pas connecté db");
				callback(null,err);
				throw err;
			}
			else{
				connection.beginTransaction(function(err){
					if(err){
						throw err;
					}
					connection.prepare(query,function(err,statement){
					//connection.release();
					if(!err){
						statement.execute([params],function(err,rows){
							if(!err){
								callback(rows);
							}else{
								callback(null,err);
								console.log('error db');
							}
							statement.close();
						});
					}
				    });
				    connection.commit(function(err){
				    	if(err){
				    			throw err;
				    	}
				    });
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

