var DB=require('../config/database.js');

function Parametre(){
	var idParametre, nomParametre, descriptionParametre, statutPublicParametre, typeAffichageParametre, valeurDefautParametre, valeurMinParametre, valeurMaxParametre, valeurPasParametre, fonctions = [];
}

Parametre.prototype.getParametreById = function(id){
	  return new Promise((resolve,reject)=>{
	 	DB.query("SELECT * FROM parametre WHERE id_parametre = ?",[id],function(rows,err){
	 		if(err)
	 			return err;
		 	if(rows.length!=0){
		 		var parametre = new Parametre();
		 		parametre.idParametre = rows[0]['id_parametre'];
				parametre.NomParametre = rows[0]['nom_parametre'];
				parametre.DescriptionParametre = rows[0]['description_parametre'];
				parametre.statutPublicParametre = rows[0]['statut_public_parametre'];
				parametre.valeurDefautParametre = rows[0]['valeur_defaut_parametre'];
				//parametre.typeAffichageParametre => $pdoTypeAffichageParametre->getTypeAffichageParametreById($donnee['id_type_affichage_parametre']),
				parametre.valeurMinParametre = rows[0]['valeur_min_parametre'];
				parametre.valeurMaxParametre = rows[0]['valeur_max_parametre'];
				parametre.valeurPasParametre = rows[0]['valeur_pas_parametre'];
		 		return resolve(parametre);
		 	}
		 	else{
		 		return  resolve(false);
		 	}
	 	});
   });
}

module.exports= Parametre;