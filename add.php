<?php
date_default_timezone_set('America/New_York');

/* 	add:
*	- image (required)
*	- name (optional)
*/

echo
"
<!DOCTYPE html>
<html>
<head>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
    <title>Add thing</title>
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<script type=\"text/javascript\" src=\"scripts/resize.js\"></script>
</head>
<body>
<h1>Add thing</h1>
";

echo
"
<form action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\">
    <div>Select image to upload:</div>
    <div class=\"file\">
        <img id=\"preview\">
        <input type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\">
    </div>
    <script>
    document.getElementById('fileToUpload').onchange = function(evt) {
        console.log('resizing')
        ImageTools.resize(this.files[0], {
            width: 360, // maximum width
            height: 360 // maximum height
        }, function(blob, didItResize) {
            // didItResize will be true if it managed to resize it, otherwise false (and will return the original file as 'blob')
            document.getElementById('preview').src = window.URL.createObjectURL(blob);
            // you can also now upload this blob using an XHR.
        });
    };
    </script>
    <div class=\"name\"><input type=\"text\" name=\"name\" id=\"name\" value=\"image added " . date('Y-m-d \a\t H:i', time()) . "\"><label for=\"name\">Thing name (optional)</label></div>
    <div class=\"buttons\">
        <input type=\"submit\" value=\"Upload Image\" name=\"submit\">
        <input type=\"submit\" value=\"Cancel\" name=\"cancel\">
    </div>
</form>
";

echo
"
</body>
</html>
";

?>
