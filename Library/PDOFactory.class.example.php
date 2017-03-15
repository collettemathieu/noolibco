<?php
namespace Library;

// +----------------------------------------------------------------------+
// | PHP Version 7                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP PDOFactory pour générer la connexion SQL.	  		 	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe PDOFactory
 * @access: public
 * @version: 1
 */

class PDOFactory
{
	
	public static function getMysqlConnexion()
		{
			try
			{
				/*On choisit selon que la base soit locale ou sur le serveur distant*/				
				$ipServeur = $_SERVER['SERVER_ADDR'];
				
				$ipServeurDistant = 'xxx.xxx.xxx.xxx';
				if($ipServeur != $ipServeurDistant)
				{
					$bdd = new \PDO('mysql:host=localhost; dbname=xxx', 'xxx', 'xxx');
					$bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);				
				}
				else
				{
					$bdd = new \PDO('mysql:host=localhost; dbname=xxx', 'xxx', 'xxx');
					$bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				}
				
				return $bdd;
			}
			catch(PDOException $e)
			{
				$textErreur = $e->getMessage();
				echo '<script> alert("Erreur de connexion à la base de données : \n\n'.$textErreur.'")</script>';
			}	
		}
}
