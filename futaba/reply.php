<?php
$data = file_exists("threads.json") ? json_decode(file_get_contents("threads.json"), true) : [];
$id = $_POST['id'];
$name = trim($_POST['name']) ?: 'Anonymous';
$body = trim($_POST['body']) ?: '';

foreach ($data as &$thread) {
  if ($thread['id'] == $id) {
    $thread['replies'][] = [
      'name' => $name,
      'body' => $body,
      'date' => date("Y-m-d H:i:s")
    ];
    break;
  }
}

file_put_contents("threads.json", json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
header("Location: index.php");
