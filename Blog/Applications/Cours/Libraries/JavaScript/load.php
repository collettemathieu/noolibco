<script type="text/javascript" src="/JavaScript/initCours.js"></script>
<script type="text/javascript" src="/JavaScript/managerCommentaires.js"></script>
<script type="text/javascript" src="/JavaScript/Frameworks/katex.min.js"></script>
<script type="text/javascript" src="/JavaScript/Frameworks/katexAutoRender.min.js"></script>
<script> 
var txlist = document.getElementsByTagName("tex");
    for (var i = 0; i < txlist.length; i++) {
        var tx = txlist[i];
        var txtext = "\\displaystyle " + tx.textContent;
        var html = katex.renderToString(txtext, tx, { displayMode: true });
        html = "<div class='text-center mobile'>" + html 
                   + "<span class='pull-right'>(" + (i+1) + ")</span></div>";
        tx.innerHTML = html;
    }
</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
<script type="text/javascript">hljs.initHighlightingOnLoad();</script>