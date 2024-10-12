<?php
require_once "include/index.php";

if (Session::isLogged()) {
    require "views/home/header.php";
} else {
    require "views/base/header.php";
}
?>

<section class="p-6 my-12 text-center space-y-10">
    <h1 class="text-xl uppercase font-bold">404 | Page Not Found</h1>
    <p>
        <?php if (isGET() && isset($_GET["err"])) {
            echo urldecode($_GET["err"]);
        } else {
            echo "Something went wrong.";
        } ?>
    </p>
    <a href="javascript:void(0)" x-data @click="window.history.back()" class="inline-flex items-center space-x-2 text-blue-600">
       <span>&larr;</span>
       <span>Go Back</span>
    </a>
</section>

<?php if (Session::isLogged()) {
    require "views/home/footer.php";
} else {
    require "views/base/footer.php";
}
