<?php
session_start();
unset($_SESSION['login']);
unset($_SESSION['user']);
unset($_SESSION['editPost']);
unset($_SESSION['pid']);
unset($_SESSION['titleT']);
unset($_SESSION['contentT']);
unset($_SESSION['imgUrlT']);
unset($_SESSION['iidsTp']);
unset($_SESSION['iidsT']);
unset($_SESSION['offset']);
header("Location: index.php");
exit();