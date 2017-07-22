
function MessageClient(){
	var erreurs=[] , reussites=[];
}

MessageClient.prototype.addErreur = function(erreur){
		if(erreur != undefined ){
			if(erreur.length !=0 ){
				this.erreurs.concat(erreur);
			}else{
				this.erreurs.push(erreur);
			}
		}
}

MessageClient.prototype.addReussite = function(reussite){
	if(reussite != undefined){
		if(reussite.length != 0){
			console.log(this.reussites);
			//this.reussites.concat(reussite);
		} else{
			this.reussites.push(reussite);
		}
	}
}
module.exports= MessageClient;