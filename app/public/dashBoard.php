<?php

session_start();
date_default_timezone_set('Europe/Copenhagen');
if (!isset($_SESSION['offset'])) {
	$_SESSION['offset'] = 5;

	$ofset = $_SESSION['offset'];
} else {

	$ofset = $_SESSION['offset'];
}

if (!isset($_SESSION['login']) || !$_SESSION['login']) {

	if (isset($_GET['nonLog']) && $_GET['nonLog'] == 'true') {

		$_SESSION['user'] = 'nonLog';
		$nonLog = false;
	} else {

		header("Location: index.php?message=" . urlencode('please login first'));
		exit();
	}
} else {
	$nonLog = true;
}
if (isset($_GET['editPost']) && $_GET['editPost'] == 'false') {

	unset($_SESSION['pid']);
	unset($_SESSION['titleT']);
	unset($_SESSION['imgUrlT']);
	unset($_SESSION['contentT']);
	unset($_SESSION['editPost']);
}

require_once(dirname(__DIR__) . '/mainApiLogic/apiHub.php');
$userS = $_SESSION['user'];
$trueOR = true;
//var_dump($_SESSION['offset']);

/* The code above does the following:
1. Starts a session
2. Sets a default timezone for the user
3. Checks if the offset variable is set, if not it sets it to 5.
4. Checks if the user is logged in, if not it sets the nonLog variable to false.
5. Checks if the user is editing a post, if not it unsets all the variables that are used for editing a post.
6. Includes the apiHub.php file from the mainApiLogic folder.
7. Sets the userS variable to the session user variable.
8. Sets the trueOR variable to true. */

?>

<!DOCTYPE html>
<html lang="en">


<head>
	<title></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
	<div class="absolute top-0 right-0 p-4 text-sm text-gray-500">
		<span id="time"></span>
		<script>
			setInterval(() => {
				document.getElementById('time').innerText = new Date().toLocaleTimeString();
			}, 1000);
		</script>
	</div>
	<div class="mb-8 text-center">
		<h2 class="text-2xl font-bold py-2 border-b-2 border-gray-200 mb-4 lg:mb-8">
			Frontpage
			<span class="block sm:inline-block text-xl text-gray-600 font-normal">Alle users posts!</span>

			<br>
			<div class="pt-8 text-base font-semibold">
				<?php
				if ($nonLog) {
					echo "<a href='makePost.php' class='bg-sky-500 hover:bg-sky-600 px-4 py-2 text-white rounded'>Make a post </a>";
				}
				?>
			</div>
			<br>
		</h2>
	</div>

	<!-- The code above does the following:
