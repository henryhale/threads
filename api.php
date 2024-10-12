<?php
require "include/index.php";

header("Content-Type: application/json");

if (isPOST()) {
    $body = file_get_contents("php://input");
    $data = json_decode($body, true);
    Message::add($data["uid"], $data["text"]);
    echo json_encode(true);
    exit();
}

$lid = cleanget("i", 0);

echo json_encode(Message::list($lid));
