exports.getFormData= async function(req){
		var promise= await new Promise(function(resolve,reject){
		var form = new multiparty.Form();
		form.parse(req,function(err,fields){
	 		if(err) {
	 			console.log(err);
	 			return err;
	 		}

	 		resolve(fields);
	 	});
	 	});
	 	return promise;
	}