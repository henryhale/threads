<?php

final class Asset
{
    private static string $base;

    public static function setBase(string $path)
    {
        self::$base = $path;
    }

    public static function url(string $path): string
    {
        return str_replace("//", "/", self::$base . "/" . $path);
    }
}

function isGET()
{
    return $_SERVER["REQUEST_METHOD"] === "GET";
}

function isPOST()
{
    return $_SERVER["REQUEST_METHOD"] === "POST";
}

function cleanget($key, $default = "")
{
    return isset($_GET[$key])
        ? trim(filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS))
        : $default;
}

function cleanpost($key, $default = "")
{
    return isset($_POST[$key])
        ? trim(filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS))
        : $default;
}

function redirect(string $page)
{
    if (headers_sent()) {
        echo "<p>Redirecting to page...</p><script> window.location.href = '$page'; </script>";
    } else {
        header("location: $page");
    }
    exit();
}

function showerror($msg = null)
{
    if (!is_null($msg)) {
        echo '<p class="text-red-500 bg-red-200 text-lg rounded-md px-4 py-2 border border-red-500 text-center">' .
            $msg .
            "</p>";
    }
}

function showtime($ts, $now = null)
{
    if (is_null($now)) {
        $now = time();
    } else {
        $now = strtotime($now);
    }
    $d = strtotime($ts);
    $diff = $now - $d;

    if ($diff < 60) {
        return $diff . " second" . ($diff != 1 ? "s" : "") . " ago";
    }

    if ($diff < 60 * 60) {
        $diff = floor($diff / 60);
        return $diff . " minute" . ($diff != 1 ? "s" : "") . " ago";
    }

    if ($diff < 60 * 60 * 24) {
        $diff = floor($diff / 60 / 60);
        return $diff . " hour" . ($diff != 1 ? "s" : "") . " ago";
    }

    if ($diff < 60 * 60 * 24 * 30) {
        $diff = floor($diff / 24 / 60 / 60);
        return $diff . " day" . ($diff != 1 ? "s" : "") . " ago";
    }

    $diff = floor($diff / 24 / 60 / 60 / 30);
    return $diff . " month" . ($diff != 1 ? "s" : "") . " ago";
}

function showicon($n)
{
    echo "<span>
        <svg class='w-6 h-6'>
            <use href='#$n'></use>
        </svg>
    </span>";
}

function notDefaultImage(string $path)
{
    $t = strpos($path, "data:image");
    return is_int($t) && $t == 0;
}
