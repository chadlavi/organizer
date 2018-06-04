<?php
date_default_timezone_set('America/New_York');
#header contents
include 'includes/connect.php';
include 'includes/notification.php';
#body contents
$sql = "select id, name, image, date_format(convert_tz(created, '+00:00', '-04:00'), '%Y-%m-%d %H:%i') as 'created' from things where ordered=1 order by created desc;";
#$sql = "SELECT image, id, name, created FROM things WHERE ordered=1 ORDER BY created desc;";
$result = $conn->query($sql);

echo
"<!DOCTYPE html>
<html>
	<head>
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
        <title>Sorted things (" . $result->num_rows . ")</title>
	</head>
	<body>
        <h1>Sorted Things (" . $result->num_rows . ")</h1>
        <div class=\"\">
            <p><a href=\"index.php\">Unsorted things</a></p>
        </div>
        <div class=\"things\">";
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo 
			"<div class=\"thing\">" .
			"<img src=\"" . $row["image"]. "\">" .
			"<div class=\"title\">" .
			"<div class=\"name\">" . $row["name"]. "</div>" . 
			"<div class=\"created\">Added " . $row["created"]. "</div>" .
			"</div>" .
			"<div class=\"buttons\">
            <form action=\"\" method=\"post\">
				<input type=\"hidden\" value=" . $row["id"]. " name=\"id\">
				<input type=\"hidden\" value=" . urlencode($row["name"]) . " name=\"name\">" .
  				"<input type=\"submit\" name=\"unsort\" value=\"Unsort\">
			</form>
            <form action=\"\" method=\"post\">
				<input type=\"hidden\" value=" . $row["id"]. " name=\"id\">
				<input type=\"hidden\" value=" . $row["image"]. " name=\"image\">
				<input type=\"hidden\" value=" . urlencode($row["name"]) . " name=\"name\">" .
  				"<input type=\"submit\" name=\"delete\" value=\"Delete\">
			</form>
			</div>" .
			"</div>";
    }
} else {
    echo "0 results";
}

echo
"
</div>
";

# end of body
if(isset($_POST['unsort'])) {
	$conn->query("UPDATE things SET ordered = 0 where id=" . $_POST["id"]);
    notify("\"" . urldecode($_POST["name"]) . "\" unsorted.", "unsort");
	header("location: {$_SERVER['PHP_SELF']}");
    exit;
};
if(isset($_POST['delete'])) {
	$conn->query("DELETE from things where id=" . $_POST["id"]);
    $image = $_POST["image"];
    shell_exec("rm $image");
    notify("\"" . urldecode($_POST["name"]) . "\" deleted.", "delete");
	header("location: {$_SERVER['PHP_SELF']}");
    exit;
};
echo
"</body>
</html>";

?>
