<?php
$db = new PDO('mysql:dbname=rbk_news;host=localhost;charset=utf8', 'rbk_news_user', 'rbk_news_user_pass');
$fh = fopen(__DIR__ . '/schema.sql', 'r');
while ($line = fread($fh, 4096)) {
    $db->exec($line);
}
fclose($fh);