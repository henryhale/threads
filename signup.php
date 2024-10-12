<?php

require_once "include/index.php";

checkSessionUser(false);

$error = isGET() && isset($_GET["err"]) ? urldecode(cleanget("err")) : null;

if (isPOST() && Session::verifyToken()) {
    $name = cleanpost("name");
    $gender = cleanpost("gender");
    $email = cleanpost("email");
    $password = cleanpost("password");

    $result = User::register([
        "name" => $name,
        "email" => $email,
        "username" => strtolower(explode("@", $email)[0]),
        "password" => password_hash($password, PASSWORD_BCRYPT),
        "gender" => $gender,
        "online" => 1,
        "avatar" => $gender == "M" ? "male.jpg" : "female.jpg",
    ]);

    $user = $result["user"];
    $error = $result["error"];

    if ($user) {
        Alert::welcome($user);
        Session::setUser(User::info(["uid" => $user]));
        redirect("index.php");
    }
}

$title = "Sign Up";
require "views/base/header.php";
?>

<div class="pb-10 px-5 flex items-center">
    <div class="w-full max-w-sm mx-auto overflow-hidden">
        <form class="space-y-5 my-4 p-1.5" method="post" action="signup.php">
            <div class="text-center py-4">
                <svg class="inline-block w-10 h-10 outline rounded">
                    <title>Account</title>
                    <use href="#avatar"></use>
                </svg>
                <h1 class='mt-4 text-3xl font-bold'>Create Your Account</h1>
            </div>
            <?= showerror($error) ?>
            <div class='flex flex-col md:flex-grow'>
                <label for='name'>Name</label>
                <input type='text' name='name' id='name' placeholder='John Doe' spellcheck='false' autocomplete='off' required class='px-2.5 py-1.5 shadow-sm outline-none bg-white/60 hover:bg-white/70 focus:bg-white/70 border border-slate-300 rounded focus:border-blue-600 hover:border-blue-500'>
            </div>
            <div class='flex flex-col md:flex-grow'>
                <label for='gender'>Gender</label>
                <select name='gender' id='gender' class="px-2.5 py-2 shadow-sm outline-none bg-white/60 hover:bg-white/70 focus:bg-white/70 border border-slate-300 rounded focus:border-blue-600 hover:border-blue-500">
                    <option value='M'>Male</option>
                    <option value='F'>Female</option>
                </select>
            </div>
            <div class='flex flex-col md:flex-grow'>
                <label for='email'>Email</label>
                <input type='email' name='email' id='email' placeholder='john@doe.me' spellcheck='false' autocomplete='off' required class='px-2.5 py-1.5 shadow-sm outline-none bg-white/60 hover:bg-white/70 focus:bg-white/70 border border-slate-300 rounded focus:border-blue-600 hover:border-blue-500'>
            </div>
            <div class='flex flex-col md:flex-grow'>
                <label for='password'>Password</label>
                <input type='password' name='password' id='password' placeholder='********' spellcheck='false' autocomplete='off' required class='px-2.5 py-1.5 shadow-sm outline-none bg-white/60 hover:bg-white/70 focus:bg-white/70 border border-slate-300 rounded focus:border-blue-600 hover:border-blue-500'>
            </div>
            <div class="text-center max-w-sm mx-auto">
                <input type="hidden" class="hidden" name="token" value="<?= Session::generateToken() ?>">
                <input type="hidden" class="hidden" name="form" value="signup">
                <button type="submit" class="w-full px-2.5 py-1.5 outline-none bg-blue-600/90 hover:bg-blue-600/100 focus:bg-blue-600/100 text-white rounded">
                    Sign Up
                </button>
            </div>
        </form>
        <p class="py-4 opacity-75 text-center">
            Already have an account? <a class="text-blue-600 hover:underline font-medium" href="signin.php">Sign In</a>
        </p>
    </div>
</div>

<?php require "views/base/footer.php";
