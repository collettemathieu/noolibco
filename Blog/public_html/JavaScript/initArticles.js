(function(){
    var txlist = document.getElementsByTagName("tex");
    for (var i = 0; i < txlist.length; i++) {
        var tx = txlist[i];
        var txtext = "\\displaystyle " + tx.textContent;
        var html = katex.renderToString(txtext, tx, { displayMode: true });
        html = "<div class='text-center mobile'>" + html 
                   + "<span class='pull-right'>(" + (i+1) + ")</span></div>";
        tx.innerHTML = html;
    }

    if(hljs != 'undefined'){
        hljs.initHighlightingOnLoad(); 
    }
})();