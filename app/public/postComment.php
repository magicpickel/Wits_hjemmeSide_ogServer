<?php
/* 
1. Start the session.

2. Set the timezone for the session.

3. Check if the user is logged in or not.

4. Check if the pid is set or not.

5. Set the pid in the session.

6. Require the apiHub.php file which is in the mainApiLogic folder.

7. If the message is set then unserialize it and set it in the ErrArr variable. */

session_start();
date_default_timezone_set('Europe/Copenhagen');

if (!isset($_SESSION['login']) || !$_SESSION['login']) {
	header("Location: index.php?message=" . urlencode('please login first'));
	exit();
}
if (!isset($_GET['pid'])) {
	header("Location: index.php?message=" . urlencode('please login first'));
	exit();
}
$_SESSION['pid'] = $_GET['pid'];
require_once(dirname(__DIR__) . '/mainApiLogic/apiHub.php');
$post = make_post_array()[$_GET['pid']];
if (isset($_GET['message'])) {
	$ErrArr = unserialize($_GET['message']);
}

?>

<!DOCTYPE html>
<!-- Here is the explanation for the code above:

1. The user is able to view the post and comments on it.

2. When the user clicks on the “Logout” button they will be logged out and redirected to the login page.

3. When the user clicks on the “Back to Frontpage” button they will be redirected to the frontpage.

4. When the user clicks on the “Publish comment” button their comment will be published.

5. The user is able to see the time in the top right corner.

6. The user is able to see the post title, content, images, and the user who posted it.

7. The user is able to see the comment content and the user who posted it.

8. If the user does not enter any comment content, an error message will be displayed.

9. The user is able to see the date and time the comment was posted.

10. The user is able to see the date and time the post was posted. -->
<html lang="en">


<head>
	<title></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://cdn.tailwindcss.com"></script>
</head>
<div>
	<div class="pt-8 text-base font-semibold">
		<a href="dashBoard.php" class="bg-sky-500 hover:bg-sky-600 px-2 py-2 text-white rounded"><-Back to Frontpage</a>

	</div>
</div>

<div class="mb-8 text-center">

	<body>
		<div class="absolute top-0 right-0 p-4 text-sm text-gray-500">
			<span id="time"></span>
			<script>
				setInterval(() => {
					document.getElementById('time').innerText = new Date().toLocaleTimeString();
				}, 1000);
			</script>
		</div>
		<?php
		echo "<div class='post'>";
		echo "<h2 class='text-4xl font-bold py-2 border-b-2 border-gray-200 mb-4 lg:mb-8'>{$post['title']}</h2>";
		echo "<p>{$post['content']}</p>";
		echo "<br>";
		foreach (array_slice($post['iid'], 0) as $img) {
			echo "<img class='mx-auto' src='{$img['path']}' alt=''>";
			echo "<br>";
		}
		echo "<br>";
		echo "<p><strong>{$post['uid']}</strong> posted on {$post['date']}</p>";
		echo "</div>";
		?>
	</body>
</div>

<style>
	.post {

		margin: 60px 0;
		padding: 80px;
		border: 1px solid #ccc;
	}

	.comment {

		margin: 10px 0;
		padding: 10px;
		border: 2px solid #ccc;

	}
</style>
</head>
<div class="space-y-5 py-1 px-4 text-base text-gray-400">
	<p class="text-xl font-medium leading-7 "> Comments </p>
</div>
<div class="mb-8 text-center">

	<body>
		<div id="comment-wall">

			<?php

			foreach (array_slice($post['cid'], 0) as $comment) {

				echo "<div class='comment'>";
				echo "<p>{$comment['content']}</p>";
				echo "<p class='space-y-5 py-1 px-4 text-sm text-gray-400'><strong>{$comment['uid']}</strong> commented on {$comment['date']}</p>";
				echo "</div>";
			}

			?>

		</div>

</div>
<div>

	<form action="makeCommentLogic.php" method="post">
		<form onsubmit="return false;" class="space-y-6">
			<div class="text-center mt-5">
				<div class="space-y-6">
					<div class="space-y-6 p-4 rounded border bg-gray-50">

						<textarea class="block border placeholder-gray-400 px-5 py-3 leading-6 w-full rounded border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="text" name="content" placeholder="enter main text comment" rows="10" cols="80"></textarea><?php if (isset($_GET["message"])) : ?>


							</textarea>
							<p class="text-sm text-red-500"><?= $ErrArr["c"]; ?></p></textarea>
						<?php endif; ?></textarea>

					</div>

					<div>
						<button type="submit" class="inline-flex justify-center items-center space-x-2 border font-semibold focus:outline-none w-full px-4 py-3 leading-6 rounded border-blue-700 bg-blue-700 text-white hover:text-white hover:bg-blue-800 hover:border-blue-800 focus:ring focus:ring-blue-500 focus:ring-opacity-50 active:bg-blue-700 active:border-blue-700">
							<span>Publish comment</span>
						</button>
					</div>
				</div>

		</form>
</div>
<div>
	<div class="pt-8 text-base font-semibold">
		<a href="logout.php" class="bg-sky-500 hover:bg-sky-600 px-4 py-2 text-white rounded">logout</a>

	</div>
</div>
</div>
</body>

</html>