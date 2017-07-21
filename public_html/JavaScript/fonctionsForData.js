/* Les espaces de noms */
var namespaceGraphData = {},
    namespaceGraphLocalData = {};

/* Les fonctions */

// Permet d'afficher une donnée chargée localement sous forme de graphe
function graphLocalData(object, num_points_display, overlay){
    if(object.get_name() === 'EDFFile' || object.get_name() === 'TXTFile'){
        if(object.get_name() === 'EDFFile'){

            var numSignal = parseInt(object.get_header_item('num_signals_in_data_record')),
                sampleRate = Math.round(object.get_signal_item(0, 'num_samples_in_data_record')/object.get_header_item('duration_of_data_record')),
                totalPoint = Math.round(object.get_header_item('num_data_records')*sampleRate),
                startTime = 0,
                endTime = object.get_header_item('num_data_records');
        }else if(object.get_name() === 'TXTFile'){

            var numSignal = object.get_number_of_signals(),
                sampleRate = object.get_sample_rate(),
                totalPoint = object.get_size_signals(),
                startTime = 0,
                endTime = totalPoint;
        }
        var rowSelected = [], // Signaux sélectionnés par l'utilisateur
            legende = object.get_legend(), // On récupère la légende
            bool = false,
            visibleLegend = true,
            visibleSignal = true;
        rowSelected[0] = 0;  

        // Déclaration des variables globales
        namespaceGraphLocalData.tmin = 0;
        namespaceGraphLocalData.tmax = namespaceGraphLocalData.tmin + Math.round(num_points_display/sampleRate)-1; // On prend les num_points_display premiers points
        if(namespaceGraphLocalData.tmax > endTime) namespaceGraphLocalData.tmax = endTime -1;
        namespaceGraphLocalData.dataForForm = {
            'data': object.get_series(),
            'rowSelected': rowSelected,
            'legend': legende,
            'sampleRate': sampleRate,
            'minimum': namespaceGraphLocalData.tmin,
            'maximum': namespaceGraphLocalData.tmax
        };
        

        /**
        * Charger les données selon le minimum et le maximum demandées par l'utilisateur (lors d'un zoom)
        */
        function resize(startTimeResize, endTimeResize) {
            
            if(startTimeResize < namespaceGraphLocalData.tmin || endTimeResize > namespaceGraphLocalData.tmax){
                
                var chart = $(overlay).find('.graphResult').highcharts();
                
                // On empêche de charger ou d'uploader une nouvelle donnée
                $('#labelSubmitLocalData').attr('for', '');
                $('#labelSubmitLocalData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');
                $('#labelFileLocalData').attr('for', '');
                $('#labelFileLocalData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');

                // On met à jour les variables globales                
                namespaceGraphLocalData.tmin = startTimeResize;
                namespaceGraphLocalData.tmax = endTimeResize;

                chart.showLoading('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading data...');
                
                // setTimeout pour simuler le temps de réponse du serveur
                setTimeout(function(){
                    try{
                        // Importation des nouvelles données
                        var data = object.get_all_signals_data(startTimeResize, endTimeResize);
                        
                        // Mise à jour du graphique
                        for(var i=0, c=numSignal ; i < c ; ++i){
                            chart.get(legende[i]+'-serie').setData(data[i]);
                        }
                        // Mise à jour de la variable data
                        namespaceGraphLocalData.dataForForm['data'] = data;
                    }
                    catch(e){
                        var response = {
                            'erreurs': '<p>A system error has occurred: '+e+'</p>'
                        };

                        displayInformationsClient(response);
                    }
                    chart.hideLoading();
                    
                    // On permet d'uploader la donnée
                    $('#labelSubmitLocalData').attr('for', 'submitLocalData');
                    $('#labelSubmitLocalData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Load this data');
                    // On permet l'ajout d'une donnée
                    $('#labelFileLocalData').attr('for', 'fileLocalData');
                    $('#labelFileLocalData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Add a new data');

                }, 10);   
            }

            // Mise à jour de la variable globale Data
            namespaceGraphLocalData.dataForForm['minimum'] = startTimeResize;
            namespaceGraphLocalData.dataForForm['maximum'] = endTimeResize;
            
        }


        // Création du graphique
        $(overlay).find('.graphResult').highcharts('StockChart', {
            chart : {
                reflow: false,
                animation: false,
                zoomType: 'x',
                height: parseInt($('html').css('height'))*0.7,
                width: parseInt($('html').css('width'))*0.86
            },
            credits: {
              enabled: false
            },
            tooltip: {
                enabled: false,
                animation: false
            },
            navigator : {
                adaptToUpdatedData: false,
                series : {
                    id: 'navigator',
                    data: object.get_navigator()
                },
                xAxis: {
                    labels: {   
                        formatter: function () {
                            return Math.round(this.value/(1000*sampleRate)*100)/100;
                        }
                    }
                }
                
            },
            legend: {
                enabled: true
            },
            plotOptions: {
                line: {
                    animation: false,
                    stickyTracking: true,
                    shadow: false,
                    dataLabels: {
                        enabled: false,
                        style: { textShadow: false }
                    },
                    enableMouseTracking: false
                },
                series: {
                    animation: false,
                    enableMouseTracking: false,
                    stickyTracking: true,
                    shadow: false,
                    dataLabels: {
                        style: { textShadow: false }
                    },
                    events: {
                        legendItemClick: function(event) {
                           
                            var numberCol = legende.indexOf(this.name);
                            // On met à jour les colonnes sélectionnées pour l'extraction des données
                            if(numberCol != -1){
                                if(this.visible){
                                    var index = rowSelected.indexOf(numberCol);
                                    rowSelected.splice(index, 1);
                                }else{
                                    rowSelected.push(numberCol);
                                    rowSelected.sort(function(a,b){
                                        if(a < b){
                                            return -1;
                                        }else if(a>b){
                                            return 1;
                                        }else{
                                            return 0.
                                        }
                                    });
                                }
                                namespaceGraphLocalData.dataForForm['rowSelected'] = rowSelected;
                            }
                        }
                    },
                    lineWidth: 1
                }
            },
            scrollbar: {
                liveRedraw: false
            },

            title: {
                text: ''
            },

            rangeSelector : {
                buttons: [{
                    type: 'all',
                    text: 'All'
                }],
                inputEnabled: false, // it supports only days
                selected : 1 // all
            },
            loading: {
                style: {
                    position: 'absolute',
                    backgroundColor: 'white',
                    opacity: 0.7,
                    textAlign: 'center'
                },
                labelStyle: {
                    position: 'relative',
                    top: '0',
                    color: 'black',
                    fontSize: '1em',
                    fontWeight: 'normal'
                }
            },
            xAxis : {
                events : {
                    setExtremes : function(e){
                       
                        // On contrôle si on sort du cadre du graphique
                        if(typeof e.min == 'undefined' && typeof e.max == 'undefined'){
                            resize(startTime, endTime);
                        }else if(typeof e.min == 'undefined'){
                            resize(startTime, e.max);
                        }
                        else if(typeof e.max == 'undefined'){
                            resize(e.min, endTime);
                        }
                        else{
                            var startTimeSet = Math.round(e.min/(1000*sampleRate)),
                                endTimeSet = Math.round(e.max/(1000*sampleRate));
                                
                            // On contrôle l'intervalle choisit selon la limite de points de l'objet, sinon on le réinitialise
                            if((endTimeSet-startTimeSet)*sampleRate > object.get_limit_points_display() + 1000){  
                                  
                                var chart = $(overlay).find('.graphResult').highcharts();
                                setTimeout(function(){

                                    chart.xAxis[0].setExtremes(startTimeSet*1000*sampleRate, object.get_limit_points_display()*1000 + startTimeSet*1000*sampleRate);
                                    var response = {
                                      'erreurs': '<p>The data interval has been adjusted automatically for a better display.</p>'
                                    };
                                    displayInformationsClient(response);
                                }, 1);
                                
                            }else{
                                resize(startTimeSet, endTimeSet);
                            }
                        }
                    }
                    
                },
                labels: {   
                    formatter: function () {
                        return Math.round(this.value/(1000*sampleRate)*100)/100;
                    }
                }
            }
        });

        
        // On ajoute les series successives au graph
        var chart = $(overlay).find('.graphResult').highcharts();

        if(numSignal > 4){
            visibleSignal = false;
        }

        for(var i=0, c=numSignal ; i < c ; ++i){
            
            if(i%2 == 0){
                bool = false;
            }else{
                bool = true;
            }

            chart.addAxis({
                id: legende[i] + '-axis',
                gridLineWidth: 0,
                title: {
                    text: legende[i],
                    style: {
                        color: Highcharts.getOptions().colors[i]
                    },
                    enabled: visibleSignal
                },
                labels: {
                    formatter: function () {
                        return this.value;
                    },
                    style: {
                        color: Highcharts.getOptions().colors[i]
                    },
                    enabled: visibleSignal
                },
                opposite: bool

            });

            chart.addSeries({
                name: legende[i],
                yAxis: legende[i] + '-axis',
                id: legende[i]+'-serie',
                visible: visibleLegend,
                data: object.get_series(i)
            });

            visibleLegend = false;

        }
    }else{
        var response = {
            'erreurs': '<p>[JS] No object has been identified.</p>'
        };
        displayInformationsClient(response);
    }
}



