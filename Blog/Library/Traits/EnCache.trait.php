<?php
namespace Library\Traits;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Trait PHP EnCache permettant la mise en cache de certains composants.|
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Trait EnCache
 * @access: public
 * @version: 1
 */

trait EnCache
{
	/**
	* Permet de créer le cache du routeur si celui-ci n'a pas été créé en format txt. Ainsi si le
	* cache existe alors l'utilisateur lit dans le fichier plutôt que de recréer toutes les routes.
	*/
	public function getCacheRouteur($cheminFichier, $classe)
	{
		$expire = time() - 3; // 3 secondes de durée de vie en cache.

		if(file_exists($cheminFichier) && filemtime($cheminFichier) > $expire)
		{
			$fichier = fopen($cheminFichier, 'r+');
			$entite = fgets($fichier);
			$entite = unserialize($entite); // On le transforme en String.
			
			fclose($fichier);
		}
		else
		{
			ob_start();

			// Création et chargement de l'entité
			echo serialize(new $classe($this));

			$entite = ob_get_clean();
			file_put_contents($cheminFichier, serialize($entite));

			ob_end_clean();
		}

		return unserialize($entite); // On le transforme en objet.
	}
}