<?php
namespace Library;


// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP Routeur pour gérer la route URL demandée par le client.	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe Routeur
 * @access: public
 * @version: 1
 */

class Routeur
{
	/* Définition des attributs */
	protected $routes = array();


	/**
	* Constructeur de la classe.
	*/

	public function __construct(\Library\Application $app)
	{
		// Création et chargement du fichier XML.
		$fichierXML = new \DOMDocument("1.0", "UTF-8");
		$fichierXML->load('../Applications/'.$app->getNomApplication().'/Config/routes.xml');

		// Récupération des routes du fichier XML.
		$routes = $fichierXML->getElementsByTagName('route');
		

		// On parcourt les routes du fichier XML et on les enregistre dans le routeur.
		foreach ($routes as $route)
		{
			
			$variables = array();

			// On regarde si des variables sont présentes dans la route du fichier XML.
			if($route->hasAttribute('vars'))
			{
				$variables = explode(',', $route->getAttribute('vars')); //Création d'un tableau des variables
			}

			// On ajoute la route au routeur.
			$url = $route->getAttribute('url');
			$module = $route->getAttribute('module');
			$action = $route->getAttribute('action');
			$routeAAjouter = new Route($url, $module, $action, $variables);
			$this->addRoute($routeAAjouter);

		}
	}
	

	/**
	* Permet d'ajouter une route dans la liste des routes. 
	* Ces routes sont obtenus à partir du fichier routes.xml.
	*/
	public function addRoute(Route $route)
	{
		if(!in_array($route, $this->routes))
		{
			$this->routes[] = $route;
		}
	}

	
	/**
	* Permet de récupérer la route correspondant à l'URL demandée par le client.
	* On compare donc chaque route du tableau $routes avec l'URL demandée par le client.
	*/
	public function getRoute($URL)
	{
		foreach($this->routes as $route)
		{
			// Si la route demandée par le client ($URL) correspond à une route du routeur
			if(($varValues = $route->match($URL)) !== false)
			{
				// Si elle a des variables
				if($route->hasVars())
				{
					$varNames = $route->getVarsNames();
					$listVars = array();

					// On créé un nouveau tableau clé/valeur.
					// Clé = nom de la variable, valeur = sa valeur.
					foreach ($varValues as $key => $match)
					{
						// La première valeur contient entièrement la chaine capturée
						if($key !== 0)
						{
							$listVars[$varNames[$key - 1]] = $match;
						}
					}

					// On assigne ce tableau de variables à la route.
					$route->setVars($listVars);
				}

				return $route;
			}
		}

		// Sinon aucune route n'a été trouvée en correspondance avec
		// l'URL demandée par le client. Nous renvoyons donc le client vers la route par défaut (soit l'acceuil).
		// Cela se passe dans l'application.

		return false;

	}

	/**
	* Permet de récupérer la route par défaut.
	*/
	public function getRouteDefault()
	{
		// On réécrit l'url PHP pour qu'elle apparaisse clairement dans le fil d'Ariane du client
		$_SERVER['REQUEST_URI'] = $this->routes[0]->getUrl();

		// On renvoie la route
		return $this->routes[0];
	}
	
}










