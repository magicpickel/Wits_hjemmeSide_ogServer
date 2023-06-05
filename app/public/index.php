<?php
session_start();
date_default_timezone_set('Europe/Copenhagen');
?>
<!doctype html>
<!-- 
1. The code starts with a html form that brings you to the loginLogic.php file when you press login. It also includes a link to the dashboard page without login.
2. It then includes a script that shows the time in the top right corner.
3. The rest of the code is a form where you can login or create a new user. The form uses tailwind css to make it look good.
4. The form is then closed and the html is closed. -->
<html>

<head>
    <title>login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>


    <form action="loginLogic.php" method="post">

        <div class="relative flex min-h-screen flex-col justify-center overflow-hidden bg-gray-50 py-6 sm:py-12">
            <div class="absolute top-0 right-0 p-4 text-sm text-gray-500">
                <span id="time"></span>
                <script>
                    setInterval(() => {
                        document.getElementById('time').innerText = new Date().toLocaleTimeString();
                    }, 1000);
                </script>
            </div>



            <div class="absolute top-0 left-0 p-4 text-sm text-gray-500">

                <a href="dashBoard.php?nonLog=true" class="bg-sky-500 hover:bg-sky-600 px-4 py-2 text-white rounded">Go to dashBoard with out login</a>

            </div>


            <div class="mb-8 text-center">



                <h3 class="flex items-center my-8">

                    <span aria-hidden="true" class="grow bg-gray-200 rounded h-0.5"></span>
                    <span class="text-lg font-medium mx-3">Jens Mikkels Amazing Web Page</span>
                    <span aria-hidden="true" class="grow bg-gray-200 rounded h-0.5"></span>
                </h3>
                <div class="relative bg-white pt-10 pb-8 px-10 shadow-xl mx-auto w-96 rounded-lg">

                    <div class="divide-y divide-gray-300/50 w-full">
                        <div class="space-y-6 py-8 text-base  text-gray-600">

                            <?php if (isset($_GET["message"])) : ?>


                                <p class="text-sm text-red-500"><?= $_GET["message"]; ?></p>
                            <?php endif; ?>

                            <p class="text-xl font-medium leading-7">Login</p>
                            <div class="space-y-4 flex flex-col">
                                <input type="text" name="username" placeholder="Username" class="border border-gray-300/50 p-1 rounded focus:outline-none" />

                                <input type="password" name="password" placeholder="Password" class="border border-gray-300/50 p-1 rounded focus:outline-none" />
                            </div>
                        </div>
                        <div class="pt-8 text-base font-semibold leading-7">
                            <button type="submit" class="bg-sky-500 hover:bg-sky-600 px-4 py-1 text-white rounded">
                                Login
                            </button>
                        </div>
                    </div>
                </div>
                <div class="py-4 px-5 lg:px-6 w-full text-sm text-center bg-gray-50">
                    Don’t have an account yet?
                    <a class="font-medium text-blue-600 hover:text-blue-400" href="register.php">Join today</a>
                </div>
            </div>
    </form>
</body>

</html>