<?php
session_start();
date_default_timezone_set('Europe/Copenhagen');
?>
<!doctype html>
<html>
<!-- Here is the explanation for the code above:
1. The form is submitted to the registerLogic.php file on the server

2. The form is validated on the server

3. If the form is validated correctly, the user is redirected to the login page

4. If the form is not validated correctly, the user is redirected to the register page and an error message is displayed
 -->
<head>
    <meta charset="UTF-8">
    <meta username="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .error {
            color: #FF0000;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_GET["message"])) {
        $ErrArr = unserialize($_GET["message"]);
    }
   
    ?>
    <form action="registerLogic.php" method="post">
        <div class="bg-gray-100">
            <div class="absolute top-0 right-0 p-4 text-sm text-gray-500">
                <span id="time"></span>
                <script>
                    setInterval(() => {
                        document.getElementById('time').innerText = new Date().toLocaleTimeString();
                    }, 1000);
                </script>
            </div>
            <div class="container xl:max-w-7xl mx-auto px-4 py-16 lg:px-8 lg:py-32">
                <div class="flex flex-col rounded-xl shadow-sm bg-white overflow-hidden">
                    <div class="p-5 lg:p-6 grow w-full md:w-3/4 lg:w-3/5 xl:w-2/5 mx-auto">
                        <div class="text-center mt-5">
                            <h6 class="text-sm uppercase font-bold tracking-wider text-blue-600 mb-1">
                                Register account
                            </h6>
                            <h1 class="text-2xl font-bold mb-1">
                                Jens Mikkels Amazing Web Page
                            </h1>
                            <p class="text-sm text-gray-600 font-medium mb-5">
                                Enter your info to complete registration
                            <div class="space-y-6">
                                <form onsubmit="return false;" class="space-y-6">
                                    <div class="space-y-6 p-4 rounded border bg-gray-50">
                                        <div class="space-y-1">
                                            <input class="block border placeholder-gray-400 px-5 py-3 leading-6 w-full rounded border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="text" name="username" value='<?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : ''; ?>' placeholder="Enter your username">
                                            <?php if (isset($_GET["message"])) : ?>

                                                <p class="text-sm text-red-500"><?= $ErrArr["u"]; ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="space-y-6 p-4 rounded border bg-gray-50">
                                        <div class="space-y-1">
                                            <input class="block border placeholder-gray-400 px-5 py-3 leading-6 w-full rounded border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="password" name="password" placeholder="Enter your password">
                                        </div>
                                        <div class="space-y-1">
                                            <input class="block border placeholder-gray-400 px-5 py-3 leading-6 w-full rounded border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="password" name="password2" placeholder="Repeat password">
                                            <?php if (isset($_GET["message"])) : ?>

                                                <p class="text-sm text-red-500"><?= $ErrArr["p"]; ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="space-y-6 p-4 rounded border bg-gray-50">
                                        <div class="space-y-1">
                                            <input class="block border placeholder-gray-400 px-5 py-3 leading-6 w-full rounded border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="text" name="firstname" value='<?php echo isset($_SESSION["firstname"]) ? $_SESSION["firstname"] : ''; ?>' placeholder="Enter your firstname">
                                            <?php if (isset($_GET["message"])) : ?>

                                                <p class="text-sm text-red-500"><?= $ErrArr["f"]; ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="space-y-1">
                                            <input class="block border placeholder-gray-400 px-5 py-3 leading-6 w-full rounded border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="text" name="lastname" value='<?php echo isset($_SESSION["lastname"]) ? $_SESSION["lastname"] : ''; ?>' placeholder="Enter your lastname">
                                        </div>
                                    </div>
                                    <button type="submit" class="inline-flex justify-center items-center space-x-2 border font-semibold focus:outline-none w-full px-4 py-3 leading-6 rounded border-blue-700 bg-blue-700 text-white hover:text-white hover:bg-blue-800 hover:border-blue-800 focus:ring focus:ring-blue-500 focus:ring-opacity-50 active:bg-blue-700 active:border-blue-700">
                                        <span>Register</span>
                                    </button>
                                </form>
                            </div>
                            <div class="py-4 px-5 lg:px-6 w-full text-sm text-center bg-gray-50">
                                Already have an account?
                                <a class="font-medium text-blue-600 hover:text-blue-400" href="index.php">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</body>

</html>