<?php

require_once "{$_SERVER['DOCUMENT_ROOT']}include/Classes/Db.class.php";

$pages = 10;
$db = new Db();

if (!$db->connect()) {
    die(implode('. ', $db->getErrors()));
}

for($i = 1; $i <= $pages; $i++) {
    if(!set_time_limit(10)) {
        $page = '/page/' . ($i - 1);

        die("Истекло время выполнения скрипта на странице: {$page}");
    }

    $curl = curl_init("http://firstclassmovies.tumblr.com/page/{$i}");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $content = curl_exec($curl);
    curl_close($curl);

    $matches = [];
    preg_match_all('/src="(.*)" alt="(.*?)\. /mi', $content, $matches);

    foreach ($matches[1] as $key => $link) {
        $title = $matches[2][$key];
        $hash = sha1($title);

        $path = "{$_SERVER['DOCUMENT_ROOT']}upload/films/{$hash}.png";
        $file = fopen($path, 'w');

        $curl = curl_init($link);
        curl_setopt($curl, CURLOPT_FILE, $file);
        curl_exec($curl);
        curl_close($curl);

        $query = <<< SQL
            INSERT INTO `Film` (`name`, `hash`)
            VALUES (:name, :hash)
SQL;
        $data = [
            ':name' => $title,
            ':hash' => $hash,
        ];

        if (!$db->query($query, $data)) {
            echo '<pre>';
            print_r(implode('. ', $db->getErrors()));
            echo '</pre>';

            die();
        }

        $serverPath = "/upload/films/{$hash}.png";
        echo <<< HTML
            <div>
                <p>{$title}</p>
                <p>{$hash}</p>
                <p><img src="{$serverPath}" alt=""></p>
            </div>
            <hr>
HTML;

    }
}

$db->close();
