
function MessageClient(){
     this.erreurs=[];
     this.reussites=[];
}

MessageClient.prototype.addErreur = function(erreur){
	this.erreurs.push(erreur);
}

MessageClient.prototype.addReussite = function(reussite){
		this.reussites.push(reussite);	
}
MessageClient.prototype.hasErreur = function(){
		if(this.erreurs.length != 0){		
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