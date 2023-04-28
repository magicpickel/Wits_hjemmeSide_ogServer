<?php
/* 
1. We start the session.

2. We check if the variable newOffset is set.

3. If it is set, we set the session variable offset to the value of newOffset.

*/

session_start();
if (isset($_POST['newOffset'])) {
    $_SESSION['offset'] = $_POST['newOffset'];
}
?>