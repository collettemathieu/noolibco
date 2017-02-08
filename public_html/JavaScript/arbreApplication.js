(function(){
    if($('#containerTreeApplication').length != 0){
        // Pour modifier le logo de l'application
        $('#formChangePictureApplication').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData(e.target),
                btn = $(this).find('button');
            btn.button('loading');
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: '/HandleApplication/ChangePictureApplication',
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    btn.button('reset');
                    response = JSON.parse(response);
                    displayInformationsClient(response);

                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                },
                error: function(){
                    btn.button('reset');
                    var response = {
                        'erreurs': '<p>A system error has occurred.</p>'
                    };
                    displayInformationsClient(response);
                }
            });
        });

        // Pour modifier le nom de l'application
        $('#formChangeNameApplication').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData(e.target),
                btn = $(this).find('button');
            btn.button('loading');
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: '/HandleApplication/ChangeNameApplication',
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    btn.button('reset');
                    response = JSON.parse(response);
                    displayInformationsClient(response);

                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                },
                error: function(){
                    btn.button('reset');
                    var response = {
                        'erreurs': '<p>A system error has occurred.</p>'
                    };
                    displayInformationsClient(response);
                }
            });
        });

        // Pour modifier la description (et +) de l'application
        $('#formDescriptionApplication').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData(e.target),
                This = $(this),
                btn = $(this).find('button');
            btn.button('loading');
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: '/HandleApplication/ChangeDescriptionApplication',
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    btn.button('reset');
                    response = JSON.parse(response);
                    displayInformationsClient(response);
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                },
                error: function(){
                    btn.button('reset');
                    var response = {
                        'erreurs': '<p>A system error has occurred.</p>'
                    };
                    displayInformationsClient(response);
                }
            });
        });

        // Pour ajouter une publication
        $('#addPublicationForm').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData(e.target),
                This = $(this),
                btn = $(this).find('button');
            btn.button('loading');
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: '/HandleApplication/AddPublication',
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    btn.button('reset');
                    response = JSON.parse(response);
                    displayInformationsClient(response);

                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                },
                error: function(){
                    btn.button('reset');
                    var response = {
                        'erreurs': '<p>A system error has occurred.</p>'
                    };
                    displayInformationsClient(response);
                }
            });
        });

        // Permet d'effectuer une requête vers CrossRef
        $('#searchPublicationForm').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData(e.target),
                btn = $(this).find('button');
            btn.button('loading');
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: '/HandleApplication/RequestPublication',
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    btn.button('reset');
                    try{
                        response = JSON.parse(response);
                        if(response['erreurs']){
                            displayInformationsClient(response);
                        }else if(response['reussites']){
                            var results = response['reussites'],
                                inputFormPublication = $('#addPublicationForm').find('input');
                            // On change d'onglet
                            $('#addPublication').find('.nav-pills a[href="#manuel"]').tab('show');
                            
                            // On remplit le tableau du formulaire des publications
                            $('#addPublicationForm').find('select option:eq(1)').attr('selected', 'selected'); // On sélectionne article de journal
                            inputFormPublication[0].value = results['titleArticle'];
                            inputFormPublication[1].value = results['listeAuteurs'];
                            inputFormPublication[2].value = results['yearPublication'];
                            inputFormPublication[3].value = results['titleJournal'];
                            inputFormPublication[4].value = results['urlRessource'];
                        }
                    }
                    catch(e){
                        var response = {
                            'erreurs': '<p>A system error has occurred.</p>'
                        };
                        displayInformationsClient(response);
                    }
                },
                error: function(){
                    btn.button('reset');
                    var response = {
                        'erreurs': '<p>A system error has occurred.</p>'
                    };
                    displayInformationsClient(response);
                    $('#searchPublicationForm').modal('hide');
                }
            });
        });


        // Pour supprimer une publication
        $('.formDeletePublication').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData(e.target),
                This = $(this),
                btn = $(this).find('button');
            btn.button('loading');
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: '/HandleApplication/DeletePublication',
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    btn.button('reset');
                    response = JSON.parse(response);
                    displayInformationsClient(response);

                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                },
                error: function(){
                    btn.button('reset');
                    var response = {
                        'erreurs': '<p>A system error has occurred.</p>'
                    };
                    displayInformationsClient(response);
                }
            });
        });

        // Pour ajouter un auteur à l'application
        $('#formAuteursApplication').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData(e.target),
                This = $(this),
                btn = $(this).find('button');
            btn.button('loading');
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: '/HandleApplication/AddAuthor',
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    btn.button('reset');
                    response = JSON.parse(response);
                    displayInformationsClient(response);

                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                },
                error: function(){
                    btn.button('reset');
                    var response = {
                        'erreurs': '<p>A system error has occurred.</p>'
                    };
                    displayInformationsClient(response);
                }
            });
        });


        // Pour supprimer un auteur de l'application
        $('.removeAuthor').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData(e.target),
                This = $(this),
                btn = $(this).find('button');
            btn.button('loading');
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: '/HandleApplication/RemoveAuthor',
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    btn.button('reset');
                    response = JSON.parse(response);
                    displayInformationsClient(response);

                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                },
                error: function(){
                    btn.button('reset');
                    var response = {
                        'erreurs': '<p>A system error has occurred.</p>'
                    };
                    displayInformationsClient(response);
                }
            });
        });
    


        // Pour supprimer l'application
        $('#formDeleteApplication').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData(e.target),
                btn = $(this).find('button');
            btn.button('loading');
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: '/HandleApplication/Delete',
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    btn.button('reset');
                    response = JSON.parse(response);
                    displayInformationsClient(response);

                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                },
                error: function(){
                    btn.button('reset');
                    var response = {
                        'erreurs': '<p>A system error has occurred.</p>'
                    };
                    displayInformationsClient(response);
                }
            });
        });


    	// Pour ajouter une tâche
    	$('#boutonAjouterTache').click(function(){
    		// Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: '/HandleApplication/FormTache',
                type:'POST',
                data:{
                    idApp:parseInt($('#containerTreeApplication').attr('idApp'))
                },
                async: true,
                cache: true,
                success: function(response) {
                	
    				$('#contenuForm').html(response);

    				$('form').on('submit', function(e){
    					e.preventDefault();
    					var formData = new FormData(e.target);
    					$(this).find('button').button('loading');
    					validerFormulaireApplicationByAjax(formData, '/HandleApplication/ValidFormTache');
    				});

                    manageTypeDonneeUtilisateur();
                    initTypeDonneeUtilisateur();
                },
                error: function(){
                	var response = {
                		'erreurs': '<p>A system error has occurred.</p>'
                	};
    	            displayInformationsClient(response);
                }
            });
    	});


        // Pour créer une nouvelle version
        $('#formCreateNewVersion').on('submit', function(e){
            e.preventDefault();
            var formData = new FormData(e.target),
                btn = $(this).find('button');
            btn.button('loading');
            // Envoi de la requête HTTP en mode asynchrone
            $.ajax({
                url: '/HandleApplication/CreateNewVersionApplication',
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    btn.button('reset');
                    response = JSON.parse(response);
                    setTimeout(function(){
                            location.reload();
                    }, 1000);
                    displayInformationsClient(response);
                },
                error: function(){
                    btn.button('reset');
                    var response = {
                        'erreurs': '<p>A system error has occurred.</p>'
                    };
                    displayInformationsClient(response);
                }
            });
        });


        // Pour initialiser les nouveaux types de données utilisateurs
        function initTypeDonneeUtilisateur(){
            $('.selectUniteDonneeUtilisateur').each(function(){
                if($(this).parent().find('select:first').val() != 'Input Text'){
                    $(this).attr('disabled', 'disabled');
                }
            });

            if($('.selectTypeDonneeUtilisateur').length > 1){
                $('#deleteTypeDonneeUtilisateur').removeAttr('disabled');
            }

            $('.selectTypeDonneeUtilisateur').change(function(){
                if($(this).val() != 'Input Text'){
                    $(this).parent().find('select:last').attr('disabled', 'disabled');
                }else{
                    $(this).parent().find('select:last').removeAttr('disabled');
                }
            });
        }

        // Pour ajouter un nouveau type de donnée utilisateur à une tâche ou en supprimer un
        function manageTypeDonneeUtilisateur(){
            $('#addNewTypeDonneeUtilisateur').click(function(e){
                e.preventDefault();
                var divNode = $(this).parent().find('div:last'),
                    num_data = $(this).parent().find('div').length,
                    divClone = divNode.clone();
                divClone.find('select:first').attr('name', 'typeDonneeUtilisateur'+num_data).val('');
                divClone.find('select:last').attr('name', 'uniteDonneeUtilisateur'+num_data).val('').removeAttr('disabled');
                divClone.find('input').attr('name', 'description'+num_data).val('');
                divClone.insertAfter(divNode);
                if(num_data == 1){
                    $('#deleteTypeDonneeUtilisateur').removeAttr('disabled');
                }
                initTypeDonneeUtilisateur();
            });

            // Pour supprimer un type de paramètre à la tâche - le dernier n'est pas supprimé
            $('#deleteTypeDonneeUtilisateur').click(function(e){
                e.preventDefault();
                var divNode = $(this).parent().find('div');
                if(divNode.length > 1){
                    divNode[divNode.length-1].remove();
                    if(divNode.length == 2){
                        $(this).attr('disabled', 'disabled');
                    }
                }
            });
        }


        /* Création de l'arbre de l'application */
        // Envoi de la requête HTTP en mode asynchrone pour récupérer les data de l'application
        function initTreeApplication(idVersion){
            $.ajax({
                url: '/HandleApplication/DataApplication',
                type:'POST',
                data:{
                    idApp:parseInt($('#containerTreeApplication').attr('idApp')),
                    idVersion:parseInt(idVersion)
                },
                async: true,
                cache: true,
                success: function(response) {

                    var response = JSON.parse(response);

                    if(response['erreurs'] != 'undefined'){
                        displayInformationsClient(response);
                    }

                    var task = response['task'],
                        data = response['data'],
                        tasksData = [],
                        fonctionsData = [],
                        parametersData = [],
                        i,
                        j,
                        k,
                        dataLen = data.length,
                        drillDataLen,
                        drillParameterLen,
                        brightness,
                        colors = Highcharts.getOptions().colors;

                    
                        
                    // Build the data arrays
                    for (i = 0; i < dataLen; ++i) {

                        // add task data
                        tasksData.push({
                            name: task[i],
                            y: data[i].y,
                            id: data[i].id,
                            color: colors[i+5]
                        });

                        // add function data

                        drillDataLen = data[i].drilldown.data.length;
                        for (j = 0; j < drillDataLen; ++j) {
                            brightness = 0.2 - (j / drillDataLen) / 5;
                            fonctionsData.push({
                                name: data[i].drilldown.fonction[j],
                                y: data[i].drilldown.data[j],
                                id: data[i].drilldown.id[j],
                                tacheId: data[i].drilldown.tacheID,
                                color: Highcharts.Color(colors[i+5]).brighten(brightness).get()
                            });


                            // add parameter data

                            drillParameterLen = data[i].drilldown.drillparameter[j].data.length;
                            
                            for (k = 0; k < drillParameterLen; ++k) {
                                brightness = 0.1 - (k / drillParameterLen) / 5;
                                parametersData.push({
                                    name: data[i].drilldown.drillparameter[j].parameter[k],
                                    y: data[i].drilldown.drillparameter[j].data[k],
                                    id: data[i].drilldown.drillparameter[j].id[k],
                                    fonctionId: data[i].drilldown.drillparameter[j].fonctionID,
                                    color: Highcharts.Color(colors[i+5]).brighten(brightness).get()
                                });
                            }
                        }
                    }

                    // Create the chart
                    $('#containerTreeApplication').highcharts({
                        chart: {
                            type: 'pie',
                            backgroundColor: 'transparent'
                        },
                        credits: {
                          enabled: false
                        },
                        title:{
                            text:''
                        },
                        exporting: { 
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                shadow: false,
                                center: ['50%', '50%']
                            },
                            series: {
                                cursor: 'pointer'
                            }
                        },
                        tooltip: {
                            shared: true,
                            useHTML: true,
                            headerFormat: '<small>{point.key}</small><table>',
                            pointFormat: '',
                            footerFormat: '</table>',
                            valueDecimals: 2
                        },
                        series: [{
                            name: 'Tasks',
                            data: tasksData,
                            size: '100%',
                            innerSize: '5%',
                            dataLabels: {
                                formatter: function () {
                                    return this.point.name;
                                },
                                color: 'white',
                                distance: -140
                            },
                            point:{
                                events:{
                                    click: function (event) {

                                        // Envoi de la requête HTTP en mode asynchrone
                                        $.ajax({
                                            url: '/HandleApplication/ModifTache',
                                            type: 'POST',
                                            data:{
                                                idVersion:parseInt($('#selectVersion').val()),
                                                idApp:parseInt($('#containerTreeApplication').attr('idApp')),
                                                idTache:this.id
                                            },
                                            async: true,
                                            cache: true,
                                            success: function(response) {

                                                $('#contenuForm').html(response);
                                                $('#formulaireApplication').modal();
                                                manageTypeDonneeUtilisateur();
                                                initTypeDonneeUtilisateur();
                                                
                                                // Pour modifier la tâche
                                                $('#formulaireApplication form:first').on('submit', function(e){
                                                
                                                    e.preventDefault();
                                                    var formData = new FormData(e.target);
                                                    $(this).find('button').button('loading');
                                                    validerFormulaireApplicationByAjax(formData, '/HandleApplication/ValidModifTache');
                                                });

                                                // Pour supprimer la tâche
                                                $('#formulaireApplication form:last').on('submit', function(e){
                                                    e.preventDefault();
                                                    var formData = new FormData(e.target),
                                                        btn = $(this).find('button');
                                                    btn.button('loading');
                                                    formData.append('idApp', parseInt($('#containerTreeApplication').attr('idApp')));
                                                    formData.append('idVersion', parseInt($('#selectVersion').val()));
                                                    // Envoi de la requête HTTP en mode asynchrone
                                                    $.ajax({
                                                        url: '/HandleApplication/DeleteTache',
                                                        type: 'POST',
                                                        data: formData,
                                                        async: true,
                                                        cache: false,
                                                        contentType: false,
                                                        processData: false,
                                                        success: function(response) {
                                                            btn.button('reset');
                                                            var response = JSON.parse(response);
                                                            
                                                            if(response['reussites']){
                                                                setTimeout(function(){
                                                                        location.reload();
                                                                }, 1000);
                                                            }

                                                            displayInformationsClient(response);
                                                        },
                                                        error: function(){
                                                            btn.button('reset');
                                                            var response = {
                                                                'erreurs': '<p>A system error has occurred.</p>'
                                                            };
                                                            displayInformationsClient(response);
                                                        }
                                                    });
                                                });

                                            },
                                            error: function(){
                                                var response = {
                                                    'erreurs': '<p>A system error has occurred.</p>'
                                                };
                                                displayInformationsClient(response);
                                            }
                                        });
                                    }
                                }
                            } 
                        }, {
                            name: 'Functions',
                            data: fonctionsData,
                            size: '100%',
                            innerSize: '40%',
                            dataLabels: {
                                formatter: function () {
                                    // display only if larger than 1
                                    return this.point.name;
                                },
                                distance: -60
                            },
                            point:{
                                events:{
                                    click: function (event) {
                                        if(this.id != -1){
                                            // Envoi de la requête HTTP en mode asynchrone
                                            $.ajax({
                                                url: '/HandleApplication/ModifFonction',
                                                type: 'POST',
                                                data:{
                                                    idVersion:parseInt($('#selectVersion').val()),
                                                    idApp:parseInt($('#containerTreeApplication').attr('idApp')),
                                                    idFonction:this.id
                                                },
                                                async: true,
                                                cache: true,
                                                success: function(response) {
                                                    $('#contenuForm').html(response);
                                                    $('#formulaireApplication').modal();

                                                    // Pour modifier la fonction
                                                    addFunctionToTask('/HandleApplication/ValidModifFonction');

                                                    // Pour supprimer la fonction
                                                    $('#formulaireApplication form:last').on('submit', function(e){
                                                        e.preventDefault();
                                                        var formData = new FormData(e.target),
                                                            btn = $(this).find('button');
                                                        btn.button('loading');
                                                        formData.append('idApp', parseInt($('#containerTreeApplication').attr('idApp')));
                                                        formData.append('idVersion', parseInt($('#selectVersion').val()));
                                                        // Envoi de la requête HTTP en mode asynchrone
                                                        $.ajax({
                                                            url: '/HandleApplication/DeleteFonction',
                                                            type: 'POST',
                                                            data: formData,
                                                            cache: false,
                                                            contentType: false,
                                                            processData: false,
                                                            success: function(response) {
                                                                btn.button('reset');
                                                                var response = JSON.parse(response);
                                                                
                                                                if(response['reussites']){
                                                                    setTimeout(function(){
                                                                            location.reload();
                                                                    }, 1000);
                                                                }

                                                                displayInformationsClient(response);
                                                            },
                                                            error: function(){
                                                                btn.button('reset');
                                                                var response = {
                                                                    'erreurs': '<p>A system error has occurred.</p>'
                                                                };
                                                                displayInformationsClient(response);
                                                            }
                                                        });
                                                    });
                                                        
                                                },
                                                error: function(){
                                                    var response = {
                                                        'erreurs': '<p>A system error has occurred.</p>'
                                                    };
                                                    displayInformationsClient(response);
                                                }
                                            });
                                        }else{
                                           
                                            // Envoi de la requête HTTP en mode asynchrone
                                            $.ajax({
                                                url: '/HandleApplication/FormFonction',
                                                type: 'POST',
                                                data:{
                                                    idVersion:parseInt($('#selectVersion').val()),
                                                    idApp:parseInt($('#containerTreeApplication').attr('idApp')),
                                                    idTache:this.tacheId
                                                },
                                                async: true,
                                                cache: true,
                                                success: function(response) {
                                                    $('#contenuForm').html(response);
                                                    $('#formulaireApplication').modal();

                                                    addFunctionToTask('/HandleApplication/ValidFormFonction');
                                                },
                                                error: function(){
                                                    var response = {
                                                        'erreurs': '<p>A system error has occurred.</p>'
                                                    };
                                                    displayInformationsClient(response);
                                                }
                                            });
                                        }
                                    }
                                }
                            } 
                        },
                        {
                            name: 'Parameters',
                            data: parametersData,
                            size: '100%',
                            innerSize: '100%',
                            dataLabels: {
                                formatter: function () {
                                    return this.point.name;
                                },
                            },
                            point:{
                                events:{
                                    click: function (event) {
                                        if(this.id != -1){
                                            // Envoi de la requête HTTP en mode asynchrone
                                            $.ajax({
                                                url: '/HandleApplication/ModifParametre',
                                                type: 'POST',
                                                data:{
                                                    idVersion:parseInt($('#selectVersion').val()),
                                                    idApp:parseInt($('#containerTreeApplication').attr('idApp')),
                                                    idParametre:this.id
                                                },
                                                async: true,
                                                cache: true,
                                                success: function(response) {
                                                    $('#contenuForm').html(response);
                                                    $('#formulaireApplication').modal();
                                                    
                                                    // Pour modifier le paramètre
                                                    $('#formulaireApplication form:first').on('submit', function(e){
                                                    
                                                        e.preventDefault();
                                                        var formData = new FormData(e.target),
                                                            btn = $(this).find('button');
                                                        btn.button('loading');
                                                        validerFormulaireApplicationByAjax(formData, '/HandleApplication/ValidModifParametre');
                                                        
                                                    });

                                                    // Pour supprimer le paramètre
                                                    $('#formulaireApplication form:last').on('submit', function(e){
                                                        e.preventDefault();
                                                        var formData = new FormData(e.target),
                                                            btn = $(this).find('button');
                                                        btn.button('loading');
                                                        formData.append('idApp', parseInt($('#containerTreeApplication').attr('idApp')));
                                                        formData.append('idVersion', parseInt($('#selectVersion').val()));
                                                        
                                                        // Envoi de la requête HTTP en mode asynchrone
                                                        $.ajax({
                                                            url: '/HandleApplication/DeleteParametre',
                                                            type: 'POST',
                                                            data: formData,
                                                            cache: false,
                                                            contentType: false,
                                                            processData: false,
                                                            success: function(response) {
                                                                btn.button('reset');
                                                                var response = JSON.parse(response);
                                                                
                                                                if(response['reussites']){
                                                                    setTimeout(function(){
                                                                            location.reload();
                                                                    }, 1000);
                                                                }

                                                                displayInformationsClient(response);
                                                            },
                                                            error: function(){
                                                                btn.button('reset');
                                                                var response = {
                                                                    'erreurs': '<p>A system error has occurred.</p>'
                                                                };
                                                                displayInformationsClient(response);
                                                            }
                                                        });
                                                    });

                                                },
                                                error: function(){
                                                    var response = {
                                                        'erreurs': '<p>A system error has occurred.</p>'
                                                    };
                                                    displayInformationsClient(response);
                                                }
                                            });
                                        }else{
                                            
                                            // Envoi de la requête HTTP en mode asynchrone
                                            $.ajax({
                                                url: '/HandleApplication/FormParametre',
                                                type: 'POST',
                                                data:{
                                                    idVersion:parseInt($('#selectVersion').val()),
                                                    idApp:parseInt($('#containerTreeApplication').attr('idApp')),
                                                    idFonction:this.fonctionId
                                                },
                                                async: true,
                                                cache: true,
                                                success: function(response) {
                                                    
                                                    $('#contenuForm').html(response);
                                                    $('#formulaireApplication').modal();

                                                    $('#formulaireApplication form').on('submit', function(e){
                                                    
                                                        e.preventDefault();
                                                        var formData = new FormData(e.target),
                                                            btn = $(this).find('button');
                                                        btn.button('loading');
                                                        validerFormulaireApplicationByAjax(formData, '/HandleApplication/ValidFormParametre');
                                                        
                                                    });
                                                    
                                                },
                                                error: function(){
                                                    var response = {
                                                        'erreurs': '<p>A system error has occurred.</p>'
                                                    };
                                                    displayInformationsClient(response);
                                                }
                                            });
                                        }
                                    }
                                }
                            } 
                        }]
                    });
                    
                },
                error: function(){
                    var response = {
                        'erreurs': '<p>A system error has occurred.</p>'
                    };
                    displayInformationsClient(response);
                }
            });
        }
        initTreeApplication(); // initialisation au 1er chargement
        // Pour sélectionner une autre version de l'application
        $('#selectVersion').on('change', function(e){
            // On met à jour l'arbre de l'application
            initTreeApplication(e.target.value);
            // On met à jour l'url de la requête NooSpace
            $('#testInNooSpace').attr('href', '/NooSpace/a='+parseInt($('#containerTreeApplication').attr('idApp'))+'v='+parseInt($('#selectVersion').val()));
             // On met à jour l'url de la requête Version
            $('#validVersionApplication').attr('href', '/PourAdminSeulement/Applications/ActivateVersion/a='+parseInt($('#containerTreeApplication').attr('idApp'))+'v='+parseInt($('#selectVersion').val()));
        });

    }
})();


   