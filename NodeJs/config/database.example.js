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
	pool.execute(query,params,function(err,rows,fields){
		if(err){
			callback(null,err);
		}
		else
			callback(rows);
	});

	};
	return{
		query: _query
	};
})();
module.exports = DB;