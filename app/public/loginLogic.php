<?php
/* 
1. We get the username and password from the form.

2. We create a new user object and pass the username and password to the constructor.

3. We call the itItcorretLogin() method to check if the username and password are correct or not.

4. If the username and password are correct, we set the session variable login to true and redirect the user to the member.php page.

5. If the username and password are incorrect, we redirect the user to the index.php page with a message to indicate that the username or password is incorrect.

6. The urlencode() function is used to encode the message to be passed to the index.php page.

 This is because we need to pass the message as a query string and the message may contain characters that are not allowed in a query string.
 
 The urlencode() function encodes the message so that it can be used safely in a query string. */

require_once(dirname(__DIR__).'/mainApiLogic/userLogic.php');
date_default_timezone_set('Europe/Copenhagen');
$username = $_POST['username'];



$password = $_POST['password'];



if($username==""||$password=="" ){
    
    header("location: index.php?message=".urlencode('login require both a username and a password'));
    
    exit();
}



$user=new User($username, array("uid"=>$username,"firstname"=>"","lastname"=>"","date"=>""),$password);

if ($user->itItcorretLogin()) {;
    session_start();
    $_SESSION['login'] = true;
    $_SESSION['user']=$username;
    header("location: member.php");
    
} else {
    
    header("location: index.php?message=".urlencode('incorrect username or password'));
    exit();
}
