<?php


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
