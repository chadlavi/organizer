<?php
date_default_timezone_set('America/New_York');
#header contents
$page = "index";
include 'includes/notification.php';

#body contents
$sql = "select id, name, image, date_format(convert_tz(created, '+00:00', '-04:00'), '%Y-%m-%d %H:%i') as 'created', date_format(adddate(adddate(convert_tz(created, '+00:00', '-04:00'), interval 3 hour), interval snooze day), '%Y-%m-%d %H:%i') as 'due', snooze, date_format(convert_tz(CURRENT_TIMESTAMP, '+00:00', '-04:00'), '%Y-%m-%d %H:%i') as 'now' from things where ordered=0 order by due asc;";
echo
"<!DOCTYPE html>
<html>
	<head>
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
        <title>Unsorted</title>
	</head>
	<body>
        <div class=\"things\">";
include "includes/nav.php";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo 
			"<div class=\"thing" .($row["due"] < $row["now"] ? " late" : "") . "\">" .
			"<img src=\"" . $row["image"]. "\">" .
			"<div class=\"title\">" .
			"<div class=\"name\">" . $row["name"]. "</div>" . 
			"<div class=\"created\">Added " . $row["created"]. "</div>" .
			"<div class=\"due\">Due " . $row["due"] . "</div>" .
			($row["snooze"]>0 ? "<div class=\"snooze\">Snoozed ".$row["snooze"]." days</div>" : "") .
			"</div>" .
			"<div class=\"buttons\">
            <form action=\"\" method=\"post\">
				<input type=\"hidden\" value=" . $row["id"]. " name=\"id\">
				<input type=\"hidden\" value=" . urlencode($row["name"]) . " name=\"name\">" .
  				"<input type=\"submit\" name=\"sort\" value=\"Sort\">
			</form>
            <form action=\"\" method=\"post\">
				<input type=\"hidden\" value=" . $row["id"]. " name=\"id\">
				<input type=\"hidden\" value=" . urlencode($row["name"]) . " name=\"name\">" .
  				"<input type=\"submit\" name=\"snooze\" value=\"Snooze\">
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
    echo 
    "
    <div class=\"empty\">
        <p>Nothing unsorted.</p>
        <p><a href=\"add.php\">Add</a></p>
    </div>
    ";
}

echo
"
</div>
";

# end of body
if(isset($_POST['sort'])) {
	$conn->query("UPDATE things SET ordered = 1 where id=" . $_POST["id"]);
    notify("\"" . urldecode($_POST["name"]) . "\" sorted.", "sort");
	header("location: {$_SERVER['PHP_SELF']}");
    exit;
};
if(isset($_POST['snooze'])) {
	$conn->query("UPDATE things SET snooze = snooze + 1 where id=" . $_POST["id"]);
    notify("\"" . urldecode($_POST["name"]) . "\" snoozed.", "snooze");
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
