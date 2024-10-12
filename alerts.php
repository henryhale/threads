<?php

require "include/index.php";

checkSessionUser(true);

$title = "Alerts";

$alerts = Alert::list(Session::getUser("uid"));

require "views/home/header.php";
?>

<h1 class="px-2 py-4 text-xl font-semibold">Notifications</h1>

<div class="p-2 grid grid-cols-1 gap-2">
    <?php if (count($alerts) == 0) { ?>
    <div>
        No updates yet.
    </div>
    <?php } ?>
    <?php foreach ($alerts as $a) { ?>
        <div class="px-3 py-2 hover:bg-slate-100 rounded-md <?= $a["status"] ==
        1
            ? "border-blue-400 border-l-2 bg-blue-100"
            : "bg-slate-100" ?>">
            <?php if (isset($a["text"])) { ?>
                <div class="flex items-center">
                    <span class=""><?= $a["text"] ?></span>
                    <span class="flex-grow"></span>
                    <?php if (!empty($a["pid"])) { ?>
                        <a href="post.php?i=<?= $a[
                            "pid"
                        ] ?>" class="py-2 px-3 text-blue-600">View Post</a>
                    <?php } ?>
                </div>
                <i class="block text-sm">▪️ <?= showtime(
                    $a["time"],
                    $a["ts"]
                ) ?></i>
            <?php } else { ?>
                <span> [alert: not found] </span>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<?php require "views/home/footer.php"; ?>