// Permet d'afficher les données CSV sous forme d'une table
function tableData(data, overlay){
    
    var table = $(overlay).find('.tableResult table');
    table.empty(); // On vide la table

    table.append('<thead><tr></tr></thead><tbody></tbody>');
    var headTable = table.find('thead tr'),
        bodyTable = table.find('tbody'),
        tailleTable, sampleRate, text;

    if(data.get_name() === 'TXTFile'){
        tailleTable = data.get_size_signals();
    }else if(data.get_name() === 'EDFFile'){
        sampleRate = Math.round(data.get_signal_item(0, 'num_samples_in_data_record')/data.get_header_item('duration_of_data_record'));
        tailleTable = Math.round(data.get_header_item('num_data_records')*sampleRate);
    }

    if(tailleTable > 50) tailleTable = 50;

    // On renseigne la légende du tableau
    for(var i=0, c=data.get_legend().length ; i < c ; ++i){
        headTable.append('<th>'+data.get_legend()[i]+'</th>');
    }
    // On renseigne les valeurs du tableau
    for(var i=0 ; i < tailleTable-1 ; ++i){
        text += '<tr>';
        for(var j=0, c=data.get_series().length ; j < c ; ++j){
            text += '<td>'+data.get_series()[j][i][1]+'</td>';
        }
        text += '</tr>';
    }
    text += '<tr>';
    for(var j=0, c=data.get_series().length ; j < c ; ++j){
        text += '<td>...</td>';
    }
    text += '</tr>';
    bodyTable.append(text);
    
}




