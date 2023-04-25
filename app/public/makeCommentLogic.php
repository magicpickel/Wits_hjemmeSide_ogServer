<?php
session_start();
date_default_timezone_set('Europe/Copenhagen');

require_once(dirname(__DIR__) . '/mainApiLogic/postLogic.php');



$content = $_POST["content"];

$ErrA = array("c" => "");
$ErrT_F = false;



if (empty($content)) {
    $content = "";
    $ErrA["c"] = "main text is required";
    $ErrT_F = true;
}


if ($ErrT_F) {
    $serializedData = serialize($ErrA);
    header("Location: postComment.php?pid=" . $_SESSION['pid'] . "&message=" . urlencode("$serializedData"));
    exit();
}
if (!$ErrT_F) {
    $date = date("Y-m-d h:i:sa");
    $userUid = $_SESSION['user'];
    $pid = $_SESSION['pid'];
    $Post = new Post($userUid, "", "", "", $date, $pid);
    $Post->createComment($content);
    header("Location: dashBoard.php");
}
