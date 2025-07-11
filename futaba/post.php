<?php
$data = file_exists("threads.json") ? json_decode(file_get_contents("threads.json"), true) : [];

$id = time();
$name = trim($_POST['name']) ?: 'Anonymous';
$email = trim($_POST['email']);
$title = trim($_POST['title']) ?: 'untitled';
$body = trim($_POST['body']) ?: '';
$image = trim($_POST['image']);
$noimage = isset($_POST['noimage']) ? true : false;

$data[] = [
  'id' => $id,
  'name' => $name,
  'email' => $email,
  'title' => $title,
  'body' => $body,
  'image' => $image,
  'noimage' => $noimage,
  'date' => date("Y-m-d H:i:s"),
  'replies' => []
];

file_put_contents("threads.json", json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
header("Location: index.php");
