var allowedTableType = ['csv'], allowedTextType = ['txt'];

function TXTFile(raw_data, extFichier) {
  this.donnees = [];


  if(typeof(raw_data) !== 'undefined' && typeof(extFichier) !== 'undefined'){

    if(allowedTextType.indexOf(extFichier) != -1){
      this.donnees = this.set_data_from_txt(raw_data);
    }else if(allowedTableType.indexOf(extFichier) != -1){
      this.donnees = this.set_data_from_csv(raw_data);
    }
    this.numberOfSignals = this.donnees[0].length;
    this.sizeData = this.donnees.length-1;// On retire la légende
    this.step = Math.round(this.sizeData/1000); // On crée un navigator à 1000 points
    if(this.step < 1) this.step = 1;
  }

  this.series = [];
  this.serieNavigator = [];
  this.sampleRate = 1;
}

/**
* Retourne le nom de l'objet
*/
TXTFile.prototype.get_name = function() {
  return 'TXTFile';
}

/**
* Retourne la légende
*/
TXTFile.prototype.get_legend = function() {
  return this.donnees[0];
}

/**
* Crée une légende
*/
TXTFile.prototype.set_legend = function(array_legend) {
  if(Array.isArray(array_legend)){
    this.donnees[0] = array_legend;
  }else{
    console.log('TXTFile::array_legend is not an array.');
  }
}

/**
* Retourne la série
*/
TXTFile.prototype.get_series = function(i) {
  if(typeof i === 'number'){
    return this.series[i];
  }else{
    return this.series;
  }
}

/**
* Ajoute les séries
*/
TXTFile.prototype.set_series = function(array_series) {
  if(Array.isArray(array_series)){
    this.series = array_series;
    this.sizeData = this.series[0].length;
  }else{
    console.log('TXTFile::array_series is not an array.');
  }
}

/**
* Adapte les séries avant de les ajouter à l'objet
*/
TXTFile.prototype.construct_from_data = function(array_legend, array_data, num_points_display, sample_rate) {
  if(Array.isArray(array_data) && Array.isArray(array_legend)){
    var tempArrayDonnees = [],
        sizeArray = array_data.length;

    // On ajoute la légende à l'objet
    this.set_legend(array_legend);

    // On ajoute les données à l'objet
    for(var j=0, c=array_data[0].length; j<c; ++j){
      for(var i=0; i<sizeArray; ++i){
        tempArrayDonnees.push(array_data[i][j]);
      }
      this.donnees.push(tempArrayDonnees);
      tempArrayDonnees = [];
    }
  
    // On renseigne les attributs de l'objet
    this.sizeData = this.donnees.length-1;// On retire la légende
    this.numberOfSignals = array_data.length;
    if(typeof(sample_rate) !== 'undefined'){
      sample_rate = parseFloat(sample_rate);
      this.sampleRate = sample_rate;
    }
    this.step = Math.round(this.sizeData/1000); // On crée un navigator à 1000 points
    if(this.step < 1) this.step = 1;

    // On crée les séries et le navigator
    this.get_view_signals_data(num_points_display);
  }else{
    console.log('TXTFile::array_legend or array_data is not an array.');
  }
}

/**
* Retourne le navigateur
*/
TXTFile.prototype.get_navigator = function() {
  return this.serieNavigator;
}

/**
* Retourne la fréquence d'échantillonnage
*/
TXTFile.prototype.get_sample_rate = function() {
  return this.sampleRate;
}

/**
* Retourne la limite de points à afficher
*/
TXTFile.prototype.get_limit_points_display = function() {
  return 100000;
}

/**
* Retourne le nombre de signaux
*/
TXTFile.prototype.get_number_of_signals = function() {
  return this.numberOfSignals;
}

/**
* Retourne le nombre de signaux
*/
TXTFile.prototype.get_size_signals = function() {
  return this.sizeData;
}

