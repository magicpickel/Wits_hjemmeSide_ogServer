<?php
/* 
1. It gets the offset and limit from the URL

2. It uses the offset and limit to slice the array of posts

3. It converts the sliced array to JSON

4. It returns the JSON to the client */

require_once(dirname(__DIR__).'/mainApiLogic/apiHub.php');
date_default_timezone_set('Europe/Copenhagen');

$offset = $_GET['offset'] ?? 1;

$limit = 4; 


$latest_posts = array_slice(make_post_array(), $offset, $limit);



echo json_encode($latest_posts);
?>