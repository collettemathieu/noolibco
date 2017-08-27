var fs= require('fs');
var xml2js = require('xml2js');
var async=require('asyncawait/async');
var await=require('asyncawait/await');
var parser = new xml2js.Parser();

function Config(){
	//this.vars=[];
}

Config.prototype.getVar= async function(categorie, sousCategorie, variable){
	var xml = await(getSimpleXmlFile());
	xml=xml['definitions'];

	if(xml[categorie] != ''){
		var donneesCategorie = xml[categorie];
         
		if(donneesCategorie[0][sousCategorie] != undefined){

			var donneesSousCategorie = donneesCategorie[0][sousCategorie];
			
			if(donneesSousCategorie[0]['define']!= undefined){
				return donneesSousCategorie[0]['define'][0]['$']['value'];
			}else{
				return null;
			}
		}else{
			return null;
		}

	}else{
		return null;
	}
}

getSimpleXmlFile = function() {
	  return new Promise(function(resolve,rejection){
	  	var xml = fs.readFile('/home/noolibco/ConfigSystem/fichierDeConfiguration.xml',function(err,data){
			parser.parseString(data,function(err,result){
				return resolve(result);
			});
		});
	  });
}
module.exports= Config;