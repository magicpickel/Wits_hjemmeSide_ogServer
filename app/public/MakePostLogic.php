<?php


session_start();
require_once(dirname(__DIR__) . '/mainApiLogic/postLogic.php');
require_once(dirname(__DIR__) . '/dataBeseLogic/dataBaseImage.php');
require_once(dirname(__DIR__) . '/mainApiLogic/apiHub.php');
date_default_timezone_set('Europe/Copenhagen');
if (isset($_POST['imgUrl'])) {
    $_SESSION['imgUrlT'] = $_POST['imgUrl'];
} else {
    $_SESSION['imgUrlT'] = array();
}
if (!isset($_SESSION['pid'])) {

    $files = scandir(dirname(__DIR__) . '/dataBaseFiles/post');
    $num_files = count($files) - 2;
    $_SESSION['pid'] = $num_files + 1;
}
if (!isset($_SESSION['iidsTp'])) {
    $_SESSION['iidsTp'] = array();
}
if (!isset($_POST['imgUrl'])) {
    $_POST['imgUrl'] = array();
}
$i=count($_POST['imgUrl']);
if ($_SESSION['editPost'] == 'true') {

foreach($_POST['imgUrl'] as $key=>$value){
    foreach($_SESSION['iidsTp'] as $key2=>$value2){
        if($value==$value2){
            unset($_SESSION['iidsTp'][$key2]);
            sort($_SESSION['iidsTp']);
            unset($_POST['imgUrl'][$key]);
            
            
        }
            
    }
    
}

foreach($_SESSION['iidsTp'] as $key3=>$value3){
    $result=testURlnew($value3,$_SESSION['pid']);
    if($result !='true'){
        delete_attachment($_SESSION['pid'],$result);
        unset($_SESSION['iidsTp'][$key3]); 
    }
   
}
    



}
foreach($_POST['imgUrl'] as $key4=>$value4){
    $result=testURlnew($value4,null);
    if($result !='true'){
        add_attachment($_SESSION['pid'],$result);
        unset($_POST['imgUrl'][$key4]);
    }
   
}

$_SESSION['titleT'] = $_POST['title'];
$_SESSION['contentT'] = $_POST['content'];


$userUid = $_SESSION['user'];
$title = $_POST["title"];
$content = $_POST["content"];


if (!isset($_POST['imgUrl'])) {
    $_SESSION['imgUrlT'] = array();
    $img = array();
    $imgAr = array($img);
} else {

    $imgAr = $_POST['imgUrl'];
}




$ErrA = array("t" => "", "c" => "", "i" => array());
$ErrT_F = false;

//1 tjek iid and new img= unset img in array


//2 tjek if img exsist on server= add_attachment





if (empty($title)) {
    $ErrA["t"] = "title is required";
    $ErrT_F = true;
} else {
    $title = test_input($title);
    if (!preg_match("/^[a?!-zA-Z-' ]*$/", $title)) {
        $ErrA["t"] = "only letters and white space allowed";
        $ErrT_F = true;
    }
}

if (empty($content)) {
    $ErrA["c"] = "main text is required";
    $ErrT_F = true;
}

$tjekker = 0;

$i=$i-count($_POST['imgUrl']);

foreach ($imgAr as $img) {

    if (empty($img)) {
        $Withimage = false;
        unset($imgAr[$i]);
    } else {
        $img = test_input($img);

        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $img)) {
            $ErrA["i"][$i] = "invalid URL";
            $ErrT_F = true;
            $Withimage = false;
        } else {
            $pathinfo = pathinfo($img);
            $extension = $pathinfo['extension'];


            $filename = uniqid() . "." . $extension;
            if(!isset($extension)){
                $extension="";
            }

            if (!preg_match("/jpg|jpeg|png|gif|svg$/", $extension)) {
                $ErrA["i"][$i] = "could not save the image, only “.jpg”, “.jpeg”, “.png”, “.gif”, “.svg”. are supported";
                $ErrT_F = true;
                $Withimage = false;
            } else {

                error_reporting(E_ERROR | E_PARSE);
                session_write_close();
                try {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $img);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: close'));
                    $image_data = curl_exec($ch);
                    curl_close($ch);

                    if ($image_data == false) {
                        $ErrA["i"][$i] = "could not download the image, “.jpg”, “.jpeg”, “.png”, “.gif”, “.svg”. are the only supported file formats";
                        $ErrT_F = true;
                        $Withimage = false;
                    } else {
                        if (isset($headers['Content-Type']) && strpos($headers['Content-Type'], 'image/') !== 0) {
                            $ErrA["i"][$i] = "the file is not an image";
                            $ErrT_F = true;
                            $Withimage = false;
                        } else {
                            // Check file size to ensure that it's not too large
                            $max_file_size = 5024 * 5024; // 1 MB
                            $file_size = strlen($image_data);
                            if ($file_size > $max_file_size) {
                                $ErrA["i"][$i] = "the image file size is too large, maximum size allowed is " . $max_file_size / 1024 . " KB";
                                $ErrT_F = true;
                                $Withimage = false;
                            } else {

                                $image_data = null;
                                $tjekker = $tjekker + 1;
                            }
                        }
                    }
                } catch (Exception $e) {
                    $ErrA["i"] = "combined file sizeses to big";
                    $ErrT_F = true;
                    $Withimage = false;
                    $serializedData = serialize($ErrA);
                    header("Location: makePost.php?message=" . urlencode("$serializedData"));
                    exit();
                }
                error_reporting(E_ERROR | E_WARNING | E_PARSE);
            }
        }
    }
    $i = $i + 1;
}

if ($tjekker == sizeof($imgAr)) {
    $Withimage = true;
}


//(var_dump( $i);


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if ($ErrT_F) {
    $serializedData = serialize($ErrA);

    if (isset($_SESSION['editPost']) && $_SESSION['editPost'] == 'true') {
        header("Location: makePost.php?editPost=true&message=" . urlencode("$serializedData"));
    } else {
        header("Location: makePost.php?message=" . urlencode("$serializedData"));
    }

    exit();
}
if (!$ErrT_F && isset($_SESSION['editPost']) && $_SESSION['editPost'] == 'true') {
    $SamePidOr = $_SESSION['pid'];
} else {
    $SamePidOr = "";
}
if (!$ErrT_F) {
    $date = date("Y-m-d h:i:sa");

    if ($Withimage) {
        foreach ($imgAr as $im) {


            $Post = new Post("", "", "", $im, $date, $SamePidOr);
            $Post->createImg();
        }
    }
    $Post = new Post($userUid, $title, $content, "", $date, $SamePidOr);
    $Post->createPost();
    unset($_SESSION['iidsTp']);
    unset($_SESSION['pid']);
    unset($_SESSION['titleT']);
    unset($_SESSION['imgUrlT']);
    unset($_SESSION['contentT']);
    unset($_SESSION['editPost']);
    header("Location: dashBoard.php");
    exit();
}
