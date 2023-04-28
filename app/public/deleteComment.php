<?php
/*
1. get the comment id from the url
2. use the get_comment function to get the comment details, store it in the $comment variable
3. use the delete_comment function to delete the comment using the comment id
4. redirect to the postComment.php page with the pid in the url */
session_start();

if (!isset($_SESSION['login']) || !$_SESSION['login']) {
	header("Location: index.php?message=" . urlencode('please login first'));
	exit();
}
require_once(dirname(__DIR__) . '/mainApiLogic/apiHub.php');
$cid=$_GET['cid'];
$comment=get_comment($cid);
$pid=$comment['pid'];
delete_comment($cid);
header("Location: postComment.php?pid=" . $pid);
?>