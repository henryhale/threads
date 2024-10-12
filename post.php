<?php
require "include/index.php";

$pid = intval(cleanget("i", 0));
$action = cleanget("a", null);
$data = null;
$liked = false;
$likes = 0;
$comments = [];

if (isPOST() && Session::verifyToken() && $pid) {
    Post::addcomment($pid, Session::getUser("uid"), cleanpost("comment"));
    Alert::add(
        cleanpost("uid"),
        "New comment by " .
            "<a class='underline hover:text-blue-600' href='user.php?i=" .
            Session::getUser("uid") .
            "'>@" .
            Session::getUser("username") .
            "</a>: " .
            cleanpost("comment"),
        $pid
    );
    redirect("post.php?i=$pid");
}

if ($pid) {
    $data = Post::info($pid);
    $liked = Post::liked($pid, Session::getUser("uid"));
    $likes = Post::likes($pid);
    $comments = Post::comments($pid);

    switch ($action) {
        case "like":
            if ($liked) {
                Post::dislike($pid, Session::getUser("uid"));
            } else {
                Post::like($pid, Session::getUser("uid"));
                Alert::add(
                    $data["uid"],
                    "<a class='underline hover:text-blue-600' href='user.php?i=" .
                        Session::getUser("uid") .
                        "'>@" .
                        Session::getUser("username") .
                        "</a> liked your post.",
                    $pid
                );
            }
            redirect("post.php?i=$pid");
            break;

        case "delete":
            Post::remove($pid);
            redirect("index.php");
            break;

        case "delcomment":
            Post::delcomment(cleanget("c", 0));
            redirect("post.php?i=$pid");
            break;

        default:
            break;
    }
}

$title = "Post Details";
require "views/home/header.php";
?>

<?php if (!is_array($data)) { ?>
    <div class="flex flex-col space-y-5 h-96 items-center justify-center">
        <span>:( Opps!</span>
        <span>Post not found.</span>
        <a href="javascript:void(0)" x-data @click="window.history.back()" class="flex space-x-2 items-center bg-slate-100 rounded px-3 py-1.5">
            <svg class="w-5 h-5 rotate-180">
                <title>Back</title>
                <use href="#right"></use>
            </svg>
            <span>Home</span>
        </a>
    </div>
<?php } else { ?>

    <div class="flex flex-col space-y-2 p-2 group">
        <div class="flex flex-nowrap space-x-4 items-center px-1 py-1.5 group-hover:shadow-sm border-b">
            <a href="javascript:void(0)" x-data @click="window.history.back()" class="inline-block p-3 rounded-full hover:bg-slate-100">
                <svg class="w-5 h-5 rotate-180">
                    <title>Back</title>
                    <use href="#right"></use>
                </svg>
            </a>
            <a href="<?= "user.php?p=" .
                $data[
                    "uid"
                ] ?>" class="flex-grow inline-flex space-x-2 items-start hover:bg-slate-100 rounded-3xl">
                <span class='w-10 rounded-full overflow-hidden top-0 -left-6 text-center text-sm z-10 border-2 <?= $data[
                    "online"
                ] == 1
                    ? "border-blue-500/70"
                    : "border-slate-300" ?>'>
                    <img src="<?= notDefaultImage($data["avatar"])
                        ? $data["avatar"]
                        : Asset::url(
                            "img/" . $data["avatar"]
                        ) ?>" alt="photo" class="w-full bg-white">
                </span>
                <span class="flex flex-col">
                    <span><?= $data["name"] . "'s Post" ?></span>
                    <span class="text-sm">@<?= $data["username"] ?></span>
                </span>
            </a>
            <?php if ($data["uid"] == Session::getUser("uid")) { ?>
                <a href="post.php?i=<?= $pid ?>&a=delete" class="inline-block p-3 rounded-full hover:bg-slate-100">
                    <svg class="w-5 h-5 text-red-400">
                        <use href="#trash"></use>
                    </svg>
                </a>
            <?php } ?>
        </div>
        <div class="py-12 px-3 text-center rounded-2xl bg-slate-50 space-y-3">
            <p><?= $data["text"] ?></p>
            <?php if (!empty($data["photo"])) { ?>
                <img src="<?= $data[
                    "photo"
                ] ?>" class="rounded-md mx-auto w-auto min-w-[100px]" />
            <?php } ?>
        </div>
        <div class="px-1 py-1.5 flex items-center space-x-3">
            <a href="post.php?i=<?= $pid ?>&a=like" class="hover:text-blue-700 p-2">
                <span class='inline-flex items-center space-x-1'>
                    <svg class='w-4 h-4'>
                        <title>Like</title>
                        <use href='#like'></use>
                    </svg>
                    <span class="text-sm"><?= $liked ? $likes : "Like" ?></span>
                </span>
            </a>
            <span class='flex-grow inline-flex items-center space-x-1'>
                <svg class='w-4 h-4'>
                    <title>Comment</title>
                    <use href='#comment'></use>
                </svg>
                <span class="text-sm"><?= count($comments) ?></span>
            </span>
            <form class="flex-grow flex justify-end items-stretch px-2" method="post" action="post.php?i=<?= $pid ?>">
                <input class="flex-grow px-2 py-1.5 border outline-none rounded-l-md" placeholder="Comment..." type="text" required name="comment">
                <input type="hidden" class="hidden" name="token" value="<?= Session::generateToken() ?>">
                <input type="text" class="hidden" name="uid" value="<?= $data[
                    "uid"
                ] ?>">
                <button type="submit" class="px-3 outline-noe bg-blue-500 text-white rounded-r-md">
                    <svg class='w-5 h-5 -rotate-45'>
                        <use href='#post'></use>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <?php if (count($comments) > 0) { ?>
    <div class='py-10 px-2 relative w-full after:absolute after:w-0.5 after:bg-slate-200 after:top-0 after:bottom-0 after:left-4'>
        <?php foreach ($comments as $p) { ?>
            <div class='block relative ml-4 pb-12'>
                <div class='absolute w-8 rounded-full overflow-hidden top-0 -left-6 text-center text-sm z-10 border-2 <?= $p[
                    "online"
                ] == 1
                    ? "border-blue-500/70"
                    : "border-slate-300" ?>'>
                    <img src="<?= notDefaultImage($p["avatar"])
                        ? $p["avatar"]
                        : Asset::url(
                            "img/" . $p["avatar"]
                        ) ?>" alt="" class="w-full">
                </div>
                <div class='ml-1.5 py-1 px-1.5 flex items-center space-x-2'>
                    <a href="user.php?i=<?= $p[
                        "uid"
                    ] ?>" class='hover:text-blue-600 flex-grow text-base'>
                        <?= $p["name"] ?> - @<?= $p["username"] ?></span>
                    </a>

                    <?php if ($p["uid"] == Session::getUser("uid")) { ?>
                        <a href="post.php?i=<?= $pid ?>&a=delcomment&c=<?= $p[
    "cid"
] ?>">
                            <svg class="w-4 h-4 text-red-400">
                                <use href="#trash"></use>
                            </svg>
                        </a>
                    <?php } ?>
                </div>

                <div class='ml-1.5 px-2 py-2 border shadow-sm rounded-md'>
                    <div class="flex items-start">
                        <span><?= $p["text"] ?></span>
                        <span class="flex-grow"></span>
                        <span class="text-sm"><?= showtime(
                            $p["time"],
                            $p["ts"]
                        ) ?></span>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php } ?>

<?php } ?>

<?php require "views/home/footer.php"; ?>
