<?php

$tabs = [
    ["text" => "Feed", "href" => "index.php", "icon" => "home"],
    ["text" => "People", "href" => "people.php", "icon" => "users"],
    ["text" => "Chat", "href" => "chat.php", "icon" => "message"],
    ["text" => "Alerts", "href" => "alerts.php", "icon" => "bell"],
]; ?>

<!DOCTYPE html>
<html lang="en" class="text-slate-800">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= APP_BRAND ?> <?= isset($title) ? "| " . $title : "" ?></title>
    <link rel="shortcut icon" href="<?= Asset::url(
        "favicon.svg"
    ) ?>" type="image/svg+xml" />
    <script src="<?= Asset::url("js/tailwind.js") ?>"></script>
    <script defer src="<?= Asset::url("js/alpine.js") ?>"></script>
</head>

<body class="min-h-screen flex items-stretch flex-col md:flex-row md:justify-center">
    <div class='fixed z-20 top-0 left-0 right-0 font-sans shadow border-b bg-white'>
        <div class='max-w-6xl mx-auto flex items-center p-2'>
            <div class='flex items-center space-x-2 ml-1.5 text-center font-semibold'>
                <svg class='inline-block w-5 h-5 outline'>
                    <use href='#logo'></use>
                </svg>
                <span><?= APP_BRAND ?></span>
            </div>
            <div class='flex-grow flex items-stretch justify-center md:space-x-2'>
                <?php foreach ($tabs as $tab) { ?>
                    <a href='<?= $tab[
                        "href"
                    ] ?>' class='inline-flex space-x-2 py-2 px-4
                    <?= $title == $tab["text"]
                        ? "font-semibold text-blue-600"
                        : "opacity-80 hover:opacity-100 hover:text-blue-600" ?>'>
                        <span>
                            <svg class='w-6 h-6'>
                                <use href='#<?= $tab["icon"] ?>'></use>
                            </svg>
                        </span>
                        <span class='hidden sm:inline-block'>
                            <?= $tab["text"] ?>
                        </span>
                    </a>
                <?php } ?>
            </div>
            <div>
                <a href='user.php' class='inline-flex space-x-2 py-2 px-4
                    <?= $title == "Profile"
                        ? "font-semibold text-blue-600"
                        : "opacity-80 hover:opacity-100 hover:text-blue-600" ?>'>
                    <span>
                        <svg class='w-6 h-6'>
                            <use href='#user'></use>
                        </svg>
                    </span>
                    <span class='hidden md:inline-block'>Profile</span>
                </a>
            </div>
        </div>
    </div>

    <?php
    require __DIR__ . "/icons.php";
    require __DIR__ . "/main.php";


?>
