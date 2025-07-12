<?php
// threads.jsonからスレッド読み込み
$filename = "threads.json";
$threads = file_exists($filename) ? json_decode(file_get_contents($filename), true) : [];

$now = time();
$one_week_seconds = 7 * 24 * 60 * 60;

// 1週間より古いスレッドを除外した新配列作成
$filtered_threads = [];
foreach ($threads as $thread) {
    $thread_time = strtotime($thread['date']);
    if ($thread_time === false || ($now - $thread_time) <= $one_week_seconds) {
        // 日付不明or1週間以内のスレは残す
        $filtered_threads[] = $thread;
    }
}

// もし変わってたらファイルに書き込み保存
if (count($filtered_threads) !== count($threads)) {
    file_put_contents($filename, json_encode($filtered_threads, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $threads = $filtered_threads;
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>untitled</title>
</head>
<body>

<!--

ソースコード見るなホモガキ掘るぞ

.　　　　　　▃▆█▇▄▖
　　　　　▟◤▖　　　◥█▎
　　　◢◤　　▐　　　　▐▉
　▗◤　　　▂　▗▖　　▕█▎
　◤　▗▅▖◥▄　▀◣　　█▊
▐　▕▎◥▖◣◤　　　　◢██
█◣　◥▅█▀　　　　▐██◤
▐█▙▂　　　　　　◢██◤
　◥██◣　　　　◢▄◤
　　　▀██▅▇▀

-->
<center><h1>untitled</h1></center>
<hr>
<!-- スレ投稿フォーム -->
<center>
<div class="container">
<table border="0">
  <tr>
   <td>
<form action="post.php" method="post">
  name: <input type="text" name="name"><br>
  E-mail: <input type="text" name="email"><br>
  title: <input type="text" name="title"><input type="submit" value="Post"><br>
  comment: <textarea name="body" rows="4" cols="40"></textarea><br>
  image URL: <input type="text" name="image"><br>
  no image?: <input type="checkbox" name="noimage" value="1"><br>
</form>
    </td>
   </tr>
</table>
</div>
</center>

<hr>

<!-- スレ一覧 -->
<?php foreach(array_reverse($threads) as $thread): ?>
  <div>
    <div style="display: flex;">
      <?php if (empty($thread['noimage']) && !empty($thread['image'])): ?>
        <img src="<?= htmlspecialchars($thread['image']) ?>" alt="画像" width="100" style="margin-right:10px;">
      <?php endif; ?>
      <div>
        <strong>(<?= htmlspecialchars($thread['title']) ?>)</strong>
        <?= htmlspecialchars($thread['name']) ?> - <?= htmlspecialchars($thread['date']) ?>
        [<a href="#thread<?= $thread['id'] ?>">reply</a>]<br>
        <?= nl2br(htmlspecialchars($thread['body'])) ?>
      </div>
    </div>

    <!-- 返信一覧 -->

    <?php foreach($thread['replies'] as $reply): ?>
      <table bgcolor="#f0f0f0" style="border: none; margin-left: 20px; margin-top: 8px; margin-bottom: 8px; width: 95%;">
        <tr>
          <td><?= htmlspecialchars($reply['name']) ?> (<?= htmlspecialchars($reply['date']) ?>)<br><?= nl2br(htmlspecialchars($reply['body'])) ?></td>
        </tr>
      </table>
    <?php endforeach; ?>

    <!-- 返信フォーム -->
    <form action="reply.php" method="post" id="thread<?= $thread['id'] ?>" style="margin-left: 10px; margin-top: 10px;">
      <input type="hidden" name="id" value="<?= $thread['id'] ?>">
      name: <input type="text" name="name">
      comment: <input type="text" name="body">
      <input type="submit" value="reply">
    </form>

    <hr>
  </div>
<?php endforeach; ?>

</body>
</html>
