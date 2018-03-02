function addData(){

  // On cache l'indicateur de progression
  $('#image-local-waiter').hide();
  $('#labelFileLocalData img').attr('data-content', 'Add a new data item');

  /**
  * Chargement d'un fichier - 2 méthodes
  */
  // 1. Drag and drop d'un File
  $(document).on('dragenter',function(e){
      e.stopPropagation();
      e.preventDefault();
  });
  $(document).on('dragover',function(e){
      e.stopPropagation();
      e.preventDefault();
      $('#overlayDragAndDropData').fadeIn();
  });
  $(document).on('drop',function(e){
      e.stopPropagation();
      e.preventDefault();
      $('#overlayDragAndDropData').fadeOut();
      // Si un fichier a été déposé... sinon on ne fait rien.
      if(typeof e.originalEvent.dataTransfer != 'undefined'){
        uploadFile(e.originalEvent.dataTransfer.files);
        $('#overlayFormulaireAjoutDonneeLocale').modal('show');
      }
  });  
  $(document).on('dragleave', function(e){
     $('#overlayDragAndDropData').fadeOut();
  });

  // 2. Par input File
  $('#fileLocalData').change(function(){
    if(this.files.length == 1){ // On ne tolère qu'un seul fichier à charger
      uploadFile(this.files);
    }
  });

  // Gérér la soumission du formulaire
  $('#formUploadLocalData').on('submit', function(e) {

      e.preventDefault();

      // On affiche l'indicateur de progression
      $('#image-local-waiter').show();

      // On empêche de charger ou d'uploader une nouvelle donnée
      $('#labelSubmitLocalData').attr('for', '');
      $('#labelSubmitLocalData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');
      $('#labelFileLocalData').attr('for', '');
      $('#labelFileLocalData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');

      try{

        // On traite les données (graph de highChart) de manière à n'envoyer
        // que les données zoomées sur le graph
        if(typeof namespaceGraphLocalData.dataForForm != "undefined"){

            var data = [],
              M = [],
              debut = namespaceGraphLocalData.dataForForm['minimum']*namespaceGraphLocalData.dataForForm['sampleRate'] - namespaceGraphLocalData.dataForForm['data'][0][0][0]/1000,
              fin = namespaceGraphLocalData.dataForForm['maximum']*namespaceGraphLocalData.dataForForm['sampleRate'] - namespaceGraphLocalData.dataForForm['data'][0][0][0]/1000 - 1;
              tailleDonnee = fin-debut,
              nombreDonnee = namespaceGraphLocalData.dataForForm['rowSelected'].length,
              sizeAllowed = 300000,
              isTableSelected = $('#overlayFormulaireAjoutDonneeLocale').find('.modal-body .tableResult').hasClass('active');
              
            // Si l'onglet Table est sélectionné, alors on envoie la table en entier.
            if(isTableSelected){
              nombreDonnee = namespaceGraphLocalData.dataForForm['data'].length;
              debut = 0;
              fin = namespaceGraphLocalData.dataForForm['data'][0].length;
              for(var i=0; i<nombreDonnee; ++i){
                namespaceGraphLocalData.dataForForm['rowSelected'][i] = i;
              }
            }

            // On contrôle la taille des données
            if(tailleDonnee*nombreDonnee > sizeAllowed){
              uploadAllowed = false; // On n'autorise pas l'upload
            }else{
              // On extrait les item sélectionnés par l'utilisateur
              M[0] = 'Time'; // On ajoute la colonne des temps
              for(var k=0, c=nombreDonnee; k<c ; ++k){
                  M[k+1] = namespaceGraphLocalData.dataForForm['legend'][namespaceGraphLocalData.dataForForm['rowSelected'][k]];
              }

              data.push(M);

              // On restructure les données pour avoir la forme
              // Time | Signal 1 | Signal 2 | ...
              // 0.23 | 343.34   | 4343.34  | ...
              for(var i=debut ; i<fin ; ++i){
                  M=[];
                  M[0] = namespaceGraphLocalData.dataForForm['data'][0][i][0]/(1000*namespaceGraphLocalData.dataForForm['sampleRate']); // On récupère la colonne des temps
                  for(var k=0, len=namespaceGraphLocalData.dataForForm['rowSelected'].length; k<len ; ++k){
                      M[k+1] = namespaceGraphLocalData.dataForForm['data'][namespaceGraphLocalData.dataForForm['rowSelected'][k]][i][1];
                  }

                  data.push(M);
              }
              var dataJSON = JSON.stringify(data);
              //dataJSON = dataJSON.replace('\\r', '');// Bizarement il y a un retour chariot \r qui s'insère à la fin de la légende avec le JSON.stringify. On le retire.
              $('#localData').attr('value', dataJSON);
              $('#sampleRateDonnee').attr('value', namespaceGraphLocalData.dataForForm['sampleRate']);
              $('#tailleDonnee').attr('value', tailleDonnee);
              $('#tempsMinimumDonneeUtilisateur').attr('value', namespaceGraphLocalData.dataForForm['minimum']*namespaceGraphLocalData.dataForForm['sampleRate']);
            }
        }

        if(uploadAllowed){
          var formData = new FormData(e.target);

          // Envoi de la requête HTTP en mode asynchrone
          $.ajax({
              url: '/HandleData/AddLocalData',
              type: 'POST',
              data: formData,
              async: true,
              cache: false,
              contentType: false,
              processData: false,
              success: function(response) {
                closeOverlayLocalData(); 
                try{
                  var response = JSON.parse(response);
                }
                catch(e){
                  var response = {
                    'erreurs': '<p>A system error has occurred: '+e+'</p>'
                  };

                  displayInformationsClient(response);
                }

                if(response['reussites']){
                   
                    displayInformationsClient(response);
                    // On actualise les données
                    $('#inListeDonneesUser').html(response['listeDonneeUtilisateur']);
                    // On met à jour l'affichage du gestionnaire de données
                    var parametres = {
                        'tailleMoDonneesUtilisateur': response['tailleMoDonneesUtilisateur'],
                        'tailleMaxDonneesUtilisateur': response['tailleMaxDonneesUtilisateur'],
                        'progressionPourcent': response['progressionPourcent']
                    };

                    showData(parametres);

                }else if(response['erreurs']){
                    displayInformationsClient(response);
                }
              },
              error: function(xhr, ajaxOptions, thrownError){
                var response = {
                    'erreurs': '<p>The size of data exceeds the limit authorized.</p><p>Please try again with another data.</p>'
                };
                displayInformationsClient(response);
                closeOverlayLocalData();
              }
          });
        }else{

          var response = {
              'erreurs': '<p>The size of data exceeds the limit authorized.</p><p>Please try again with an another data/image or a smaller interval.</p>'
          };

          displayInformationsClient(response);
          // On ferme l'indicateur de progression
          $('#image-local-waiter').hide();
          // On permet d'uploader la donnée
          $('#labelSubmitLocalData').attr('for', 'submitLocalData');
          $('#labelSubmitLocalData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Load this data');
          // On permet l'ajout d'une donnée
          $('#labelFileLocalData').attr('for', 'fileLocalData');
          $('#labelFileLocalData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Add a new data'); 
        }
      }
      catch(e){
          var response = {
              'erreurs': '<p>A system error has occurred: '+e+'</p>'
          };

          displayInformationsClient(response);
          closeOverlayLocalData();
      }
  });
}

