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

<body class="font-sans min-h-screen flex items-stretch flex-col bg-blue-100">
    <div class="flex items-center p-10 md:p-14 container mx-auto max-w-5xl">
        <a href="index.php" class="flex items-center space-x-2">
            <svg class='inline-block w-6 h-6 outline'>
                <title><?= APP_BRAND ?></title>
                <use href='#logo'></use>
            </svg>
            <h1 class="text-xl font-semibold"><?= APP_BRAND ?></h1>
        </a>
        <span class="flex-grow"></span>
        <a href="mailto:<?= APP_EMAIL ?>">Need help?</a>
    </div>