1. Creates a time element in the upper right corner of the page, it updates every second.
2. Creates a link on the front page for the user to make a post if they are logged in. -->

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		var offset = <?php echo $ofset ?>;
		var limit = 0;
		var loading = false;


		userS = <?php echo json_encode($userS); ?>;

		nonLog2 = <?php echo json_encode($nonLog); ?>;

		function setSessionVariable() {

			$_SESSION['editPost'] = true;
		}

		function setSessionVariable3() {

			$_SESSION['editPost'] = false;
		}



		$(window).scroll(function() {


			if ($(window).scrollTop() + $(document).height() >= $(document).height() - $(window).height() - $(document).height()) {

				loadMore();

			}
		});


		function loadMore() {

			if (!loading) {

				loading = true;
				$.getJSON('load-post.php', {
					offset: offset+1
				}, function(data) {
					if (data.length > 0) {
						$.each(data, function(i, post) {
							var html = '<div class="mb-4 text-center space-y-4 p-4 rounded border bg-gray-50">';
							if (post.uid == userS) {
								html += '<div class="py-4 px-5 lg:px-6 w-full text-xl text-center bg-gray-50">';
								html += "Hey " + '<?php echo $userS ?>' + "! <p>";
								html += '</dive>'
								html += '<dive class="mb-1 border-gray-200 block sm:inline-block space-y-o p-2 text-cente text-base py-0 px-2"> Press here to: ';
								html += "<a class='text-lg text-blue-600 border-b-2 hover:text-blue-400' href='makePost.php?pid=" + post.pid + "&editPost=true'>edit post</a>";
							} else {
								html += '<div class="py-1 px-5 lg:px-6 w-full text-xl text-center bg-gray-50">';
							}
							html += '</div>'
							html += '<div class="post">'
							html += '<h2 class="text-2xl font-bold py-2 border-b-2 border-gray-200 mb-4 lg:mb-8">' + post.title + '</h2>';
							html += '<p>' + post.content + '</p>';
							html += '<br>';

							$.each(post.iid, function(i, img) {
								html += '<img class="mx-auto" src="' + img.path + '" alt="">';
								html += '<br>';
							})

							html += '<p><strong>' + post.uid + '</strong> posted on ' + post.date + '</p>';
							html += '<br>';
							html += '</div>';

							$.each(post.cid, function(i, comment) {
								html += '<div class="comment">';
								html += '<p>' + comment.content + '</p>';
								html += '<p class="space-y-5 py-1 px-4 text-sm text-gray-400"><strong>' + comment.uid + '</strong> commented on ' + comment.date + '</p>';
								if (comment.uid == userS) {
									html += '<dive class="mb-1 border-gray-200 block sm:inline-block space-y-o p-2 text-cente text-base py-0 px-2"> Press here to: ';
									html += "<a class='text-lg text-blue-600 border-b-2 hover:text-blue-400' href='deleteComment.php?cid=" + comment.cid + "'>delet comment</a>";


								}

								html += '</div>';
							})
							html += '<br>';
							if (nonLog2) {
								html += '<a href="postComment.php?pid=' + post.pid + '" class="bg-sky-400 hover:bg-sky-500 px-10 py-1 text-white rounded">make a comment </a>';
							}
							html += '</div>';
							html += '</div>';
							$('#post-wall').append(html);

						});

						offset = offset + data.length;
						newOffset = offset;

						var xhttp = new XMLHttpRequest();
						xhttp.open("POST", "update_session.php", true);
						xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						xhttp.send("newOffset=" + newOffset);


					}

					loading = false;


				});

			}
		}
		/* Here is the explanation for the code above:

1. The offset variable is used to keep track of how many posts have been loaded so far.

2. The limit variable is used to set the maximum number of posts to load each time the user scrolls to the bottom of the page.

3. The loading variable is used to prevent the loadMore() function from being called multiple times when the user scrolls to the bottom of the page.
 This variable will be set to true when the loadMore() function is called and set to false when the function has finished.

4. The userS variable is used to store the value of the $_SESSION['username'] variable.

5. The nonLog2 variable is used to determine if the user is logged on or not and will be set to true if the user is not logged on.

6. The loadMore() function is called when the user scrolls to the bottom of the page. This function will make an AJAX call to the load-post.php file and pass the offset value as a parameter. 
The load-post.php file will then get the next set of posts from the database and return them in JSON format. The loadMore() function will then use this data to generate HTML code for each post and append it to the #post-wall element.

7. The offset variable is updated to reflect the number of posts that have been loaded so far.

8. The newOffset variable is used to update the $_SESSION['offset'] variable. This is done so that when the user refreshes the page, the posts that have already been loaded will not be loaded again. */
	</script>

	<style>
		.post {

			margin: 20px 0;
			padding: 20px;
			border: 1px solid #ccc;
		}

		.comment {

			margin: 10px 4;
			padding: 10px;
			border: 1px solid #ccc;

		}
	</style>






	<div id="post-wall">
		<?php

		//echo $_SESSION['offset'];	
		foreach (array_slice($postArray = make_post_array(), 1, $_SESSION['offset']) as $post) {
			echo "<div class='mb-8 text-center'>";
			if ($post['uid'] == $userS) {
				echo "<div class='text-base font-semibold space-y-6 p-4 rounded border bg-gray-50'>";
				echo "Hey " . $userS . "! <p>";
				echo '</dive>';
				echo '<dive class="mb-1 border-gray-200 block sm:inline-block space-y-o p-2 text-cente text-base py-0 px-2"> Press here to: ';
				echo "<a class='text-lg text-blue-600 border-b-2 hover:text-blue-400' href='makePost.php?pid={$post['pid']}&editPost=true'>edit post</a>";
			} else {
				echo "<div class='space-y-6 p-4 rounded border bg-gray-50'>";
			}
			echo "<div class='post'>";
			echo "<h2 class='text-2xl font-bold py-2 border-b-2 border-gray-200 mb-4 lg:mb-8'>{$post['title']}</h2>";
			echo "<p>{$post['content']}</p>";
			echo "<br>";

			foreach (array_slice($post['iid'], 0) as $img) {
				echo "<img class='mx-auto' src='{$img['path']}' alt=''>";
				echo "<br>";
			}


			echo "<p><strong>{$post['uid']}</strong> posted on {$post['date']}</p>";
			echo "</div>";
			echo "<br>";


			foreach (array_slice($post['cid'], 0) as $comment) {
				echo "<div class='comment'>";
				echo "<p>{$comment['content']}</p>";
				echo "<p class='space-y-5 py-1 px-4 text-sm text-gray-400'><strong>{$comment['uid']}</strong> posted on {$comment['date']}</p>";
				if ($comment['uid'] == $userS || $post['uid'] == $userS) {
					echo '<dive class="mb-1 border-gray-200 block sm:inline-block space-y-o p-2 text-cente text-base py-0 px-2"> Press here to: ';
					echo "<a class='text-lg text-blue-600 border-b-2 hover:text-blue-400' href='deleteComment.php?cid={$comment['cid']}'>delete comment</a>";
				}
				echo "</div>";
			}
			echo "<br>";
			if ($nonLog) {
				echo "<a href='postComment.php?pid={$post['pid']}' class='bg-sky-400 hover:bg-sky-500 px-2 py-1 text-white rounded'>make a comment </a>";
			}
			echo "</div>";
			echo "</div>";
		}
		$trueOR = true;

		/* The explanation for the code above:
1. The first foreach loop is to display posts, so it is looping through the post array

2. The first if statement is to check if the user is the owner of the post or not, if the user is the owner of the post then the user can edit the post by clicking on the edit post link

3. The second foreach loop is to display comments, so it is looping through the comment array

4. The second if statement is to check if the user is the owner of the comment or not, if the user is the owner of the comment or the post then the user can delete the comment by clicking on the delete comment link */
		?>
	</div>
	</div>
	<div>
		<div class="pt-8 text-base font-semibold">
			<?php if ($nonLog) {
				echo "<a href='logout.php' class='bg-sky-500 hover:bg-sky-600 px-4 py-2 text-white rounded'>logout</a>";
			} else {
				echo "<a href='logout.php' class='bg-sky-500 hover:bg-sky-600 px-4 py-2 text-white rounded'>login</a>";
			} ?>

		</div>
	</div>

</body>

</html>