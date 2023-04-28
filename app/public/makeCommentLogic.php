<?php

// Start session to get access to $_SESSION
session_start();

// Set default timezone
date_default_timezone_set('Europe/Copenhagen');

// Require logic to create a post
require_once(dirname(__DIR__) . '/mainApiLogic/postLogic.php');

// Get content from $_POST
$content = $_POST["content"];

// Set up error array and error flag
$ErrA = array("c" => "");
$ErrT_F = false;

// Validate content
if (empty($content)) {
    $content = "";
    $ErrA["c"] = "main text is required";
    $ErrT_F = true;
}

// If there is an error, serialize the error array and redirect
if ($ErrT_F) {
    $serializedData = serialize($ErrA);
    header("Location: postComment.php?pid=" . $_SESSION['pid'] . "&message=" . urlencode("$serializedData"));
    exit();
}

// If there is no error, create the post and redirect to the dashboard
if (!$ErrT_F) {
    $date = date("Y-m-d h:i:sa");
    $userUid = $_SESSION['user'];
    $pid = $_SESSION['pid'];
    $Post = new Post($userUid, "", "", "", $date, $pid);
    $Post->createComment($content);
    header("Location: dashBoard.php");
}
