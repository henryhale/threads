<?php

final class Session
{
    private const sessionID = "THREADSESSIONID";

    private const csrfTokenID = "CSRF_TOKEN";

    private const userID = "THREADUSER";

    public static function init()
    {
        if (!session_id() || session_id() == "") {
            session_name(self::sessionID);
            session_start();
        }
    }

    public static function generateToken(): string
    {
        if (!isset($_SESSION[self::csrfTokenID])) {
            $token = md5(random_bytes(64));
            $_SESSION[self::csrfTokenID] = $token;
        } else {
            $token = $_SESSION[self::csrfTokenID];
        }
        return $token;
    }

    public static function verifyToken()
    {
        $tkn = filter_input(INPUT_POST, "token", FILTER_SANITIZE_SPECIAL_CHARS);
        if ($tkn !== $_SESSION[self::csrfTokenID]) {
            unset($_SESSION[self::csrfTokenID]);
            return false;
        }
        return true;
    }

    public static function getUser(string $key)
    {
        return isset($_SESSION[self::userID])
            ? $_SESSION[self::userID][$key]
            : null;
    }

    public static function set($key, $value)
    {
        if (self::isLogged()) {
            $_SESSION[self::userID][$key] = $value;
        }
    }

    public static function setUser(array $data)
    {
        $_SESSION[self::userID] = array_filter($data, function ($k) {
            if ($k === "password") {
                return false;
            }
            return true;
        });
        session_write_close();
    }

    public static function isLogged()
    {
        return isset($_SESSION[self::userID]);
    }

    public static function destroy()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                "",
                time() - 24 * 60 * 60,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }
}