// Permet d'afficher une donnée stockée sur le serveur sous forme de graphe
function graphData(graphObjet){

    /**
     * Charger les données selon le minimum et le maximum demandées par l'utilisateur (lors d'un zoom)
    */
    function afterSetExtremes(min, max) {
        
        // Mise à jour des variables globales
        namespaceGraphData.tmin = min/1000;
        namespaceGraphData.tmax = max/1000;

        var min = Math.round(min/1000),
            max = Math.round(max/1000);
       
        if(min < namespaceGraphData.startTime || max > namespaceGraphData.endTime){

            // On empêche de mettre à jour une nouvelle donnée
            $('#labelSubmitUpdateData').attr('for', '');
            $('#labelSubmitUpdateData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');
           
            var chart = $('#overlayAfficherDonnee').find('.graphResult').highcharts();
            
            chart.showLoading('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading data...');

            // Mise à jour des variables globales
            namespaceGraphData.startTime = min;
            namespaceGraphData.endTime = max;

            $.getJSON('/HandleData/SendDataForHighStock/idDonnee='+graphObjet['idDonneeUtilisateur']+'debutDonnee='+min+'finDonnee='+max, function (response) {
                
                var data = response['data'];

                // Mise à jour du graphique
                for(var i=0, c=namespaceGraphData.numSignal ; i < c ; ++i){
                    
                    chart.get(namespaceGraphData.legende[i]+'-serie').setData(data[i]);
                
                }

                chart.hideLoading();

            });

            // On permet de mettre à jour la donnée
            $('#labelSubmitUpdateData').attr('for', 'submitUpdateData');
            $('#labelSubmitUpdateData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Update this data');
        }
    }

    // Les données sont chargées en ajax
    $.getJSON('/HandleData/SendDataForHighStock/idDonnee='+graphObjet['idDonneeUtilisateur'], function (response) {
        
        // On cache l'indicateur de chargement
        $('#image-waiter').hide();

        if(response['erreurs']){         
             displayInformationsClient(response);
        }else{

            var isGraphSelected = $('#overlayAfficherDonnee').find('.graphResult').hasClass('active');
    
            if(isGraphSelected){
                // On permet de sauvegarder la donnée
                $('#labelSubmitUpdateData').attr('for', 'submitUpdateData');
                $('#labelSubmitUpdateData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Update this data');
            }

            // On créé le tableau de données
            var tabSeries = [],
                bool = false,
                visibleLegend = true,
                visibleSignal = true,
                serieNavigator = response['serieNavigator'];

            // Variables globales
            namespaceGraphData.startTime = response['startTime'];
            namespaceGraphData.endTime = response['endTime'];
            namespaceGraphData.legende = response['legende'];
            namespaceGraphData.numSignal = namespaceGraphData.legende.length;
            namespaceGraphData.sampleRate = response['sampleRate'];
            namespaceGraphData.tailleSignal = response['tailleSignal'];
            namespaceGraphData.rowSelected = [];
            namespaceGraphData.tmin = namespaceGraphData.startTime;
            namespaceGraphData.tmax = namespaceGraphData.endTime;

            // Initialisation
            for(var i=0; i<namespaceGraphData.numSignal ; ++i){
                tabSeries[i] = response['data'][i];  
            }
            namespaceGraphData.rowSelected[0] = 0;

            // Création de la table
            var txtReader = new TXTFile();
            txtReader.set_series(tabSeries);
            txtReader.set_legend(response['legende']);
            tableData(txtReader, '#overlayAfficherDonnee');

            // Création du graphique
            $('#overlayAfficherDonnee').find('.graphResult').highcharts('StockChart', {
                chart : {
                    reflow: false,
                    animation: false,
                    zoomType: 'x',
                    height: parseInt($('html').css('height'))*0.7,
                    width: parseInt($('html').css('width'))*0.86
                },
                credits: {
                  enabled: false
                },
                tooltip: {
                    enabled: false,
                    animation: false
                },
                navigator : {
                    adaptToUpdatedData: false,
                    series : {
                        id: 'navigator',
                        data : serieNavigator
                    },
                    xAxis: {
                        labels: {   
                            formatter: function () {
                                return Math.round(this.value/(1000*namespaceGraphData.sampleRate)*100)/100; //(100/100) pour 2 chiffres après la virgule
                            }
                        }
                    }
                    
                },
                legend: {
                    enabled: true
                },
                plotOptions: {
                    line: {
                    animation: false,
                    stickyTracking: true,
                    shadow: false,
                    dataLabels: {
                            enabled: false,
                            style: { textShadow: false }
                        },
                        enableMouseTracking: false
                    },
                    series: {
                        animation: false,
                        enableMouseTracking: false,
                        stickyTracking: true,
                        shadow: false,
                        events: {
                            legendItemClick: function(event) {
                                var numberCol = namespaceGraphData.legende.indexOf(this.name);
                                // On met à jour les colonnes sélectionnées pour l'extraction des données
                                if(numberCol != -1){
                                    if(this.visible){
                                        var index = namespaceGraphData.rowSelected.indexOf(numberCol);
                                        namespaceGraphData.rowSelected.splice(index, 1);
                                    }else{
                                        namespaceGraphData.rowSelected.push(numberCol);
                                        namespaceGraphData.rowSelected.sort(function(a,b){
                                            if(a < b){
                                                return -1;
                                            }else if(a>b){
                                                return 1;
                                            }else{
                                                return 0.
                                            }
                                        });
                                    }
                                }
                            }
                        },
                        lineWidth: 1
                    }
                },
                scrollbar: {
                    liveRedraw: false
                },

                rangeSelector : {
                    buttons: [{
                        type: 'all',
                        text: 'All'
                    }],
                    inputEnabled: false, // it supports only days
                    selected : 1 // all
                },
                loading: {
                    style: {
                        position: 'absolute',
                        backgroundColor: 'white',
                        opacity: 0.6,
                        textAlign: 'center'
                    },
                    labelStyle: {
                        position: 'relative',
                        top: '0',
                        color: 'black',
                        fontSize: '1em',
                        fontWeight: 'normal'
                    }
                },
                xAxis : {
                    events : {
                        setExtremes : function(e){
                             // On contrôle si on sort du cadre du graphique
                            if(typeof e.min == 'undefined' && typeof e.max == 'undefined'){
                                afterSetExtremes(0, 30000);
                            }else if(typeof e.min == 'undefined'){
                                afterSetExtremes(0, e.max);
                            }
                            else if(typeof e.max == 'undefined'){
                                afterSetExtremes(e.min, 30000);
                            }
                            else{
                                afterSetExtremes(e.min, e.max);
                            }
                        }
                    },
                    labels: {   
                        formatter: function () {
                            return Math.round(this.value/(1000*namespaceGraphData.sampleRate)*100)/100;
                        }
                    }
                }
            });
            
            // On ajoute les axes et les series successives au graphe
            var chart = $('#overlayAfficherDonnee').find('.graphResult').highcharts();

            if(namespaceGraphData.numSignal > 4){
                visibleSignal = false;
            }


            for(var i=0, c=namespaceGraphData.numSignal ; i < c ; ++i){
                
                if(i%2 == 0){
                    bool = false;
                }else{
                    bool = true;
                }

                chart.addAxis({
                    id: namespaceGraphData.legende[i] + '-axis',
                    gridLineWidth: 0,
                    title: {
                        text: namespaceGraphData.legende[i],
                        style: {
                            color: Highcharts.getOptions().colors[i]
                        },
                        enabled: visibleSignal
                    },
                    labels: {
                        formatter: function () {
                            return this.value;
                        },
                        style: {
                            color: Highcharts.getOptions().colors[i]
                        },
                        enabled: visibleSignal
                    },
                    opposite: bool

                });

                chart.addSeries({
                    name: namespaceGraphData.legende[i],
                    yAxis: namespaceGraphData.legende[i] + '-axis',
                    id: namespaceGraphData.legende[i]+'-serie',
                    visible: visibleLegend,
                    data: tabSeries[i]
                });

                visibleLegend = false;
            }
        }
    });
}


