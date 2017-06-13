function InputDonneeUtilisateur(valInputDonnee, typeDonneeUtilisateur) {

	this.valeurInputDonneUtilisateur= valInputDonnee;
	this.typeDonneeUtilisateur =  typeDonneeUtilisateur;

}
InputDonneeUtilisateur.prototype.getValeurInputDonneeUtilisateur = function(){
	return this.valeurInputDonneUtilisateur;
}
module.exports= InputDonneeUtilisateur;
