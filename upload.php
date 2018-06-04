<?php
date_default_timezone_set('America/New_York');
if(isset($_POST["cancel"])) {
    header("location: /");
    exit;
}
$target_dir = "images/";
$target_file = $target_dir . uniqid();
echo "target file is " . $target_file;
#$target_file = $target_dir . basename($_FILES["fileToUpload"]["tmp_name"]) . time();

if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    $exif = exif_read_data($target_file);
    $ort = $exif['Orientation']; /*STORES ORIENTATION FROM IMAGE */
    $ort1 = $ort;
    $exif = exif_read_data($target_file, 0, true);
    if (!empty($ort1)){
        $image = imagecreatefromjpeg($target_file);
        $ort = $ort1;
        switch ($ort) {
            case 3:
                $image = imagerotate($image, 180, 0);
                break;
            case 6:
                $image = imagerotate($image, -90, 0);
                break;
            case 8:
                $image = imagerotate($image, 90, 0);
                break;
        }
    }
    imagejpeg($image,$target_file, 90); /*IF FOUND ORIENTATION THEN ROTATE IMAGE IN PERFECT DIMENSION*/
    shell_exec("mogrify -resize 360x360 -quality 50% -strip $target_file");
    include 'includes/connect.php';
    $conn->query("INSERT INTO things (name, image) VALUES (\"" . $_POST["name"] . "\",\"" . $target_file . "\")");
    header("location: /");
} else {
    echo "Sorry, there was an error uploading your file.";
}
?>