function closeOverlayLocalData(){
  // On ferme l'indicateur de progression
  $('#image-local-waiter').hide();
  // On ne permet pas d'uploader la donnée
  $('#labelSubmitLocalData').attr('for', '');
  $('#labelSubmitLocalData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');
  // On permet l'ajout d'une donnée
  $('#labelFileLocalData').attr('for', 'fileLocalData');
  $('#labelFileLocalData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Add a new data'); 

}

function uploadFile(files){
  
  // Variable d'autorisation d'upload
  uploadAllowed = true;
  var overlayFormulaire = $('#overlayFormulaireAjoutDonneeLocale');

  // On empêche de charger ou d'uploader une nouvelle donnée
  $('#labelSubmitLocalData').attr('for', '');
  $('#labelSubmitLocalData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');
  $('#labelFileLocalData').attr('for', '');
  $('#labelFileLocalData img').css('opacity', '0.2').css('cursor', 'default').attr('data-content', '');
  
  // On cache et on vide la fenêtre des données
  overlayFormulaire.find('.modal-body .row:first').addClass('hidden');
  overlayFormulaire.find('.modal-body .row:first .tableResult table').empty();
  overlayFormulaire.find('.modal-body .row:first .graphResult').empty();
  overlayFormulaire.find('.modal-body .row:last').empty();

  // On affiche l'indicateur de progression
  $('#image-local-waiter').show();

  // On réinitialise le formulaire
  $('#localData').attr('value', '');
  $('#nomFichierData').attr('value', '');
  $('#extensionFichierData').attr('value', '');

  // On supprime la variable globale dataForForm
  delete namespaceGraphLocalData.dataForForm;

  var reader = new FileReader(),
      allowedImgType = ['bmp','jpg','jpeg','png','gif','jpeg2000'],
      allowedDicomType = ['dcm'],
      allowedTableType = ['csv'],
      allowedTextType = ['txt'],
      allowedEdfType = ['edf','edf+'],
      nomFichier = files[0].name;

  // On récupère l'extension du fichier
  var extFichier = nomFichier.split('.');
  extFichier = extFichier[extFichier.length-1].toLowerCase();

  // On insert l'extension du fichier, le nom des données du formulaire
  $('#nomFichierData').attr('value', nomFichier);
  if(extFichier == 'tiff'){
    extFichier = 'tif';
  }
  if(extFichier == 'jpeg'){
    extFichier = 'jpg';
  }
  $('#extensionFichierData').attr('value', extFichier);

  // 1. En fonction de l'extension du fichier on lit les données 
  // avec FileReader
  if(allowedImgType.indexOf(extFichier) != -1){
    reader.readAsDataURL(files[0]);
  }else if(allowedDicomType.indexOf(extFichier) != -1){
    reader.readAsDataURL(files[0], 'UTF-8');
    var imageDicom = cornerstoneWADOImageLoader.fileManager.add(this.files[0])
  }else if(allowedTableType.indexOf(extFichier) != -1 || allowedTextType.indexOf(extFichier) != -1){
    reader.readAsText(files[0], 'UTF-8');
  }else if(allowedEdfType.indexOf(extFichier) != -1){
    reader.readAsArrayBuffer(files[0]);
  }else{
    var response = {
      'erreurs': '<p>Sorry, this data cannot be read by NooLib. Please, verify the extension of your data.</p>'
    };
    
    displayInformationsClient(response);
    closeOverlayLocalData();
  }

  // 2. On contrôle la taille des données image - le contrôle sur les graphes se réalise lors
  // de la soumission de la matrice graphe
  if((allowedImgType.indexOf(extFichier) != -1 || allowedDicomType.indexOf(extFichier) != -1) && files[0].size > 6000000){
    uploadAllowed = false;
  }

  // 3. Une fois les données chargées, on les affiche
  // pour les traiter ensuite
  reader.onload = function(){
    // Si c'est une image
    if(allowedImgType.indexOf(extFichier) != -1){
    
      // Pour afficher sous forme d'image
      var image = new Image();
      image.src = this.result;
      overlayFormulaire.find('.modal-body .row:last').html(image);
      $('#localData').attr('value', this.result);
      $('#sampleRateDonnee').attr('value', 0);
      $('#tailleDonnee').attr('value', 0);

    // Si c'est un Dicom
    }else if(allowedDicomType.indexOf(extFichier) != -1){
      
      var element = overlayFormulaire.find('.modal-body .row:last').get(0),
          dataDicom = this.result;
      try {
          cornerstone.enable(element);
          cornerstone.loadImage(imageDicom).then(function(image) {
              
              var viewport = cornerstone.getDefaultViewportForImage(element, image);
              cornerstone.displayImage(element, image, viewport);
              //cornerstoneTools.mouseInput.enable(element);
              //cornerstoneTools.mouseWheelInput.enable(element);
              //cornerstoneTools.wwwc.activate(element, 1); // ww/wc is the default tool for left mouse button
              //cornerstoneTools.pan.activate(element, 2); // pan is the default tool for middle mouse button
              //cornerstoneTools.zoom.activate(element, 4); // zoom is the default tool for right mouse button
              //cornerstoneTools.zoomWheel.activate(element); // zoom is the default tool for middle mouse wheel
              
              $('#localData').attr('value', dataDicom);
              $('#sampleRateDonnee').attr('value', 0);
              $('#tailleDonnee').attr('value', 0);
              
          }, function(e) {
              var response = {
                  'erreurs': '<p>[JS] A system error has occured: '+e+'</p>'
              };

              displayInformationsClient(response);
              // On ferme la fenêtre
              overlayFormulaire.modal('hide');
          });
      }
      catch(e) {
          var response = {
              'erreurs': '<p>[JS] A system error has occured: '+e+'</p>'
          };

          displayInformationsClient(response);
          // On ferme la fenêtre
          overlayFormulaire.modal('hide');
      }

    // Si ce n'est pas une image, c'est-à-dire une table
    }else{

      // On affiche la fenêtre des données
      overlayFormulaire.find('.modal-body .row:first').removeClass('hidden');
      
      var num_points_display = 15000;

      // Si c'est un fichier CSV ou TXT
      if(allowedTableType.indexOf(extFichier) != -1 || allowedTextType.indexOf(extFichier) != -1){

        try{
          var txtReader = new TXTFile(this.result, extFichier);
          txtReader.get_view_signals_data(num_points_display);

          // On affiche le graphe des données locales
          graphLocalData(txtReader, num_points_display, '#overlayFormulaireAjoutDonneeLocale');

          // On affiche les données sous la forme d'une table
          tableData(txtReader, '#overlayFormulaireAjoutDonneeLocale');

        }
        catch(e){
            var response = {
                'erreurs': '<p>Sorry, this file does not contain any data for displaying.</p>'
            };

            displayInformationsClient(response);
            // On ferme la fenêtre
            closeOverlayLocalData();
        }
         
      }

      // Si c'est un fichier EDF
      if(allowedEdfType.indexOf(extFichier) != -1){
    
        try{
            
            var edfReader = new EDFFile(this.result);
            edfReader.get_view_signals_data(num_points_display);

            // On affiche le graphe des données locales
            graphLocalData(edfReader, num_points_display, '#overlayFormulaireAjoutDonneeLocale');
            //var t1 = new Date().getTime();
            //alert(t1-t0 + ' ms');

            // On affiche les données sous la forme d'une table
            tableData(edfReader, '#overlayFormulaireAjoutDonneeLocale');
        
        }
        catch(e){
            var response = {
                'erreurs': '<p>A system error has occured: '+e+'</p>'
            };

            displayInformationsClient(response);
            // On ferme la fenêtre
            overlayFormulaire.modal('hide');
        }
    
      }
    }

    // On cache l'indicateur de progression
    $('#image-local-waiter').hide();

    // On permet d'uploader la donnée
    $('#labelSubmitLocalData').attr('for', 'submitLocalData');
    $('#labelSubmitLocalData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Load this data');
    // On permet l'ajout d'une donnée
    $('#labelFileLocalData').attr('for', 'fileLocalData');
    $('#labelFileLocalData img').css('opacity', '1').css('cursor', 'pointer').attr('data-content', 'Add a new data');

  };
}