// On contrôle si l'onglet graph est actif => on autorise l'update
$('#overlayAfficherDonnee').find('.modal-body a').click(function(e){

    var href = $(e.target).attr('href');
    
    if(href === '#graphResult'){
        // On permet de sauvegarder la donnée
        $('#labelSubmitUpdateData').attr('for', 'submitUpdateData');
        $('#labelSubmitUpdateData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Update this data');
    }else{
        // On empêche de mettre à jour une nouvelle donnée
        $('#labelSubmitUpdateData').attr('for', '');
        $('#labelSubmitUpdateData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');
    }
});

// On contrôle l'envoi du formulaire pour la sauvegarde de la donnée
$('#formUpdateData').on('submit', function(e){

    // On montre l'indicateur de chargement
    $('#image-waiter').show();

    // On permet de mettre à jour la donnée
    $('#labelSubmitUpdateData').attr('for', '');
    $('#labelSubmitUpdateData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');
        
    // On empêche l'envoi du formulaire
    e.preventDefault();

    // On lance la requête en ajax avec les paramètres sélectionnés par l'utilisateur
    $(this).find('#debutDonnee').attr('value', namespaceGraphData.tmin);
    $(this).find('#finDonnee').attr('value', namespaceGraphData.tmax);
    $(this).find('#rowSelected').attr('value', JSON.stringify(namespaceGraphData.rowSelected));
    

    // Envoi de la requête HTTP en mode asynchrone
    $.ajax({
      url: '/HandleData/UpdateData',
      type: 'POST',
      async: true,
      cache: false,
      processData: false,
      data: $(this).serialize(),
      success: function(response) {
        
        response = JSON.parse(response);
        displayInformationsClient(response);
        // On ferme la fenêtre modale
        $('#overlayAfficherDonnee').modal('hide');

        // On permet de mettre à jour la donnée
        $('#labelSubmitUpdateData').attr('for', 'submitUpdateData');
        $('#labelSubmitUpdateData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Update this data');
        
      },
      error: function(){
        var response = {
          'erreurs': '<p>A system error has occurred.</p>'
        };
        displayInformationsClient(response);

        // On permet de mettre à jour la donnée
        $('#labelSubmitUpdateData').attr('for', 'submitUpdateData');
        $('#labelSubmitUpdateData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Update this data');
      }
    });

});



