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
//var_dump($_SESSION['editPost']);
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
					offset: offset
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