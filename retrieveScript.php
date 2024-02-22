<?php
// retrieveScript.php

function getLoggedInUserName() {
    session_start();
    if (isset($_SESSION['username'])) {
        return $_SESSION['username'];
    } else {
        return NULL;
    }
}
?>
