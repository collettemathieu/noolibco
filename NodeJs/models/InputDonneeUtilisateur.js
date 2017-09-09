var DonneeUtilisateur = require('./DonneeUtilisateur');


function InputDonneeUtilisateur(donnee) {

	this.valeurInputDonneUtilisateur= donnee.valeurInputDonneUtilisateur;
	this.typeDonneeUtilisateur= donnee.typeDonneUtilisateur;
	

}
InputDonneeUtilisateur.prototype = new DonneeUtilisateur;

InputDonneeUtilisateur.prototype.getValeurInputDonneeUtilisateur = function(){
	return this.valeurInputDonneUtilisateur;
}

module.exports= InputDonneeUtilisateur;
