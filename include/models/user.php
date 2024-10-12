<?php

final class User extends Database
{
    private const table = "users";

    public static function register(array $fields)
    {
        $result = self::selectOne(self::table, ["email" => $fields["email"]]);
        $error = null;

        if (!empty($result)) {
            $error = "Email address already in use.";
        } else {
            $status = self::insert(self::table, $fields);
            if (!$status) {
                $error = "Something went wrong!";
            }
        }

        return [
            "error" => $error,
            "user" => $error ? null : self::lastInsertId(),
        ];
    }

    public static function login(array $fields)
    {
        $result = self::selectOne(self::table, [
            "username" => $fields["username"],
        ]);
        $error = null;
        if (empty($result)) {
            $error = "Account not found! Please register first.";
        } else {
            if (!password_verify($fields["password"], $result["password"])) {
                $error = "Invalid credentials!";
            } else {
                self::update(
                    self::table,
                    ["uid" => $result["uid"]],
                    ["online" => 1, "lastlogin" => date("y-m-d h:m:s")]
                );
            }
        }
        return [
            "error" => !empty($error) ? $error : null,
            "user" => !empty($error) ? null : $result,
        ];
    }

    public static function logout(int $id)
    {
        return self::update(self::table, ["uid" => $id], ["online" => 0]);
    }

    public static function info(array $fields)
    {
        return self::selectOne(self::table, $fields);
    }

    public static function updateProfile(int $id, array $fields)
    {
        return self::update(self::table, ["uid" => $id], $fields);
    }

    public static function list()
    {
        return self::select(self::table, [], null, null);
    }
}
