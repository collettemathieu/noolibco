<?php
	if(isset($idDonneeUtilisateur)){
		
		if(isset($image)){
			if($typeDonnee != 'dcm'){
				$image = '<img src="data:image/jpeg;charset=utf8;base64,'.base64_encode($image).'" alt="Image data"/>';
			
				$reponse = array(
					'imageStandard' => $image
				);
			}else{
				$image = base64_encode($image);
				$reponse = array(
					'imageDicom' => $image
				);
			}
		}
		$reponse['idDonneeUtilisateur'] = $idDonneeUtilisateur;

	}elseif($user->getMessageClient()->hasErreur()){
		$texte = '';
		foreach($user->getMessageClient()->getErreurs() as $erreur){
			$texte .= '<p>'.$erreur.'</p>';
		}
		$reponse = array(
			'erreurs' => $texte
		);
	}else{
		$reponse = array(
			'erreurs' => '<p>A system error has occurred !</p>'
		);
	}

	// On envoie la réponse au format JSON
	echo json_encode($reponse);

	?>