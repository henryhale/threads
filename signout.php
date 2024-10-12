<?php

require_once "include/index.php";

User::logout(Session::getUser("uid"));

Session::destroy();

redirect("signin.php");
