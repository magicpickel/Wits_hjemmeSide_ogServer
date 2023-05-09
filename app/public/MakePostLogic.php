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
$i = count($_POST['imgUrl']);
/* The code above does the following:
1. Checks if the session variable for the image url is set.
2. If not set, set the session variable.
3. Checks if the session variable for the post id is set.
4. If not set, set the session variable.
5. Checks if the session variable for the image id is set.
6. If not set, set the session variable.
7. Checks if the post array is empty.
8. If not empty, set the session variable. */

if ($_SESSION['editPost'] == 'true') {

    foreach ($_POST['imgUrl'] as $key => $value) {
        foreach ($_SESSION['iidsTp'] as $key2 => $value2) {
            if ($value == $value2) {
                unset($_SESSION['iidsTp'][$key2]);
                sort($_SESSION['iidsTp'],SORT_NATURAL);
                unset($_POST['imgUrl'][$key]);
            }
        }
    }

    foreach ($_SESSION['iidsTp'] as $key3 => $value3) {
        $result = testURlnew($value3, $_SESSION['pid']);
        if ($result != 'true') {
            delete_attachment($_SESSION['pid'], $result);
            unset($_SESSION['iidsTp'][$key3]);
        }
    }
}


foreach ($_POST['imgUrl'] as $key4 => $value4) {
    $result = testURlnew($value4, null);
    if ($result != 'true') {
        add_attachment($_SESSION['pid'], $result);
        unset($_POST['imgUrl'][$key4]);
    }
}
/* The explanation for the code above:
1. We check if the user is editing a post or creating a new one. If he is editing a post we check if the images are already attached to the post. If they are, we check if they are still there. 
If they are not there we delete the attachment from the post and the image from the server. If the image is still there we do nothing.

2. If the user is creating a new post we check if the image is already in the database. If it is we attach it to the post. If it is not we add it to the database and we attach it to the post. */

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

$i = $i - count($_POST['imgUrl']);

/* Explanation for the code above:
1. We start by checking if the title is empty.

2. If it is empty, we put a error message inside the $ErrA array, and set the $ErrT_F to true.

3. If the title is not empty, we test the title with the test_input() function.

4. After testing the title, we check if the title contains any letters, numbers or the characters " - ' ".

5. If it does, we put a error message inside the $ErrA array, and set the $ErrT_F to true.

6. Then we check if the content is empty.

7. If it is empty, we put a error message inside the $ErrA array, and set the $ErrT_F to true.

8. Then we check if the $i is lower than the amount of images inside the $_POST['imgUrl'] array.

9. If it is lower, we put a error message inside the $ErrA array, and set the $ErrT_F to true. */

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
            if (!isset($extension)) {
                $extension = "";
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

                            $max_file_size = 5024 * 5024;
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
/* The code above does the following:
1. Checks if the image is empty, if it is, it gets removed from the array.
2. Checks if the image is a valid URL, if it isn't, it sets an error message.
3. Checks if the image is a valid file extension, if it isn't, it sets an error message.
4. Checks if the image is an actual image, if it isn't, it sets an error message.
5. Checks if the image file size is too large, if it is, it sets an error message.
6. Checks if the image array is the same length as the total array, if it is, it sets the variable $Withimage to true. */

function test_input($data)
/* 
1. The trim() function removes whitespace and other predefined characters from both sides of a string. 
(The predefined characters are spaces, newlines, and tabs.)

2. The stripslashes() function removes backslashes added by the addslashes() function.

3. The htmlspecialchars() function converts special characters to HTML entities. For example, < becomes &lt;. */
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if ($ErrT_F) {
    //tjek wether or not the $ErrT_F is true or false, if it is true, we serialize the $ErrA array, and send it to the makePost.php page.
    $serializedData = serialize($ErrA);

    if (isset($_SESSION['editPost']) && $_SESSION['editPost'] == 'true') {
        header("Location: makePost.php?editPost=true&message=" . urlencode("$serializedData"));
    } else {
        header("Location: makePost.php?message=" . urlencode("$serializedData"));
    }

    exit();
}
if (!$ErrT_F && isset($_SESSION['editPost']) && $_SESSION['editPost'] == 'true') {
    //tjek wether if the $ErrT_F is empty, and if the $_SESSION['editPost'] is true, if it is, we update the $SamePidOr.
    $SamePidOr = $_SESSION['pid'];
} else {
    $SamePidOr = "";
}


if (!$ErrT_F) {
    /* 
1. First we check if there is any error in the title or content.

2. If there is no error we check if the user upload an image with the post.

3. If the user upload an image we loop over the image array and we create a img for each image.

4. At the end we create a post with the title and content and we redirect the user to the dashboard page. */

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
    unset($_SESSION['ofset']);
    header("Location: dashBoard.php");
    exit();
}