// Permet d'afficher une donnée utilisateur
function forShowingData(element){

    var overlayAfficherDonnee = $('#overlayAfficherDonnee');

    // On montre l'indicateur de chargement
    $('#image-waiter').show();

    // On ne permet pas de sauvegarder la donnée
    $('#labelSubmitUpdateData').attr('for', '');
    $('#labelSubmitUpdateData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');

    // On efface la donnée précédente
    overlayAfficherDonnee.find('.tableResult table').empty();
    overlayAfficherDonnee.find('.graphResult').empty();
    overlayAfficherDonnee.find('.row last').empty();
    overlayAfficherDonnee.find('.row first').empty();
    overlayAfficherDonnee.find('.row:nth-child(2)').empty();
    overlayAfficherDonnee.find('.row:first').addClass('hidden');
    overlayAfficherDonnee.find('.row:last').addClass('hidden');

    // On ouvre la fenêtre
    overlayAfficherDonnee.modal('show');

    
    // On lance la requête Ajax
    // Envoi de la requête HTTP en mode asynchrone
    $.ajax({
      url: '/HandleData/ShowData',
      type: 'POST',
      async: true,
      cache: false,
      data: { 
        idDonneeUtilisateur: element.attr('id')
        },
      success: function(response) {
        
        // On récupère la réponse au format JSON
        response = JSON.parse(response);

        if(response['erreurs']){
            displayInformationsClient(response);
            // On ferme la fenêtre
            overlayAfficherDonnee.modal('hide');

        }else{
            // On insère l'id de la donnée affichée
            overlayAfficherDonnee.find('#idDonneeUtilisateur').attr('value', response['idDonneeUtilisateur']);    
            
            // Si c'est une image standard
            if(response['imageStandard']){
                // On cache l'indicateur de chargement
                $('#image-waiter').hide();

                overlayAfficherDonnee.find('.row:nth-child(2)').html(response['imageStandard']);
           
            }else if(response['imageDicom']){  // Si c'est une image DICOM
                // On cache l'indicateur de chargement
                $('#image-waiter').hide();

                var element = overlayAfficherDonnee.find('.row:nth-child(2)').get(0),
                    imageDicom = base64toBlob(response['imageDicom'], 'application/dicom');
                try {
                      cornerstone.enable(element);
                      cornerstone.loadImage(imageDicom).then(function(image) {
                          
                        var viewport = cornerstone.getDefaultViewportForImage(element, image);
                        cornerstone.displayImage(element, image, viewport);
                        cornerstoneTools.mouseInput.enable(element);
                        cornerstoneTools.mouseWheelInput.enable(element);
                        cornerstoneTools.wwwc.activate(element, 1); // ww/wc is the default tool for left mouse button
                        cornerstoneTools.pan.activate(element, 2); // pan is the default tool for middle mouse button
                        cornerstoneTools.zoom.activate(element, 4); // zoom is the default tool for right mouse button
                        cornerstoneTools.zoomWheel.activate(element); // zoom is the default tool for middle mouse wheel
                          
                      }, function(e) {
                          var response = {
                              'erreurs': '<p>[JS] A system error has occured: '+e+'</p>'
                          };

                          displayInformationsClient(response);
                        
                      });
                  }
                  catch(e) {
                    var response = {
                      'erreurs': '<p>[JS] A system error has occured: '+e+'</p>'
                    };

                    displayInformationsClient(response);
                 }
            }else if(response['dataFile'] && response['ext']){// Si c'est un fichier xml
                
                // On cache l'indicateur de chargement
                $('#image-waiter').hide();
                overlayAfficherDonnee.find('.row:last').removeClass('hidden');
                // On crée un éditor de texte
                var element = overlayAfficherDonnee.find('.row:last'),
                    editor = ace.edit(element[0]),
                    fileExt = response['ext'].toLowerCase(),
                    fileData = base64_decode(response['dataFile']);

                editor.$blockScrolling = Infinity; // Remove warning
                editor.setHighlightActiveLine(true); // Underline
                editor.setValue(fileData, 1);
                editor.setTheme('ace/theme/monokai'); // Edit the theme
                editor.getSession().setMode('ace/mode/'+fileExt); // Edit the mode


            }else{ // Si c'est une table ou un graphe
                overlayAfficherDonnee.find('.row:first').removeClass('hidden');
                graphData(response);
            }
        }
        
      },
      error: function(){
        var response = {
          'erreurs': '<p>A system error has occurred.</p>'
        };
        displayInformationsClient(response);

        // On permet de mettre à jour la donnée
        $('#labelSubmitUpdateData').attr('for', 'submitUpdateData');
        $('#labelSubmitUpdateData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Update this data');
      }
    });
}


