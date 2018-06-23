export default function displayInformationsClient(response){

    if(typeof(response) != 'undefined'){
        
        if(response['erreurs'] && response['erreurs'] != ''){
            
            $('#informationsClient').show().append('<div class="alert alert-danger alert-dismissable" style="display:none"><button type="button" class="close" data-dismiss="alert">x</button><h3>Attention</h3>'+response['erreurs']+'</div>');
            
            $('#informationsClient').find('.alert:last').fadeIn(function(){
                
                    $(this).delay(6000).fadeOut(800, function(){
                        $(this).remove();
                    });

            });
            
        }

        if(response['reussites'] && response['reussites'] != ''){
            $('#informationsClient').show().append('<div class="alert alert-success alert-dismissable style="display:none"><button type="button" class="close" data-dismiss="alert">x</button><h3>Information</h3>'+response['reussites']+'</div>');
            $('#informationsClient').find('.alert:last').fadeIn(function(){
                
                    $(this).delay(6000).fadeOut(function(){
                        $(this).remove();
                    });

            });
        }
    }else{

        if($('#informationExists').length != 0){
        
            $('#informationsClient').fadeIn(function(){
                
                $('#informationsClient').delay(6000).fadeOut(800, function(){
                    $('#informationsClient').html(''); // On réinitialise
                });

            });
            
        }
    }
}