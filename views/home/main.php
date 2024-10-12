<div class='mt-20 mb-10 container max-w-6xl mx-auto px-2 grid gap-6 grid-cols-4'>
    <div class='hidden md:block col-span-1'>
        <div class='py-4 px-3 border rounded-md shadow space-y-2 '>
            <div class='text-center space-y-1 my-3'>
                <span class='inline-block w-24 h-24 overflow-hidden border-4 rounded-full border-slate-400/30'>
                    <?php  ?>
                    <img class='w-full' src='<?= notDefaultImage(
                        Session::getUser("avatar")
                    )
                        ? Session::getUser("avatar")
                        : Asset::url("img/" . Session::getUser("avatar")) ?>' />
                </span>
                <div class='font-semibold py-1'>
                    <?= Session::getUser("name") ?>
                </div>
                <div class='py-1'>
                    @<?= Session::getUser("username") ?>
                </div>
                <div class='py-5 border-y'>
                    <i><?= Session::getUser("bio") ?></i>
                </div>
            </div>
            <div class='flex flex-col space-y-1'>
                <a class='px-3 py-1.5 flex items-center space-x-2' href='user.php?p=<?= Session::getUser(
                    "uid"
                ) ?>'>
                    <span>
                        <svg class='w-4 h-4'>
                            <use href='#user'></use>
                        </svg>
                    </span>
                    <span>Edit Profile</span>
                </a>
                <a class='px-3 py-1.5 flex items-center space-x-2' href='signout.php'>
                    <span>
                        <svg class='w-4 h-4'>
                            <use href='#right'></use>
                        </svg>
                    </span>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
    <div class='col-span-4 md:col-span-3 lg:col-span-2 px-2'>
