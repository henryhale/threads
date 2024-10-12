<?php

require_once "include/index.php";

$error = isGET() && isset($_GET["err"]) ? urldecode($_GET["err"]) : null;

if (isPOST() && Session::verifyToken()) {
    $username = cleanpost("username");
    $password = cleanpost("password");

    $result = User::login([
        "username" => $username,
        "password" => $password,
    ]);

    $user = $result["user"];
    $error = $result["error"];

    if ($user) {
        Alert::login($user["uid"]);
        Session::setUser($user);
        redirect("index.php");
    }
}

$title = "Sign In";
require "views/base/header.php";
?>

<div class="py-10 px-5 flex items-center">
    <div class="w-full max-w-sm mx-auto rounded-md">
        <form class="w-full space-y-5 my-4 p-1.5" method="post" action="signin.php">
            <div class="py-4 text-center">
                <svg class="inline-block w-10 h-10 outline rounded">
                    <title>Account</title>
                    <use href="#avatar"></use>
                </svg>
                <h1 class='mt-4 text-3xl font-bold'>Welcome!</h1>
            </div>
            <?php if (!is_null($error)) { ?>
                <p class="text-red-500 bg-red-100 border border-red-200 rounded-md py-1.5 text-sm text-center"><?= $error ?></p>
            <?php } ?>
            <div class="flex flex-col">
                <label for="username">Username</label>
                <div class="relative">
                    <span class="absolute top-0 px-2.5 py-1.5">@</span>
                    <input type="text" name="username" id="username" spellcheck="false" autocomplete="off" required class="w-full pl-8 pr-2.5 py-1.5 shadow-sm outline-none bg-white/60 hover:bg-white/70 focus:bg-white/70 border border-slate-300 rounded focus:border-blue-600 hover:border-blue-500" />
                </div>
            </div>
            <div class="flex flex-col">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" spellcheck="false" autocomplete="off" required value="" class="px-2.5 py-1.5 shadow-sm outline-none bg-white/60 hover:bg-white/70 focus:bg-white/70 border border-slate-300 rounded focus:border-blue-600 hover:border-blue-500" />
            </div>
            <div class="text-center">
                <input type="hidden" class="hidden" name="token" value="<?= Session::generateToken() ?>">
                <button type="submit" class="w-full px-2.5 py-1.5 outline-none bg-blue-600/90 hover:bg-blue-600/100 focus:bg-blue-600/100 text-white rounded">
                    Sign In
                </button>
            </div>
        </form>
        <p class="py-4 opacity-75 text-center">
            Don't have an account? <a class="text-blue-600 hover:underline font-medium" href="signup.php">Sign Up</a>
        </p>
    </div>
</div>

<?php require "views/base/footer.php";
