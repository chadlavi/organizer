<?php

session_start();
function notification() {
    if(isset($_SESSION['notify'])) {
        echo $_SESSION['notify']['message'];
        echo "<script type=\"text/javascript\"> window.setTimeout(function() {document.querySelector('.notify').style.display='none';},3000); </script>";
        unset($_SESSION['notify']);
    };
};

function notify($message, $class) {
    $_SESSION['notify']['message'] = "<div class=\"notify " . ($class ?: '') . "\">" . $message . "</div>";
};

notification();
?>
