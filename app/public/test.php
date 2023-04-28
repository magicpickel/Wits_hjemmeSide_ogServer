<!--code is not use in main program, its for testing -->

<div class="space-y-5 py-1 px-4 text-base text-gray-400">
			<p class="text-xl font-medium leading-7 "> Comments </p>
		</div>	
<div class="mb-8 text-center">
	<body>
		<div id="comment-wall">

			<?php

			foreach (array_slice($post['cid'], 0, 3) as $comment) {

				echo "<div class='comment'>";
				echo "<br>";
				echo "<p>{$comment['content']}</p>";
				echo "<br>";
				echo "<p class='space-y-5 py-1 px-4 text-sm text-gray-400'><strong>{$post['uid']}</strong> posted on {$comment['date']}</p>";
				echo "</div>";
			}

			?>

		</div>

</div>