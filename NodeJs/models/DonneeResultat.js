var DonneeUtilisateur = require('./DonneeUtilisateur');


function DonneeResultat(donnee) {

	this.urlDonneeUtilisateur= donnee.urlDonneeUtilisateur;
	this.typeDonneeUtilisateur= donnee.typeDonneUtilisateur;
	

}
DonneeResultat.prototype = new DonneeUtilisateur;

DonneeResultat.prototype.getUrlDonneeUtilisateur = function(){
	return this.urlDonneeUtilisateur;
}

module.exports= DonneeResultat;