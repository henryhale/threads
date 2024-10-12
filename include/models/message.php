<?php

final class Message extends Database
{
    public static function add(string $uid, string $text)
    {
        return self::insert("messages", ["uid" => $uid, "text" => $text]);
    }

    public static function list($lid = null)
    {
        $query =
            "SELECT messages.*, users.username, users.name, users.avatar, users.online, current_timestamp as ts FROM messages JOIN users ON messages.uid = users.uid ";
        if (!empty($lid)) {
            $query .= " WHERE mid > $lid ";
        }
        $query .= " ORDER BY messages.time DESC";
        $stmt = self::$conn->prepare($query);
        $result = $stmt->execute();
        return $result ? $stmt->fetchAll() : [];
    }
}
