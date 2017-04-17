function base64toBlob(base64Data, contentType){

    contentType = contentType || '';
    var sliceSize = 1024;
    var byteCharacters = atob(base64Data);
    var bytesLength = byteCharacters.length;
    var slicesCount = Math.ceil(bytesLength / sliceSize);
    var byteArrays = new Array(slicesCount);

    for (var sliceIndex = 0; sliceIndex < slicesCount; ++sliceIndex) {
       
        var begin = sliceIndex * sliceSize;
        var end = Math.min(begin + sliceSize, bytesLength);

        var bytes = new Array(end - begin);
        for (var offset = begin, i = 0 ; offset < end; ++i, ++offset) {
            bytes[i] = byteCharacters[offset].charCodeAt(0);
        }
        byteArrays[sliceIndex] = new Uint8Array(bytes);
    }

    return new Blob(byteArrays, { type: contentType });
    
}

// Pour gérer l'affichage des slider dans le panneau des paramètres de l'application
function sliderParametreApplication(cloneContenuPanel){

    cloneContenuPanel.find('.sliderParametreApplication').each(function(){
       
        var valeurDefautParametre = parseInt($(this).parent().find('.valeurDefautParametre').attr('value')),
            valeurMinParametre = parseInt($(this).parent().find('.valeurMinParametre').attr('value')),
            valeurMaxParametre = parseInt($(this).parent().find('.valeurMaxParametre').attr('value')),
            valeurPasParametre = parseInt($(this).parent().find('.valeurPasParametre').attr('value'));
        
        $(this).slider({
            range: 'min',
            min: valeurMinParametre,
            max: valeurMaxParametre,
            value: valeurDefautParametre,
            step: valeurPasParametre,
            slide: function( event, ui ) {
                $(this).parent().find('.valeurDefautParametre').attr('value', ui.value);
            }
        });
    });    
}
