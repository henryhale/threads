<?php

final class Alert extends Database
{
    private const table = "alerts";

    public static function add(int $uid, string $text, $pid = null)
    {
        self::insert(self::table, [
            "uid" => $uid,
            "text" => $text,
            "pid" => $pid,
        ]);
    }

    public static function welcome(int $id)
    {
        self::add(
            $id,
            "Welcome to " .
                APP_BRAND .
                "! We are thrilled to have you here. Browse through, post & share updates. Enjoy!"
        );
    }

    public static function login(int $id)
    {
        self::add(
            $id,
            "Login Alert: A device logged into your account using " .
                $_SERVER["REMOTE_ADDR"] .
                " on " .
                date("Y-m-d H:i:s") .
                ". If this is not you, change your password now."
        );
    }

    public static function list(int $id)
    {
        $query =
            "SELECT alerts.*, users.username, users.name, users.avatar, users.online, current_timestamp as ts FROM alerts JOIN users ON alerts.uid = ? ORDER BY alerts.time DESC";
        $stmt = self::$conn->prepare($query);
        $result = $stmt->execute([$id]);
        return $result ? $stmt->fetchAll() : [];
    }

    public static function hasUpdates(int $id)
    {
        return self::selectOne(self::table, ["uid" => $id, "status" => 1]);
    }
}