/**
* Méthode pour extraire les données d'un fichier .txt
*/
TXTFile.prototype.set_data_from_txt = function(raw_data) {
  
  var testHeader = false, indice=0, tableTest = [], data = [], rgx = /(.+)/g, match;

  // On transforme les données sous forme de tableau à l'aide d'une Regex
  while (match = rgx.exec(raw_data)) {

    // On tente de récupérer les données sans le header du fichier txt
    if(!testHeader){
      if(indice<20){
        tableTest[indice] = match[0].split(/[;,\t]/);
        ++indice;
      }else{
        // On remonte la table test jusqu'à la première ligne de texte qui 
        // doit représenter la légende
        var i = tableTest.length -1;
        while(!testHeader && i >= 0){
          if(isNaN(parseFloat(tableTest[i][0]))){
            // On termine le test
            testHeader=true;
            // Si la légende est érronée, on en créé une artificielle
            if(tableTest[i].length != tableTest[i+1].length){
              var legende = [];
              for(var k=0, c=tableTest[i+1].length ; k < c ; ++k){
                legende[k] = 'No title '+k;
              }
              data.push(legende);
              // On insère la légende et les premières lignes
              for(var k=i+1, c=tableTest.length ; k < c ; ++k){
                data.push(tableTest[k]);
              }
            }else{
              // On insère la légende et les premières lignes
              for(var k=i, c=tableTest.length ; k < c ; ++k){
                data.push(tableTest[k]);
              }
            }
          }else{
            i = i-1;
          }
        }

        // Si le header n'a pas été trouvé
        if(!testHeader){
          // On termine le test même si le header n'a pas pu être supprimé
          testHeader=true;
          var legende = [];
          for(var k=0, c=tableTest[0].length ; k < c ; ++k){
            legende[k] = 'No title '+k;
          }
          data.push(legende);
          // On insère la légende et les premières lignes
          for(var k=0, c=tableTest.length ; k < c ; ++k){
            data.push(tableTest[k]);
          }
        }
      }
    }else{ // Quand le header a été extrait, on inscrit simplement les données dans le tableau
      data.push(match[0].split(/[;,\t]/));
    }
  }

  return data;
};

/**
* Méthode pour extraire les données d'un fichier .csv
*/
TXTFile.prototype.set_data_from_csv = function(raw_data) {

  var data = [],
      rgxOne = /(.+)/,
      raw_data = raw_data.replace(/;;/g,''), // On nettoie des caractères vides
      raw_data = raw_data.replace(/,,/g,''),
      raw_data = raw_data.replace(/\.\./g,''),
      matchOne = new String(rgxOne.exec(raw_data)),
      lastChar = matchOne[matchOne.length-1],
      rgx = /(.+)/g, match; // g pour prendre tous les matches

// On remplace les virgules des décimales par des points (CSV européen)
if(/[0-9]+,[0-9]+[;\t]/.test(raw_data)){
  raw_data = raw_data.replace(/,/g,'.');
}


if(lastChar === ',' || lastChar === ";" || lastChar === "."){
  // On transforme les données sous forme de tableau à l'aide d'une Regex
  while (match = rgx.exec(raw_data)) { 
    data.push(match[0].slice(0,-1).split(/[;,\t]/)); // On retire le dernier caractère
  }
}else{
  // On transforme les données sous forme de tableau à l'aide d'une Regex
  while (match = rgx.exec(raw_data)) {   
    data.push(match[0].split(/[;,\t]/));
  }
}

  return data;
};



/**
* Méthode pour récupérer l'ensemble des signaux entre deux points de temps
*/
TXTFile.prototype.get_all_signals_data = function(start, end) {
  
  // Initialisation
  var k=0;
  this.series = [];

  for(var i=0; i<this.numberOfSignals; ++i){
      this.series[i] = [];
  }

  for(var j=start+1; j<end; ++j){
    for(var i=0; i<this.numberOfSignals; ++i){
        this.series[i][k] = [((j-1)/this.sampleRate)*1000*this.sampleRate, parseFloat(this.donnees[j][i])];
    }
    ++k;
  }

  return this.series;

};

/**
* Méthode pour récupérer la légende, le navigateur (Highchart) et les signaux
*/
TXTFile.prototype.get_view_signals_data = function(num_points_display) {
  // Initialisation
  var k=0;
  this.series = [];
  for(var i=0; i<this.numberOfSignals; ++i){
      this.series[i] = [];
  }

  for(var j=1; j<this.sizeData+1; ++j){
      if(j < num_points_display){
        for(var i=0; i<this.numberOfSignals; ++i){
            this.series[i][j-1] = [((j-1)/this.sampleRate)*1000*this.sampleRate, parseFloat(this.donnees[j][i])];
        }
      }
      if((j-1) % this.step == 0){
          this.serieNavigator[k] = [((j-1)/this.sampleRate)*1000*this.sampleRate, parseFloat(this.donnees[j][0])];
          ++k;
      }
  }

};