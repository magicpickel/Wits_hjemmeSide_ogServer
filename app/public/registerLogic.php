<?php
session_start();
// define variables and set to empty values
require_once(dirname(__DIR__) . '/mainApiLogic/userLogic.php');
date_default_timezone_set('Europe/Copenhagen');

$_SESSION["username"] = $_POST["username"];
$_SESSION["firstname"] = $_POST["firstname"];
$_SESSION["lastname"] = $_POST["lastname"];


$username = $_POST["username"];
$password = $_POST["password"];
$password2 = $_POST["password2"];
$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];

$ErrA = array("u" => "", "p" => "", "f" => "", "l" => "");
$ErrT_F = false;


$user = new User($username, array("uid" => $username, "firstname" => $firstname, "lastname" => $lastname, "date" => ""), $password);


if (empty($username)) {
    $ErrA["u"] = "username is required";
    $ErrT_F = true;
} else {

    if ($user->doseUserEx()) {
        $ErrA["u"] = "Username dose allready exsist";
        $ErrT_F = true;
    }
}



if (empty($password)) {
    $ErrA["p"] = "Password is required";
    $ErrT_F = true;
}else{
    if (strlen($password) < 8) {
        $ErrA["p"] = "Password must be at least 8 characters";
        $ErrT_F = true;
    }
}
if (empty($password2)) {
    $ErrA["p"] = "Repeat Password";
    $ErrT_F = true;
} else {

    // check if username only contains letters and whitespace
    if (!preg_match("/$password2/", $password)) {
        $ErrA["p"] = "Repeated password doesn't match";
        $ErrT_F = true;
    }
}

if (empty($firstname)) {
    $ErrA["f"] = "Firstname is required";
    $ErrT_F = true;
} else {
    $firstname = test_input($firstname);
    // check if username only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/", $firstname)) {
        $ErrA["f"] = "Only letters and white space allowed";
        $ErrT_F = true;
    }
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if ($ErrT_F) {
    $serializedData = serialize($ErrA);
    header("Location: register.php?message=" . urlencode("$serializedData"));
    exit();
}
if (!$ErrT_F) {
    session_start();
    $date = date("Y-m-d h:i:sa");
    $user = new User($username, array("uid" => $username, "firstname" => $firstname, "lastname" => $lastname, "date" => "$date"), $password);
    $user->createU();
    unset($_SESSION["username"]); 
    unset($_SESSION["firstname"]); 
    unset($_SESSION["lastname"]);
    header("Location: index.php?message=" . urlencode('login with new account'));
    exit();
}
