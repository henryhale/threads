<?php

require "include/index.php";

checkSessionUser(true);

$title = "People";

require "views/home/header.php";

$users = User::list();
?>

<section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    <div class="text-xl font-medium sm:col-span-2 md:col-span-3">
        <h1>Know Your Community</h1>
    </div>
    <?php foreach ($users as $u) { ?>
    <div class="flex flex-col items-center px-4 py-6 rounded-md space-y-4 text-center border">
        <div>
            <img src="<?= notDefaultImage($u["avatar"])
                ? $u["avatar"]
                : Asset::url(
                    "img/" . $u["avatar"]
                ) ?>" class="w-20 rounded-full border-2 <?= $u["online"] == 1
    ? "border-blue-500/70"
    : "border-slate-300" ?>" />
        </div>
        <div class="flex-grow">
            <span><?= $u["name"] ?></span>
            <br>
            <span>@<?= $u["username"] ?></span>
        </div>
        <div>
            <a href="user.php?i=<?= $u[
                "uid"
            ] ?>" class="bg-blue-500 text-white px-5 py-2 rounded-md">View Profile</a>
        </div>
    </div>
    <?php } ?>
</section>

<?php require "views/home/footer.php"; ?>
