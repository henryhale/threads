<?php

require "include/index.php";

checkSessionUser(true);

$uuid = isset($_GET["p"]) ? cleanget("p") : Session::getUser("uid");

if (isPOST() && Session::verifyToken()) {
    $i = cleanpost("image");
    if (!empty($i)) {
        User::updateProfile(Session::getUser("uid"), [
            "avatar" => $i,
        ]);
        Session::set("avatar", $i);
        Post::create([
            "uid" => Session::getUser("uid"),
            "text" => "Hey! Check out my new profile picture.",
            "photo" => $i,
        ]);
        redirect("user.php?p=$uuid");
    }

    $n = cleanpost("name");
    $b = cleanpost("bio");
    User::updateProfile(Session::getUser("uid"), [
        "name" => $n,
        "bio" => $b,
    ]);
    Session::set("name", $n);
    Session::set("bio", $b);
    redirect("user.php?p=$uuid");
}

$title = "Profile";

require "views/home/header.php";

$itsme = $uuid == Session::getUser("uid");
$ux = User::info(["uid" => $uuid]);
?>

<div x-data="{ edit: false }">

<section class="rounded-md pt-20 px-1.5 pb-1.5 bg-gradient-to-tr from-blue-400 to-purple-400 via-indigo-400 space-y-4">
    <div class="text-center rounded-md bg-white p-4 rounded-md space-y-6">
        <div class="-mt-16">
            <img class="w-24 h-24 rounded-full bg-white border-4 mx-auto <?= $ux[
                "online"
            ]
                ? "border-blue-400 outline outline-white"
                : "border-white" ?>" src="<?= notDefaultImage($ux["avatar"])
    ? $ux["avatar"]
    : Asset::url("img/" . $ux["avatar"]) ?>" />
        </div>
        <h1 class="font-bold text-lg text-center"><?= $ux["name"] ?></h1>
        <p class="font-bold rounded-md p-4 text-lg text-center bg-gradient-to-t from-indigo-100 to-white"><?= $ux[
            "bio"
        ] ?></p>
        <div class="text-center flex items-center justify-center space-x-4">
            <?php if (!$itsme) { ?>
                <a href="#" class="rounded px-5 py-2 w-max bg-blue-500 text-white font-medium">Add Friend</a>
            <?php } else { ?>
                <button @click="edit = !edit" class="rounded px-5 py-2 w-max bg-slate-200 font-medium">Edit Profile</button>
                <a href="signout.php" class="rounded px-5 py-2 w-max bg-slate-200 font-medium md:hidden">Logout</a>
            <?php } ?>
        </div>
        <div class="pt-10 grid sm:grid-cols-2 grid-cols-1 text-slate-600 gap-4">
            <div class="flex items-center space-x-4">
                <?php showicon("user"); ?>
                <span class="font-medium">@<?= $ux["username"] ?></span>
            </div>
            <div class="flex items-center space-x-4">
                <?php showicon("gender"); ?>
                <span><?= $ux["gender"] == "M" ? "Male" : "Female" ?></span>
            </div>
            <div class="flex items-center space-x-4">
                <?php showicon("message"); ?>
                <a class="text-blue-600 underline" href="mailto:<?= $ux[
                    "email"
                ] ?>"><?= $ux["email"] ?></a>
            </div>
            <div class="flex items-center space-x-4">
                <?php showicon("calendar"); ?>
                <span>Joined <?= showtime($ux["joindate"]) ?></span>
            </div>
        </div>
    </div>
</section>

<div class="flex py-4 items-center justify-center">
    <a href="javascript:void(0)" x-data @click="window.history.back()" class="flex space-x-2 items-center bg-slate-100 rounded px-3 py-1.5">
        <svg class="w-5 h-5 rotate-180">
            <title>Back</title>
            <use href="#right"></use>
        </svg>
        <span>Back</span>
    </a>
</div>

<form x-show="edit" action="user.php" method="post" class="py-10 space-y-5 my-4 p-1.5 max-w-md mx-auto">
    <h1 class='text-xl font-bold'>Update Profile</h1>
    <div class="flex flex-col">
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" spellcheck="false" autocomplete="off" required value="<?= $ux[
            "name"
        ] ?>" class="px-2.5 py-1.5 shadow-sm outline-none bg-white/60 hover:bg-white/70 focus:bg-white/70 border border-slate-300 rounded focus:border-blue-600 hover:border-blue-500" />
    </div>
    <div class="flex flex-col">
        <label for="bio">Bio</label>
        <input type="text" name="bio" id="bio" spellcheck="false" autocomplete="off" required value="<?= $ux[
            "bio"
        ] ?>" class="px-2.5 py-1.5 shadow-sm outline-none bg-white/60 hover:bg-white/70 focus:bg-white/70 border border-slate-300 rounded focus:border-blue-600 hover:border-blue-500" />
    </div>
    <div class="text-center">
        <input type="hidden" class="hidden" name="token" value="<?= Session::generateToken() ?>">
        <button type="submit" class="w-full px-2.5 py-1.5 outline-none bg-blue-600/90 hover:bg-blue-600/100 focus:bg-blue-600/100 text-white rounded">
            Save Changes
        </button>
    </div>
</form>


<script>
function definePic() {
    return {
        data: null,
        init() {
            this.$refs.input.onchange = (ev) => {
                ev.preventDefault();
                const file = ev.target.files[0]
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (res) => {
                    this.data = reader.result;
                }
                reader.readAsDataURL(file);
            }
        }
    }
}
</script>

<form x-show="edit" x-data="definePic()" action="user.php" method="post" class="py-10 space-y-5 my-4 p-1.5 max-w-md mx-auto">
    <h1 class='text-xl font-bold'>Profile Picture</h1>
    <div class="flex flex-col">
        <label for="pic">Select a photo</label>
        <input type="text" class="hidden" name="image" x-model.trim="data">
        <input type="file" name="pic" id="pic" accept="image/*" x-ref="input" required value="<?= $ux[
            "avatar"
        ] ?>" class="px-2.5 py-1.5 shadow-sm outline-none bg-white/60 hover:bg-white/70 focus:bg-white/70 border border-slate-300 rounded focus:border-blue-600 hover:border-blue-500" />
    </div>
    <div class="text-center">
        <input type="hidden" class="hidden" name="token" value="<?= Session::generateToken() ?>">
        <button type="submit" class="w-full px-2.5 py-1.5 outline-none bg-blue-600/90 hover:bg-blue-600/100 focus:bg-blue-600/100 text-white rounded">
            Upload
        </button>
    </div>
</form>

</div>
<?php require "views/home/footer.php"; ?>
