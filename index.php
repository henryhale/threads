<?php

require "include/index.php";

checkSessionUser(true);

$title = "Feed";

if (isPOST() && Session::verifyToken($_POST["token"])) {
    Post::create([
        "uid" => Session::getUser("uid"),
        "text" => cleanpost("post"),
        "photo" => cleanpost("image"),
    ]);
    redirect("index.php");
}

$limit = 5;

$offset = isGET() ? cleanget("nxt", 0) : 0;

$posts = Post::list($offset, $limit);

require "views/home/header.php";
?>

<script>
function definePost() {
    return {
        photo: false,
        data: null,
        init() {
            this.$refs.input.onchange = (ev) => {
                ev.preventDefault();
                const file = ev.target.files[0]
                if (!file) return;
                const reader = new FileReader();
                reader.onload = (res) => {
                    this.data = reader.result;
                    this.photo = true;
                }
                reader.readAsDataURL(file);
            }
        },
        insert(){
            this.$refs.input.click();
        },
        remove() {
            this.data = "";
            this.photo = false;
        }
    }
}
</script>
<form x-data="definePost()" method="post" action="index.php" class="w-full p-4 shadow border rounded flex flex-col space-y-2">
    <label class="block">What's on your mind?</label>
    <div class="flex flex-row item-start justify-start space-x-2">
        <textarea name="post" autocomplete="off" spellcheck="false" required class="resize-none flex-grow min-h-[75px] px-2.5 py-1 outline-none bg-transparent border border-slate-300 rounded-md focus:border-blue-500 hover:border-blue-500">Status: Life is Good!</textarea>
        <div class="w-[30%]" x-show="photo" @click="insert">
            <img :src="data" class="w-full rounded-md" />
        </div>
    </div>
    <input type="hidden" class="hidden" name="token" value="<?= Session::generateToken() ?>">
    <input type="file" class="hidden" accept="image/*" x-ref="input">
    <input type="text" class="hidden" name="image" x-model.trim="data">
    <div class="flex items-center">
        <button type="submit" class="flex space-x-2 outline-none rounded px-5 py-1 w-max bg-blue-500 text-white font-medium">
            <svg class="w-5 h-5">
                <use href="#post"></use>
            </svg>
            <span>POST</span>
        </button>
        <div class="flex-grow"></div>
        <button class="bg-slate-100 rounded px-3 py-1.5 rounded-md" type="button" @click="!photo ? insert() : remove()" x-text="photo ? 'Remove Photo' : 'Attach Photo'"></button>
    </div>
</form>

<?php if (count($posts) === 0) { ?>
    <div class="my-10 p-4 text-center opacity-75">
        <p>
            <i>No more posts!<br /><br />Be the first to post something.</i>
        </p>
    </div>
<?php } else { ?>

    <div class='py-10 px-2 relative w-full after:absolute after:w-0.5 after:bg-slate-200 after:top-0 after:bottom-0 after:left-4'>
        <?php foreach ($posts as $p) {

            $ons =
                $p["online"] == 1 ? "border-blue-500/70" : "border-slate-300";
            $pid = $p["pid"];
            $pic = notDefaultImage($p["avatar"])
                ? $p["avatar"]
                : Asset::url("img/" . $p["avatar"]);
            ?>
            <div class='block relative ml-4 pb-12 group'>
                <div class='absolute w-8 rounded-full overflow-hidden top-0 -left-6 text-center text-sm z-10 border-2 <?= $ons ?>'>
                    <img src="<?= $pic ?>" class="w-full bg-white" />
                </div>
                <div class='ml-1.5 py-1 px-1.5 flex items-center space-x-2 opacity-80 group-hover:opacity-100'>
                    <a href="user.php?p=<?= $p[
                        "uid"
                    ] ?>" class='hover:text-blue-600 flex-grow text-base'>
                        <?= $p["name"] ?> - @<?= $p["username"] ?></span>
                    </a>

                    <span class='inline-flex items-center space-x-1'>
                        <svg class='inline-block w-4 h-4'>
                            <title>Like</title>
                            <use href='#like'></use>
                        </svg>
                        <span class="text-sm"><?= Post::likes(
                            $p["pid"]
                        ) ?></span>
                    </span>
                    <span class='inline-flex items-center space-x-1'>
                        <svg class='inline-block w-4 h-4'>
                            <title>Comment</title>
                            <use href='#comment'></use>
                        </svg>
                        <span class="text-sm"><?= count(
                            Post::comments($p["pid"])
                        ) ?></span>
                    </span>
                </div>
                <a href='./post.php?i=<?= $pid ?>' class='block ml-1.5 px-2 py-2 border shadow-sm hover:shadow rounded-md group-hover:border-slate-300'>
                    <div class="flex items-start">
                        <span><?= $p["text"] ?></span>
                        <span class="flex-grow"></span>
                        <span class="text-sm"><?= showtime(
                            $p["time"],
                            $p["ts"]
                        ) ?></span>
                    </div>
                    <?php if (!empty($p["photo"])) { ?>
                        <img src="<?= $p["photo"] ?>" class="rounded-md mt-4"/>
                    <?php } ?>
                </a>
            </div>
        <?php
        } ?>
    </div>

    <fieldset class='my-4 mx-2 flex flex-col border-t'>
        <legend class='mx-auto text-sm px-2'>
            <a href='?nxt=<?= count($posts) === $limit
                ? $limit + $offset
                : 0 ?>' class='flex space-x-2 items-center px-8 py-1 border rounded-md hover:bg-slate-100'>
                <svg class='w-4 h-4 transform rotate-180'>
                    <use href='#refresh'></use>
                </svg>
                <span><?= count($posts) === $limit
                    ? "Load more"
                    : "Refresh" ?></span>
            </a>
        </legend>
    </fieldset>
<?php } ?>

<?php require "views/home/footer.php"; ?>
