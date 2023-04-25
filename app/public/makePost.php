<?php

session_start();

require_once(dirname(__DIR__) . '/mainApiLogic/apiHub.php');
date_default_timezone_set('Europe/Copenhagen');
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header("Location: index.php?message=" . urlencode('please login first'));
    exit();
}

//var_dump($_SESSION['editPost']);
if (isset($_GET['editPost']) && $_GET['editPost'] == 'true') {

    $_SESSION['editPost'] = true;
} else {
    $_SESSION['editPost'] = false;
}

if (isset($_SESSION['editPost']) && $_SESSION['editPost'] == 'true') {
    $frontPageV = "Edit post";
    if (isset($_GET['pid'])) {

        $_SESSION['pid'] = $_GET['pid'];

        $tempPostA = make_post_array()[$_GET['pid']];



        $_SESSION['titleT'] = $tempPostA['title'];

        $_SESSION['contentT'] = $tempPostA['content'];

        $_SESSION['imgUrlT'] = array();
        $_SESSION['iidsTp'] = array();

        foreach (array_slice($tempPostA['iid'], 0) as $img) {



            $_SESSION['imgUrlT'][] = $img['path'];

            $_SESSION['iidsTp'][] = $img['path'];
        }
    }
} else {
    $frontPageV = "Make A Post";
}




if (isset($_GET["message"])) {
    $ErrArr = unserialize($_GET["message"]);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
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
    <div>
        <div class="pt-8 text-sm font-semibold">
            <a href="dashBoard.php?editPost=false" class="bg-sky-200 hover:bg-sky-600 px-2 py-2 text-white "><-Back to Frontpage</a>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var offset = 1;
        var limet = 1;
        var loadimgZone = false


        function makeImageZones() {

            if (!loadimgZone) {
                loadimgZone = true;
                $(function() {
                    var html = '<div class= "alert_del">'
                    html += '<div class="mb-6 space-y-4">';
                    html += '<input class="block border placeholder-gray-400 px-5 py-3 leading-6 w-full rounded border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="text" name="imgUrl[]" placeholder="write direct path URl for an image, to upload and attach the image to the post">';
                    html += '<strong class="w-full text-xl border rounded border-gray-200 px-3 hover:text-blue-400 cursor-pointer alert-del">&times;</strong>';
                    html += '</div>';
                    html += '</div>';





                    $('#image-zone').append(html);

                    var alert_del = document.querySelectorAll('.alert-del');
                    alert_del.forEach((x) =>
                        x.addEventListener('click', function() {
                            x.parentElement.classList.add('hidden');

                        })
                    );



                });

                loadimgZone = false;
            }

        }
    </script>



    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold py-2 border-b-2 border-gray-200 mb-4 lg:mb-8">
            <?php
            echo "$frontPageV";
            ?>
        </h2>

    </div>
</body>

<body>
    <form action="MakePostLogic.php" method="post">
        <form onsubmit="return false;" class="space-y-6">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $("form").submit(function() {
                    $(this).find(":hidden").remove();
                });
            </script>

            <div class="text-center mt-5">
                <div class="space-y-6">
                    <div class="space-y-6 p-4 rounded border bg-gray-50">
                        <div class="space-y-1">
                            <input class="block border placeholder-gray-400 px-5 py-3 leading-6 w-full rounded border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="text" name="title" value="<?php echo isset($_SESSION['titleT']) ? $_SESSION['titleT'] : ''; ?>" placeholder="enter a titel">
                            <?php if (isset($_GET["message"])) : ?>
                                <p class="text-sm text-red-500">
                                    <?= $ErrArr["t"]; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="space-y-1">
                            <textarea class="block border placeholder-gray-400 px-5 py-3 leading-6 w-full rounded border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" rows="10" cols="80" type="text" name="content" placeholder="enter main text content"><?php echo isset($_SESSION['contentT']) ? $_SESSION['contentT'] : ''; ?></textarea>
                            <?php if (isset($_GET["message"])) : ?>
                                </textarea>
                                <p class="text-sm text-red-500">
                                    <?= $ErrArr["c"]; ?>
                                </p></textarea>
                            <?php endif; ?></textarea>
                        </div>
                        <div class="space-y-1">
                            <div id="image-zone">
                                <?php


                                if (isset($_SESSION['imgUrlT'])) {

                                    $i = 0;
                                    foreach ($_SESSION['imgUrlT'] as $imgUrlT) {

                                        echo '<div class="mb-6 space-y-4">';
                                        echo '<div class="mb-6 space-y-4">';
                                        if (isset($_GET["message"])) {

                                            echo '<p class="text-sm text-red-500">';
                                            echo isset($ErrArr["i"][$i]) ? $ErrArr["i"][$i] : '';
                                            echo '</p>';
                                        }
                                        echo '<input class="block border placeholder-gray-400 px-5 py-3 leading-6 w-full rounded border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="text" name="imgUrl[]" value="' . (isset($imgUrlT) ? $imgUrlT : "") . '" placeholder="write direct path URl for an image, to upload and attach the image to the post">';
                                        echo '<strong class="w-full text-xl border rounded border-gray-200 px-3 hover:text-blue-400 cursor-pointer alert-del">&times;</strong>';
                                        echo '<script>
                                         var alert_del = document.querySelectorAll(\'.alert-del\');
                                                alert_del.forEach((x) =>
                                                    x.addEventListener(\'click\', function() {
                                                        x.parentElement.classList.add(\'hidden\');

                                                    })
                                                );
                                            </script>';
                                        echo '</div>';
                                        echo '</div>';
                                        $i = $i + 1;
                                    }
                                } ?>

                            </div>
                            <div>
                                <div>
                                    <button type="button" class="text-blue-600 hover:text-blue-400 py-2" onclick="makeImageZones()">Add image</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <button type="submit" class="inline-flex justify-center items-center space-x-2 border font-semibold focus:outline-none w-full px-4 py-3 leading-6 rounded border-blue-700 bg-blue-700 text-white hover:text-white hover:bg-blue-800 hover:border-blue-800 focus:ring focus:ring-blue-500 focus:ring-opacity-50 active:bg-blue-700 active:border-blue-700">
                    <span>Publish post</span>
                </button>
        </form>
    </form>
</body>


</html>