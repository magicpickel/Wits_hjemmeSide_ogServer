<?php
session_start();
if (isset($_POST['newOffset'])) {
    $_SESSION['offset'] = $_POST['newOffset'];
}
?>