// Permet de sauvegarder une copie de la donnée utilisateur sur le serveur
function forSavingData(){

    $('#boutonSaveDataOnServer').droppable({

        drop: function(event, ui){
             
            $.post('/HandleData/SaveDataOnServer', {
                idDonneeUtilisateur: ui.draggable.attr('id')
            }, 
            function(response){
                
                // On récupère la réponse au format JSON
                response = JSON.parse(response);

                if(response['erreurs']){

                    displayInformationsClient(response);

                }else if(response['reussites']){

                    $('#inListeDonneesUser').html(response['listeDonneeUtilisateur']);
                    showData();
                    displayInformationsClient(response);
                }
            });

        }
        
    });
}


// Pour supprimer une donnée utilisateur par ajax
function forDeletingData(){
	$('#poubelleDonneeUtilisateur').droppable({

		drop: function(event, ui){
			$.post('/HandleData/DeleteData', {
				idDonneeUtilisateur: ui.draggable.attr('id')
			}, 
			function(response){

				// On récupère la réponse au format JSON
				response = JSON.parse(response);

				if(response['erreurs']){

			    	displayInformationsClient(response);

				}else if(response['reussites']){

			    	displayInformationsClient(response);
			    	
				}

                $('#noospace .donneeUser').each(function(){
                    if($(this).attr('id') === ui.draggable.attr('id')){
                        $(this).remove();
                    }
                });
                ui.draggable.remove();

                 // On met à jour la barre d'espace disponible
                $('#progressBarEspaceDisponibleData').attr('value', response['tailleMoDonneesUtilisateur']);
                $('#progressBarEspaceDisponibleData').attr('max', response['tailleMaxDonneesUtilisateur']);
                $('#progressionPourcent').html(response['progressionPourcent']+ '%');
			});
		}
		
	});
}

