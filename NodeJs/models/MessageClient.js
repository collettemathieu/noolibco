
function MessageClient(){
     this.erreurs=[];
     this.reussites=[];
}

MessageClient.prototype.addErreur = function(erreur){
		if(erreur != undefined ){
			if(erreur.length !=0 ){
				this.erreurs=this.erreurs.concat(erreur);
			}else{
				this.erreurs.push(erreur);
			}
		}
}

MessageClient.prototype.addReussite = function(reussite){
	if(reussite != undefined){
		this.reussites = [];
		if(reussite.length != 0){
			this.reussites= this.reussites.concat(reussite);
		} else{
			this.reussites.push(reussite);
		}
	}
}
MessageClient.prototype.hasErreur = function(){
		if(this.erreurs.length != 0){
			console.log(this.erreurs.length);			
			return true;
		} else{
			return false;
		}
}
MessageClient.prototype.hasReussite = function(){
	if(this.reussites.length != 0){
			return true;
		} else{
			return false;
		}
}
MessageClient.prototype.getErreurs = function(){
	return this.erreurs;
}
MessageClient.prototype.getReussites = function(){
	return this.reussites;
}
module.exports= MessageClient;