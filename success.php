<?php

echo "
<div class=\"notify\">" . $name . " sorted.</div>
<script type=\"text/javascript\">
window.setTimeout(function() {document.querySelector('.notify').style.display='none';},1000);
</script>
"
?>