// Pour ouvrir/fermer le gestionnaire de données
function openGestionnaireDonnees(){
    var boutonShowGestionnaireDonnees = $('#boutonShowGestionnaireDonnees'),
        overlayGestionnaireDonnees = $('#overlayGestionnaireDonnees'),
        decalageInitiale = -parseInt(overlayGestionnaireDonnees.css('width')),
        decalageFinale = 0;

    // Pour fermer
    if(parseInt(overlayGestionnaireDonnees.css('left')) >= decalageFinale){

        var tailleManager = parseInt($('#overlayGestionnaireDonnees').css('width')),
            positionLeftBouton = parseInt($('#boutonShowGestionnaireDonnees').css('left'));
        if(tailleManager > 500){
            $('#overlayGestionnaireDonnees').animate({'width': tailleManager-400}, 1500);
            $('#boutonShowGestionnaireDonnees').animate({'left':positionLeftBouton-400}, 1500);
            $('#laMule').hide();
            $('#formMule').find('.form-group').empty(); // On efface les données précédentes
        }else{
            // On anime le déplacement du div du gestionnaire de données
            overlayGestionnaireDonnees.animate({'left':decalageInitiale}, 1500);
            boutonShowGestionnaireDonnees.animate({'left':decalageFinale}, 1500);
            // On efface les données
            setTimeout(function(){
                 $('#inListeDonneesUser').empty();
            }, 1500);
            dataManagerAlreadyOpened = false;
        }
    }else{ // Pour ouvrir
        // Envoi de la requête HTTP en mode asynchrone
        $.ajax({
            url: '/HandleData/GestionnaireDeDonnees',
            type: 'POST',
            success: function(response) {
                try{
                    response = JSON.parse(response);
                    $('#inListeDonneesUser').html(response['listeDonneeUtilisateur']);
                    
                    dataManagerAlreadyOpened = true;

                    // On traite l'ajout de nouvelles données
                    if(!addDataAlreadyLoaded){
                        addData();
                        addDataAlreadyLoaded = true;
                    }

                    // On traite les données importées
                    var parametres = {
                        'tailleMoDonneesUtilisateur': response['tailleMoDonneesUtilisateur'],
                        'tailleMaxDonneesUtilisateur': response['tailleMaxDonneesUtilisateur'],
                        'progressionPourcent': response['progressionPourcent']
                    };
                    showData(parametres);
                }
                catch(e){
                    var response = {};
                    response['erreurs'] = '<p>A system error has occurred.</p>';
                    displayInformationsClient(response);
                }
            },
            error: function(){
                var response = {};
                response['erreurs'] = '<p>A system error has occurred.</p>';
                displayInformationsClient(response);
            }
        });

        // On anime le déplacement du div du gestionnaire de données
        overlayGestionnaireDonnees.css('display', 'inline-block');
        overlayGestionnaireDonnees.animate({'left':decalageFinale}, 1500);
        boutonShowGestionnaireDonnees.animate({'left':-decalageInitiale}, 1500);
    }
}

// Pour transformer un tableau en CSV
function tableToCSV(legend, table){
    if(Array.isArray(legend) && Array.isArray(table)){
        var string = '',
            sizeTable = table.length,
            sizeLegend = legend.length,
            sizeColumn = table[sizeTable-1].length;

        // On insère la légende
        for(var j=0; j<sizeLegend; ++j){
            if(j===sizeLegend-1){
                string += legend[j];
            }else{
                string += legend[j]+';';
            }
        }
        // On insère les données
        string += '\r\n';
        for(var j=0; j<sizeColumn; ++j){
            for(var i=0; i<sizeTable; ++i){
                if(i===sizeTable-1){
                    string += table[i][j];
                }else{
                    string += table[i][j]+';';
                }
            }  
            string += '\r\n';
        }

        return string;
    }else{
        console.log('tableToCSV::legend or table is not an array.');
    }
}
