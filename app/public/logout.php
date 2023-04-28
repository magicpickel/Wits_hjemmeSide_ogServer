<?php
/* 
1. session_start() starts the session

2. unset($_SESSION['login']) unsets the session value, and all other session values that are set

3. header("Location: index.php") redirects the user to the index page

4. exit() stops the execution of the script */

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