<?php
include "includes/notification.php";
$page = "add";
date_default_timezone_set('America/New_York');

echo
"
<!DOCTYPE html>
<html>
<head>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
    <title>Add something</title>
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<script type=\"text/javascript\" src=\"scripts/resize.js\"></script>
<script type=\"text/javascript\" src=\"scripts/exif.js\"></script>
</head>
<body>";
include 'includes/nav.php';
echo
"
<form id=\"uploadform\" action=\"\" method=\"post\" enctype=\"multipart/form-data\">
    <div>Browse, drag and drop, or take image</div>
    <div class=\"file\">
        <img name=\"preview\" id=\"preview\">
        <input type=\"file\"  id=\"fileToUpload\">
        <input type=\"hidden\" name=\"image\" id=\"image\">
        <input type=\"hidden\" name=\"rotation\" id=\"rotation\">
    </div>
    <label for=\"image\">Image</label>
    <script>
    var uploadfile = document.getElementById('fileToUpload');
    uploadfile.onchange = function(evt) {
        console.log('resizing')
        ImageTools.resize(this.files[0], {
            width: 360, // maximum width
            height: 360 // maximum height
        }, function(blob, didItResize) {
            // didItResize will be true if it managed to resize it, otherwise false (and will return the original file as 'blob')
            document.getElementById('preview').src = window.URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob); 
            reader.onloadend = function() {
                base64data = reader.result;                
                document.getElementById('image').value = base64data;
            }
        });
        getOrientation(uploadfile.files[0], function(orientation) {
            console.log('orientation: ' + orientation);
            document.getElementById('rotation').value = orientation;
        });
    }
    </script>
    <div class=\"name\"><input type=\"text\" name=\"name\" id=\"name\" value=\"image added " . date('Y-m-d \a\t H:i', time()) . "\"><label for=\"name\">Name</label></div>
    <div class=\"buttons\">
        <input type=\"submit\" value=\"Add\" name=\"submit\">
        <input type=\"submit\" value=\"Cancel\" name=\"cancel\">
    </div>
</form>
</body>
</html>
";

if(isset($_POST["cancel"])) {
    header("location: /");
    exit;
}
if(isset($_POST["submit"])) {
    $target_dir = "images/";
    $target_file = $target_dir . uniqid();
    
    
    $image = $_POST["image"];
    $image = base64_decode(preg_replace('#^data:image/[^;]+;base64,#', '', $image));
    if (file_put_contents($target_file, $image)) {
    #if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $ort = $_POST['rotation'];
        $img = imagecreatefromjpeg($target_file);
        if (!empty($ort)){
            switch ($ort) {
                case 3:
                    $img = imagerotate($img, 180, 0);
                    break;
                case 6:
                    $img = imagerotate($img, -90, 0);
                    break;
                case 8:
                    $img = imagerotate($img, 90, 0);
                    break;
            }
        }
        imagejpeg($img, $target_file, 90);
        shell_exec("mogrify -resize 360x360 -quality 50% -strip $target_file");
        include 'includes/connect.php';
        $conn->query("INSERT INTO things (name, image) VALUES (\"" . $_POST["name"] . "\",\"" . $target_file . "\")");
        header("location: /");
    } else {
        notify("Sorry, there was an error uploading the file.", "delete");
    }
    exit;
}
?>
