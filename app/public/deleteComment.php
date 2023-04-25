<?php
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