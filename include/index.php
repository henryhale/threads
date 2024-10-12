<?php

require_once __DIR__ . "/env.php";
require_once __DIR__ . "/helpers.php";
require_once __DIR__ . "/session.php";

require_once __DIR__ . "/models/db.php";
require_once __DIR__ . "/models/user.php";
require_once __DIR__ . "/models/alert.php";
require_once __DIR__ . "/models/post.php";
require_once __DIR__ . "/models/message.php";

Asset::setBase("/assets");

Session::init();

Database::connect();

function checkSessionUser(bool $status = true)
{
    if (!Session::isLogged() && $status) {
        redirect("signin.php");
    }
    if (Session::isLogged() && !$status) {
        redirect("index.php");
    }
}
