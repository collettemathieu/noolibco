<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour le dépôt des applications. Le dépôt se |
// | réalise en plusieurs étapes avant d'être validé en ajax.			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe DefautController
 * @access: public
 * @version: 1
 */	


namespace Applications\SubmitAnApplication\Modules\Defaut;
	
use Library\Entities\Categorie;
use Library\Entities\Auteur;
use Library\Entities\ApplicationAuteur;

class DefautController extends \Library\BackController{

	use \Library\Traits\FonctionsUniverselles;

	/**
	* Méthode pour afficher le formulaire de dépôt d'une application - Etape 1
	*/
	public function executeShow($request){}
}