<?php
include "connect.php";
/*
// Create connection
$conn2 = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn2->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
*/

$result = $conn->query("select (select count(id) from things where ordered=0) as 'unordered_count', (select count(id) from things where ordered=1) as 'ordered_count';");

#$conn->close();

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        //do thing
        echo
        "
        <div id=\"nav\">
            <div><a class=\"" . ($page == "index" ? "active" : "") . "\" href=\"index.php\">Unsorted (" . $row["unordered_count"] . ")</a></div>
            <div class=\"divider\"> / </div>
            <div><a class=\"" . ($page == "sorted" ? "active" : "") . "\" href=\"sorted.php\">Sorted (" . $row["ordered_count"] . ")</a></div>
            <div class=\"divider\"> / </div>
            <div><a class=\"" . ($page == "add" ? "active" : "") . "\" href=\"add.php\">Add</a></div>
        </div>
        ";
    }
} else {
    //something else
    echo "whoopsies";
};
?>
