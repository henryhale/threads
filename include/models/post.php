<?php

final class Post extends Database
{
    private const table = "posts";

    public static function create(array $fields)
    {
        return self::insert(self::table, $fields);
    }

    public static function list(int $offset = 0, int $limit = 10)
    {
        $query =
            "SELECT " .
            self::table .
            ".*, users.username, users.name, users.avatar, users.online, current_timestamp as ts ";
        $query .=
            " FROM " .
            self::table .
            " JOIN users ON " .
            self::table .
            ".uid = users.uid ORDER BY " .
            self::table .
            ".pid DESC ";
        $query .= " LIMIT " . $offset . ", " . $limit;
        $stmt = self::$conn->prepare($query);
        $result = $stmt->execute();
        return $result ? $stmt->fetchAll() : [];
    }

    public static function info(int $id)
    {
        $query =
            "SELECT " .
            self::table .
            ".*, users.username, users.avatar, users.name, users.online, current_timestamp as ts ";
        $query .=
            " FROM " .
            self::table .
            " JOIN users ON " .
            self::table .
            ".uid" .
            " = users.uid" .
            " WHERE " .
            self::table .
            ".pid" .
            " = " .
            $id;
        $stmt = self::$conn->prepare($query);
        $result = $stmt->execute();
        return $result ? $stmt->fetch() : [];
    }

    public static function remove(int $id)
    {
        return self::delete(self::table, ["pid" => $id]);
    }

    public static function like(int $pid, int $uid)
    {
        $query = "INSERT INTO reactions(uid, pid) VALUES ($uid,$pid) ON DUPLICATE KEY UPDATE uid = $uid";
        $stmt = self::$conn->prepare($query);
        $result = $stmt->execute();
        return !!($result ? $stmt->rowCount() : 0);
    }

    public static function dislike(int $pid, int $uid)
    {
        return self::delete("reactions", ["pid" => $pid, "uid" => $uid]);
    }

    public static function likes(int $pid)
    {
        $query = "SELECT COUNT(*) likes FROM reactions WHERE pid = $pid";
        $stmt = self::$conn->prepare($query);
        $result = $stmt->execute();
        if ($result) {
            $w = $stmt->fetch();
            return $w["likes"];
        }
        return 0;
    }

    public static function liked(int $pid, int $uid)
    {
        $result = self::selectOne("reactions", [
            "uid" => $uid,
            "pid" => $pid,
        ]);
        return !!(is_array($result) ? count($result) : 0);
    }

    public static function addcomment(int $pid, int $uid, string $text)
    {
        $query = "INSERT INTO comments(uid, pid, text) VALUES(?,?,?)";
        $stmt = self::$conn->prepare($query);
        $result = $stmt->execute([$uid, $pid, $text]);
        return !!($result ? $stmt->rowCount() : 0);
    }

    public static function delcomment(int $cid)
    {
        return self::delete("comments", ["cid" => $cid]);
    }

    public static function comments($pid)
    {
        $query =
            "SELECT comments.*, users.username, users.name, users.avatar, users.online, current_timestamp as ts FROM comments JOIN users ON comments.uid = users.uid WHERE pid = ? ORDER BY comments.time DESC";
        $stmt = self::$conn->prepare($query);
        $result = $stmt->execute([$pid]);
        return $result ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }
}
