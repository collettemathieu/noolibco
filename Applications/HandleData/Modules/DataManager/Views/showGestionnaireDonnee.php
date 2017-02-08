<?php if(isset($utilisateur)){
	$listeDonneeUtilisateur = '';
	foreach($utilisateur->getDonneesUtilisateur() as $data) {
		if($data->getTypeDonneeUtilisateur()->getExtensionTypeDonneeUtilisateur() === 'csv'){
			$urlImage = '/Images/graph.png';
		}else{
			if(!empty($data->getUrlMiniatureDonneeUtilisateur())){
				$urlImage = 'data:image/png;charset=utf8;base64,'.base64_encode(file_get_contents($data->getUrlMiniatureDonneeUtilisateur()));
			}else{
				$urlImage = '/Images/image.png';
			}
		}
		if(!$data->getIsInCache()){
			// Calcul du temps de sauvegarde restant de la donnée
			preg_match('#(.+)-(.+)-(.+)#', $data->getDatePublicationDonneeUtilisateur(), $date);
			$timeStampData = mktime(0,0,0,$date[2],$date[3],$date[1]);
			$daysRemaining = round(($timeStampData + $delaySaveDataUser - time())/(60*60*24));
			if($daysRemaining < 1){
				$daysRemaining = 0;
				$labelColor = 'label-danger';
			}else if($daysRemaining <6){
				$labelColor = 'label-warning';
			}else{
				$labelColor = 'label-default';
			}
		}else{
			$daysRemaining = 0;
			$labelColor = 'label-danger';
		}

		$listeDonneeUtilisateur.='<div class="donneeUser" draggable="true" id="'.$data->getIdDonneeUtilisateur().'"
		data-html="true" title="'.$data->getNomDonneeUtilisateur().'" data-toggle="popover" data-content="
			<ul class=\'list-unstyled\'>
				<li>Type : '.$data->getTypeDonneeUtilisateur()->getNomTypeDonneeUtilisateur().'</li>
				<li><span class=\'label label-pill '.$labelColor.'\'>'.$daysRemaining.'</span> day(s) remaining</li>
			</ul>">
			<img src="'.$urlImage.'"/>
			<div class="alertSaving '.$labelColor.'-saved"></div>
		</div>';
	}

	// On prépare la réponse
	$reponse = array(
			'listeDonneeUtilisateur' => $listeDonneeUtilisateur,
			'tailleMaxDonneesUtilisateur' => $tailleMaxDonneesUtilisateur,
			'tailleMoDonneesUtilisateur' => $tailleMoDonneesUtilisateur,
			'progressionPourcent' => $progressionPourcent
		);

	// On envoie la réponse au format JSON
	echo json_encode($reponse);
}?>