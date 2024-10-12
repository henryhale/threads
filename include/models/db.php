<?php

function getId($table)
{
    if ($table == "users") {
        return "uid";
    }
    if ($table == "posts") {
        return "pid";
    }
    if ($table == "alerts") {
        return "aid";
    }
    if ($table == "reactions") {
        return "rid";
    }
    return "id";
}

class Database
{
    private const host = "localhost";
    private const port = "3306";
    private const user = "root";
    private const password = "";
    private const database = "threads";

    protected static $conn = null;

    public static function connect()
    {
        if (self::$conn instanceof PDO) {
            return;
        }
        $dsn =
            "mysql:host=" .
            self::host .
            ";port=" .
            self::port .
            ";dbname=" .
            self::database .
            ";charset=utf8";
        self::$conn = new \PDO($dsn, self::user, self::password);
        self::$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        self::$conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }

    protected static function lastInsertId()
    {
        return self::$conn->lastInsertId();
    }

    protected static function insert(string $table, $fields = [])
    {
        $query = "INSERT INTO `" . $table . "` (";
        foreach (array_keys($fields) as $key) {
            $query .= "`" . $key . "`, ";
        }
        $query = substr($query, 0, -2) . ") VALUES (";
        foreach (array_keys($fields) as $key) {
            $query .= ":" . $key . ", ";
        }
        $query = substr($query, 0, -2) . ")";
        $stmt = self::$conn->prepare($query);
        $stmt->execute($fields);
        return self::lastInsertId();
    }

    protected static function select(
        string $table,
        array $fields = [],
        $offset = 0,
        $limit = 10
    ) {
        $query = "SELECT * FROM `" . $table . "`";
        if (!empty($fields)) {
            $query .= " WHERE ";
            foreach (array_keys($fields) as $key) {
                $query .= "`" . $key . "` = :" . $key . " AND ";
            }
            $query = substr($query, 0, -5);
        }
        $query .= " ORDER BY " . $table . "." . getId($table) . " DESC ";
        if (is_int($offset) && is_int($limit)) {
            $query .= " LIMIT " . $offset . ", " . $limit;
        }
        $stmt = self::$conn->prepare($query);
        $status = $stmt->execute($fields);
        $result = [];
        if ($status) {
            $result =
                $limit === 1
                    ? $stmt->fetch(PDO::FETCH_ASSOC)
                    : $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return is_array($result) ? $result : [];
    }

    protected static function selectOne(string $table, array $fields)
    {
        return self::select($table, $fields, 0, 1);
    }

    protected static function update(string $table, $where = [], $fields = [])
    {
        $query = "UPDATE `" . $table . "` SET ";
        foreach (array_keys($fields) as $key) {
            $query .= "`" . $key . "` = :" . $key . ", ";
        }
        $query = substr($query, 0, -2) . " WHERE ";
        foreach (array_keys($where) as $key) {
            $query .= "`" . $key . "` = :" . $key . " AND ";
        }
        $query = substr($query, 0, -5);
        $stmt = self::$conn->prepare($query);
        return $stmt->execute(array_merge($where, $fields));
    }

    protected static function delete(string $table, $fields = [])
    {
        $query = "DELETE FROM `" . $table . "` WHERE ";
        foreach (array_keys($fields) as $key) {
            $query .= "`" . $key . "` = :" . $key . " AND ";
        }
        $query = substr($query, 0, -5);
        $stmt = self::$conn->prepare($query);
        return $stmt->execute($fields);
    }
}
