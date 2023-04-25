<?php
require_once(dirname(__DIR__).'/mainApiLogic/apiHub.php');
date_default_timezone_set('Europe/Copenhagen');

$offset = $_GET['offset'] ?? 1;

$limit = 4; // Change this to the number of posts to load at once

// Get the latest posts from the server
$latest_posts = array_slice(make_post_array(), $offset, $limit);

// Convert the latest posts to JSON format
//header('Content-Type: application/json');

echo json_encode($latest_posts);
?>